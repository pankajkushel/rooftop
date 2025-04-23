<?php
namespace App\Exports;

use App\Models\ZipCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ZipCodesExport implements FromCollection, WithHeadings
{
    /**
     * Fetch data for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ZipCode::all(['country', 'state', 'city', 'zip_code']);
    }

    /**
     * Define the headings for the export file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Country',
            'State',
            'City',
            'ZIP Code',
        ];
    }
}
