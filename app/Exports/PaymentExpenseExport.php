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

class PaymentExpenseExport implements FromView
{
    private $payment_expense;

    public function __construct($payment_expense){
        $this->payment_expense =$payment_expense;
    }
    public function view(): View
    {
        return view('exports.payment_expense', [
            'payment_expense' => $this->payment_expense
        ]);
    }
}