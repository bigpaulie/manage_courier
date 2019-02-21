<?php
/**
 * Created by PhpStorm.
 * User: Abhishek
 * Date: 21-02-2019
 * Time: 10:16
 */

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ManifestDetailExport implements FromView
{
    private $manifest;

    public function __construct($manifest){
        $this->manifest =$manifest;
    }
    public function view(): View
    {
        return view('exports.manifest_details', [
            'data' => $this->manifest
        ]);
    }
}