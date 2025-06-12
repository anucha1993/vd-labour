<?php
namespace App\Exports;

use App\Exports\Sheets\ExpiringPassportSheet;
use App\Exports\Sheets\ExpiringDiseaseConstructSheet;
use App\Exports\Sheets\ExpiringDiseaseFactorySheet;
use App\Exports\Sheets\ExpiringCIDConstructSheet;
use App\Exports\Sheets\ExpiringCIDFactorySheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LabourAlertsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new ExpiringPassportSheet(),
            new ExpiringDiseaseConstructSheet(),
            new ExpiringDiseaseFactorySheet(),
            new ExpiringCIDConstructSheet(),
            new ExpiringCIDFactorySheet(),
        ];
    }
}
