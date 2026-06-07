<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;

class StudentsPreviewImport implements ToCollection, WithHeadingRow
{
    /**
     * Collect all rows and return them as a Collection.
     * The heading row is automatically skipped.
     */
    public function collection(Collection $rows)
    {
        // stored on the instance so the controller can access it
        $this->rows = $rows;
    }

    public function getRows(): Collection
    {
        return $this->rows ?? collect();
    }
}
