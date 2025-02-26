<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class AttendancePhotoService
{
    /**
     * Upload attendance proof photos
     *
     * @param array $files Array of UploadedFile objects
     * @param int $employeeId
     * @param string $attendanceType
     * @param string $shiftType
     * 
     * @return array Paths to the stored files
     */
    public function uploadProofs(
        array $files,
        int $employeeId,
        string $attendanceType,
        string $shiftType
    ): array {
        $today = now()->format('Y-m-d');
        $attendanceFolderPath = "attendance-proofs/$employeeId/$today/$shiftType-$attendanceType";
        
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