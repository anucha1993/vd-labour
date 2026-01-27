<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\Sheets\ExpiringCidMoneySheet;
use App\Exports\Sheets\ExpiringPassportSheet;
use App\Exports\Sheets\ExpiringIdCardSheet;
use App\Exports\Sheets\ExpiringCIDFactorySheet;
use App\Exports\Sheets\ExpiringCIDConstructSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ExpiringDiseaseFactorySheet;
use App\Exports\Sheets\ExpiringDiseaseConstructSheet;

class LabourAlertsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new ExpiringPassportSheet(),
            new ExpiringIdCardSheet(),
            new ExpiringDiseaseConstructSheet(),
            new ExpiringDiseaseFactorySheet(),
            new ExpiringCIDConstructSheet(),
            new ExpiringCIDFactorySheet(),
            new ExpiringCidMoneySheet(),
 
        ];
    }
}
