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

class WalkingCustomerExport implements FromView
{
    private $walking_customer;

    public function __construct($walking_customer){
        $this->walking_customer =$walking_customer;
    }
    public function view(): View
    {
        return view('exports.walking_customer', [
            'walking_customer' => $this->walking_customer
        ]);
    }
}