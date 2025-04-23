<?php

namespace App\Imports;

use App\Models\ZipCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ZipCodesImport implements ToModel, WithHeadingRow
{
    /**
     * Define how each row should be mapped to the model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find existing ZipCode entry by the zip_code
        $zipcode = ZipCode::where('zip_code', $row['zip_code'])->first();

        if ($zipcode) {
            // Update the existing record
            $zipcode->update([
                'country'  => $row['country'],
                'state'    => $row['state'],
                'city'     => $row['city'],
            ]);
            return $zipcode;
        }

        // If not found, create a new ZipCode entry
        return new ZipCode([
            'country'  => $row['country'],
            'state'    => $row['state'],
            'city'     => $row['city'],
            'zip_code' => $row['zip_code'],
        ]);
    }

}
