<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class AttendancePhotoService
{
    /**
     * Upload attendance proof photos
     *
     * @param array $files Array of UploadedFile objects
     * @param Employee $employee
     * @param string $attendanceType
     * @param string $shiftType
     * @param Carbon|null $date
     * @return array Paths to the stored files
     */
    public function uploadProofs(
        array $files,
        Employee $employee,
        Carbon $dateToday,
        string $attendanceType,
        string $shiftType
    ): array {
        $attendanceFolderPath = "attendance-proofs/{$employee->id}/{$dateToday->format('Y-m-d')}/$shiftType-$attendanceType";
        
        $fileTypes = [
            'screenshot_workstation_selfie' => 'selfie',
            'screenshot_cgc_chat' => 'cgc',
            'screenshot_department_chat' => 'dept',
            'screenshot_team_chat' => 'team',
            'screenshot_group_chat' => 'group',
        ];
        
        $paths = [];
        
        foreach ($fileTypes as $inputName => $fileName) {
            if (!isset($files[$inputName])) {
                continue;
            }
            
            $file = $files[$inputName];
            $paths[$inputName] = $file->storeAs(
                $attendanceFolderPath, 
                $fileName . '.' . $file->extension(), 
                ['disk' => 'public']
            );
        }
        
        return $paths;
    }
}