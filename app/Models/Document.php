<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $casts = [
        'document_type' => DocumentType::class,
        'status' => RequestStatus::class,
    ];

    protected $fillable = [
        'employee_id',
        'updated_by',
        'document_type',
        'status',
        'file_path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function isPending()
    {
        return $this->status === RequestStatus::PENDING;
    }

    public function isApproved()
    {
        return $this->status === RequestStatus::APPROVED;
    }

    public function isRejected()
    {
        return $this->status === RequestStatus::REJECTED;
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['status'] ?? null, fn($q, $status) =>
                $q->where('status', $status))
            ->when($filters['document_type'] ?? null, fn($q, $documentType) =>
                $q->where('document_type', $documentType));
    }
}
