<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Enums\RequestStatus;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('create', Document::class)) abort(403);

        $filters = $request->only(['status', 'document_type']);
        
        $documents = Document::with(['employee', 'updatedBy'])
            ->filter($filters)
            ->when($request->user()->isEmployee(), fn($q) => $q->where('employee_id', $request->user()->employee->id))
            ->latest();
        
        return view('document.index', ['documents' => $documents->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('document.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => ['required', new Enum(DocumentType::class)],
            'document_file' => ['required', 'file', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('document_file');
        $employeeId = $request->user()->employee->id;
        $documentType = $validated['document_type'];
        $filePath = $file->store("documents/$employeeId/$documentType", 'public');

        $document = Document::create([
            'employee_id' => $employeeId,
            'updated_by' => $employeeId,
            'document_type' => $documentType,
            'status' => RequestStatus::PENDING,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('document.show', $document)
            ->with('success', 'Document uploaded successfully and pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Document $document)
    {
        if ($request->user()->cannot('view', $document)) abort(403);

        return view('document.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Document $document)
    {
        if ($request->user()->cannot('update', $document)) abort(403);

        return view('document.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        if ($request->user()->cannot('update', $document)) abort(403);

        $validated = $request->validate([
            'document_type' => ['sometimes', new Enum(DocumentType::class)],
            'document_file' => ['sometimes', 'file', 'max:10240'], // 10MB max
            'status' => ['sometimes', new Enum(RequestStatus::class)],
        ]);

        $data = [
            'document_type' => $validated['document_type'],
            'updated_by' => $request->user()->employee->id,
        ];

        // Only admins and HR can change the status
        if ($request->user()->isAdmin() || $request->user()->isHr()) {
            $data['status'] = $validated['status'];
        }

        // If a new file is uploaded
        if ($request->hasFile('document_file')) {
            // Delete the old file if it exists
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('document_file');
            $employeeId = $request->user()->employee->id;
            $documentType = $validated['document_type'];
            $filePath = $file->store("documents/$employeeId/$documentType", 'public');
            $data['file_path'] = $filePath;
        }

        $document->update($data);

        return redirect()
            ->route('document.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Document $document)
    {
        if ($request->user()->cannot('delete', $document)) abort(403);

        // Delete the file if it exists
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('document.index')
            ->with('success', 'Document deleted successfully.');
    }
}