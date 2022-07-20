<?php
  
  
namespace App\Exports;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class EmployeesExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array{
        return [
            'ID',
            'Lastname',
            'Firstname',
            'Middlename',
        ];
    }

    public function query()
    {
        return $collection = Employee::select(
            'id', 
            'last_name',
            'first_name',
            'middle_name',
        );
    }
}

