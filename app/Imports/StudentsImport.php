<?php
namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    protected $userId;
    protected $sectionId;
    protected $subjectId;

    public function __construct($userId,  $sectionId, $subjectId)
    {
        $this->userId = $userId;
        $this->sectionId = $sectionId;
        $this->subjectId = $subjectId;
    }

    public function collection(Collection $rows)
{
    foreach ($rows as $row) {
        try {
            // Log the row data
            Log::info('Importing student: ', $row->toArray());

            // Parse date_of_birth
            $dateOfBirth = \DateTime::createFromFormat('m/d/Y', $row['date_of_birth']);
            if ($dateOfBirth) {
                $dateOfBirth = $dateOfBirth->format('Y-m-d');
            } else {
                $dateOfBirth = null; // or set a default value
            }

            // Create a new student record with section_id and subject_id
            Student::create([
                'student_number' => $row['student_number'] ?? '',
                'first_name' => $row['first_name'] ?? '',
                'last_name' => $row['last_name'] ?? '',
                'middle_name' => $row['middle_name'] ?? '',
                'date_of_birth' => $dateOfBirth,
                'gender' => $row['gender'] ?? '',
                'course' => $row['course'] ?? '',
                'user_id' => $this->userId,
                'section_id' => $this->sectionId,
                'subject_id' => $this->subjectId,
            ]);
        } catch (\Exception $e) {
            // Log the error or handle it
            Log::error('Error importing student: ' . $e->getMessage());
        }
    }
}

}
