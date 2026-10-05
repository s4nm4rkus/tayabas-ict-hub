<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Reads the uploaded CSV/Excel into a plain collection of rows, keyed by
 * the (lower-cased, snake_case) heading names. It deliberately does NO
 * validation and NO saving — that all lives in OpeningBalanceImportService,
 * so the "check only" mode can never write anything by accident.
 */
class OpeningBalanceImport implements ToCollection, WithHeadingRow
{
    public Collection $rows;

    public function __construct()
    {
        $this->rows = collect();
    }

    public function collection(Collection $rows): void
    {
        $this->rows = $rows;
    }
}