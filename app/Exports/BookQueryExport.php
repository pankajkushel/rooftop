<?php

namespace App\Exports;

use App\Models\Appointment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookQueryExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $appointments = Appointment::where('status', '0')
            ->select([
                'name',
                'email',
                'mobile',
                'address',
                'pincode',
                'city',
                'country',
                'created_at',
            ])
            ->get();

        // Add serial numbers
        $appointments = $appointments->map(function ($appointment, $key) {
            return [
                'S.No' => $key + 1, // Add serial number starting from 1
                'Name' => $appointment->name,
                'Email' => $appointment->email,
                'Mobile' => $appointment->mobile,
                'Address' => $appointment->address,
                'Pincode' => $appointment->pincode,
                'City' => $appointment->city,
                'Country' => $appointment->country,
                'Created At' => $appointment->created_at 
                    ? Carbon::parse($appointment->created_at)->format('Y-m-d h:i:s')
                    : null,
            ];
        });

        return $appointments;
    }

    /**
     * Define the headings for the export file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'S.No',
            'Name',
            'Email',
            'Mobile',
            'Address',
            'Pincode',
            'City',
            'Country',
            'Created At',
        ];
    }
}

