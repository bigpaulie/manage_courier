<?php
/**
 * Created by PhpStorm.
 * User: Abhishek
 * Date: 25-12-2018
 * Time: 14:37
 */

namespace App\Exports;


use App\Models\Courier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CourierExport implements FromView
{
    private $courier;

    public function __construct($courier){
        $this->courier =$courier;
    }
    public function view(): View
    {
        return view('exports.courier', [
            'courier' => $this->courier
        ]);
    }
}