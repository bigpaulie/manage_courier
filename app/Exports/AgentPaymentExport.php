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

class AgentPaymentExport implements FromView
{
    private $agent_payment;

    public function __construct($agent_payment){
        $this->agent_payment =$agent_payment;
    }
    public function view(): View
    {
        return view('exports.agent_payment', [
            'agent_payment' => $this->agent_payment
        ]);
    }
}