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

class ManifestReportExport implements FromView
{
    private $manifests;

    public function __construct($manifests){
        $this->manifests =$manifests;
    }
    public function view(): View
    {
        return view('exports.manifest_report', [
            'manifests' => $this->manifests
        ]);
    }
}