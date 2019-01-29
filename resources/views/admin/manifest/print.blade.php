@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manifest Details</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Manifest</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-info pull-right" onclick="printDiv('printableArea')"><i class="fa fa-print"></i> </button>
        </div>
    </div>


    <section class="panel" id="printableArea">
        <div class="panel-body">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-6 mt-md">
                            <h2 class="h2 mt-none mb-sm text-dark text-bold">Manifest</h2>
                            <h4 class="h4 m-none text-dark text-bold">{{$manifest->unique_name}}</h4>
                        </div>

                        <div class="col-sm-6 text-right mt-md mb-md">

                            <address class="ib mr-xlg">
                                <p class="h5 mb-xs text-dark text-semibold">Manifest Details:</p>
                                <br>
                                <b class="text-uppercase">Vendor: {{$manifest->vendor->name}}</b>

                                <br>
                                <b class="text-uppercase">Amount: {{$manifest->amount}}</b>

                                <br>
                                Create Date: {{date('d-m-y',strtotime($manifest->created_at))}}
                            </address>

                        </div>
                    </div>
                </header>

                    <h3 class="h3 mt-none mb-sm text-dark text-bold" >Item Details</h3>

                    <div class="table-responsive">
                        <table class="table invoice-items">
                            <thead>
                            <tr class="h5 text-dark">
                                <th id="cell-id" class="text-semibold">Id</th>
                                <th id="cell-item" class="text-semibold">Sender Name</th>
                                <th id="cell-item" class="text-semibold">Recipient Name</th>
                                <th id="cell-desc" class="text-semibold">Source</th>
                                <th id="cell-price" class="text-center text-semibold">Destination</th>
                                {{--<th id="cell-qty" class="text-center text-semibold">No. of Boxes</th>--}}
                                {{--<th id="cell-total" class="text-center text-semibold">Weight(KGS)</th>--}}
                                <th id="cell-total" class="text-center text-semibold">Sign</th>


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($manifest_couriers as $item_courier)

                                <tr>
                                    <td>{{$item_courier->unique_name}}</td>
                                    <td class="text-semibold text-dark">

                                        {{$item_courier->s_name}} ({{$item_courier->s_company}})
                                    </td>
                                    <td class="text-semibold text-dark">
                                        {{$item_courier->r_name}} ({{$item_courier->r_company}})</td>
                                    <td class="text-capitalize">
                                        {{$item_courier->s_address1}}
                                        <br>
                                        {{$item_courier->s_city}}, {{$item_courier->s_state}}
                                        <br>
                                        {{$item_courier->sender_country->name}}
                                        <br>
                                        Ph: {{$item_courier->s_phone}}
                                        </td>
                                    <td class="text-center text-capitalize">

                                        {{$item_courier->r_address1}}
                                        <br>
                                        {{$item_courier->r_city}}, {{$item_courier->r_state}}
                                        <br>
                                        {{$item_courier->receiver_country->name}}, {{$item_courier->r_zip_code}}
                                        <br>
                                        Ph: {{$item_courier->r_phone}}

                                    </td>
                                    {{--<td class="text-center">{{$item_courier->no_of_boxes}}</td>--}}
                                    {{--<td class="text-center">@if(isset($item_courier->shippment)){{$item_courier->shippment->weight}}@endif</td>--}}
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>




            </div>

        </div>
    </section>

@endsection

@section('scripts')

    <script>

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
