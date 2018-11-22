@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Barcode</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Couriers</span></li>
                <li><span>Barcode</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <section class="panel">
        <header class="panel-heading">

            <a href="javascript:void(0);" onclick="printDiv('printableArea')"  class="btn btn-primary pull-right" style="margin-left: 10px;"><i class="fa fa-print"></i> Print</a>
            <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-primary pull-right"><i class="fa  fa-long-arrow-left"></i> Back</a>

            <h2 class="panel-title">{{$courier->unique_name}}</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Id: </label>
                    <label class="control-label text-primary"><strong>{{$courier->unique_name}}</strong></label>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Agent Name: </label>
                    <label class="control-label text-primary"><strong>{{$courier->agent->name}}</strong></label>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Customer Name: </label>
                    <label class="control-label text-primary"><strong>{{$courier->r_name}}</strong></label>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Country: </label>
                    <label class="control-label text-primary"><strong>{{$courier->receiver_country->name}}</strong></label>
                </div>
                <div class="col-md-2">
                    <label class="control-label">Tracking No: </label>
                    <label class="control-label text-primary"><strong>{{$courier->tracking_no}}</strong></label>
                </div>


            </div>

            <div class="row" style="padding-top: 10px; padding-bottom: 10px;"></div>

            @if($courier->barcode_no != null)
            <div class="row" style="padding-top: 10px; padding-bottom: 10px; text-align: center" id="printableArea">
                <div class="col-md-12">
                    <label class="control-label" style="text-align: center">
                    <?php
                        //echo DNS2D::getBarcodeHTML($courier->barcode_no, "QRCODE");

                        echo DNS1D::getBarcodeSVG($courier->barcode_no, "PHARMA2T",4,44);

                        ?>
                    </label>
                </div>

            </div>

           @endif
        </div>

    </section>


    <!-- end: page -->

@endsection

@section('scripts')

    <script type="text/javascript">

        jQuery(document).ready(function($) {

        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }


    </script>

@endsection
