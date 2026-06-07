<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsPreviewImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentBulkImportController extends Controller
{
    /**
     * Show the bulk import page.
     */
    public function index()
    {
        return view('admin.students.bulk-import');
    }

    /**
     * Parse the uploaded Excel file and return preview data as JSON.
     * Called via AJAX after file + class/semester selection.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file'         => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
            'program_type' => ['required', 'in:+2,Degree'],
            'class'        => ['nullable', 'required_if:program_type,+2', 'in:11,12'],
            'semester'     => ['nullable', 'required_if:program_type,Degree', 'in:1st Sem,3rd Sem,5th Sem'],
        ]);

        try {
            $import = new StudentsPreviewImport();
            Excel::import($import, $request->file('file'));
            $rows = $import->getRows();
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not parse file: ' . $e->getMessage(),
            ], 422);
        }

        if ($rows->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'The file appears to be empty or has no valid rows.',
            ], 422);
        }

        $class    = $request->program_type === '+2' ? $request->class    : null;
        $semester = $request->program_type === 'Degree' ? $request->semester : null;

        $preview  = [];
        $errors   = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because row 1 is heading

            // Normalize keys — handle common header variations
            $name    = trim($row->get('name') ?? $row->get('full_name') ?? $row->get('student_name') ?? '');
            $phone   = trim((string) ($row->get('phone') ?? $row->get('phone_number') ?? $row->get('mobile') ?? ''));
            $roll_no = trim((string) ($row->get('roll_no') ?? $row->get('roll_number') ?? $row->get('roll') ?? ''));

            $rowErrors = [];

            if (empty($name))    $rowErrors[] = 'Name is required';
            if (empty($phone))   $rowErrors[] = 'Phone is required';
            if (empty($roll_no)) $rowErrors[] = 'Roll No is required';

            // Check duplicates in DB
            if (!empty($phone) && Student::where('phone', $phone)->exists()) {
                $rowErrors[] = 'Phone already registered';
            }

            $preview[] = [
                'row'      => $rowNum,
                'name'     => $name,
                'phone'    => $phone,
                'roll_no'  => $roll_no,
                'class'    => $class,
                'semester' => $semester,
                'errors'   => $rowErrors,
                'valid'    => empty($rowErrors),
            ];
        }

        $validCount   = collect($preview)->where('valid', true)->count();
        $invalidCount = collect($preview)->where('valid', false)->count();

        return response()->json([
            'success'       => true,
            'rows'          => $preview,
            'valid_count'   => $validCount,
            'invalid_count' => $invalidCount,
            'total'         => count($preview),
        ]);
    }

    /**
     * Perform the actual import after the admin confirms the preview.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file'         => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
            'program_type' => ['required', 'in:+2,Degree'],
            'class'        => ['nullable', 'required_if:program_type,+2', 'in:11,12'],
            'semester'     => ['nullable', 'required_if:program_type,Degree', 'in:1st Sem,3rd Sem,5th Sem'],
            'skip_invalid' => ['nullable', 'boolean'],
        ]);

        try {
            $import = new StudentsPreviewImport();
            Excel::import($import, $request->file('file'));
            $rows = $import->getRows();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Could not parse file: ' . $e->getMessage()]);
        }

        $class    = $request->program_type === '+2' ? $request->class    : null;
        $semester = $request->program_type === 'Degree' ? $request->semester : null;

        $imported = 0;
        $skipped  = 0;

        DB::transaction(function () use ($rows, $class, $semester, $request, &$imported, &$skipped) {
            foreach ($rows as $row) {
                $name    = trim($row->get('name') ?? $row->get('full_name') ?? $row->get('student_name') ?? '');
                $phone   = trim((string) ($row->get('phone') ?? $row->get('phone_number') ?? $row->get('mobile') ?? ''));
                $roll_no = trim((string) ($row->get('roll_no') ?? $row->get('roll_number') ?? $row->get('roll') ?? ''));

                if (empty($name) || empty($phone) || empty($roll_no)) {
                    $skipped++;
                    continue;
                }

                // Skip duplicate phones
                if (Student::where('phone', $phone)->exists()) {
                    $skipped++;
                    continue;
                }

                Student::create([
                    'name'     => $name,
                    'phone'    => $phone,
                    'roll_no'  => $roll_no,
                    'class'    => $class,
                    'semester' => $semester,
                ]);

                $imported++;
            }
        });

        return redirect()->route('admin.students.index')
            ->with('success', "{$imported} students imported successfully." . ($skipped > 0 ? " {$skipped} rows were skipped." : ''));
    }

    /**
     * Download a sample Excel template.
     */
    public function template()
    {
        // Create a simple CSV template
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_template.csv"',
        ];

        $callback = function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['name', 'phone', 'roll_no']);
            fputcsv($out, ['John Doe', '9876543210', 'R001']);
            fputcsv($out, ['Jane Smith', '9876543211', 'R002']);
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
