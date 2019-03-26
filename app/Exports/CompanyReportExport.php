<?php
/**
 * Created by PhpStorm.
 * User: Abhishek
 * Date: 09-02-2019
 * Time: 12:47
 */

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyReportExport implements FromView
{
    private $company_payments;

    public function __construct($company_payments){
        $this->company_payments =$company_payments;
    }
    public function view(): View
    {
        return view('exports.company_report', [
            'company_payments' => $this->company_payments
        ]);
    }
}