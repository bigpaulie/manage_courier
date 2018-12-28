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


    <section class="panel">
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
                               Create Date: {{date('d-m-y',strtotime($manifest->created_at))}}
                            </address>

                        </div>
                    </div>
                </header>
                @if(count($manifest_items) > 0)
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
                            <th id="cell-qty" class="text-center text-semibold">No. of Boxes</th>
                            <th id="cell-total" class="text-center text-semibold">Weight(KGS)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($manifest_items as $mi)
                            <?php $courier_id = $mi['courier_id'];
                                  $item_courier = \App\Models\Courier::find($courier_id);
                            ?>
                        <tr>
                            <td>{{$item_courier->unique_name}}</td>
                            <td class="text-semibold text-dark">{{$item_courier->s_name}} ({{$item_courier->s_company}})</td>
                            <td class="text-semibold text-dark">{{$item_courier->r_name}} ({{$item_courier->r_company}})</td>
                            <td>{{$item_courier->s_state}}, {{$item_courier->sender_country->name}}</td>
                            <td class="text-center">{{$item_courier->r_state}}, {{$item_courier->receiver_country->name}}</td>
                            <td class="text-center">{{$item_courier->no_of_boxes}}</td>
                            <td class="text-center">@if(isset($item_courier->shippment)){{$item_courier->shippment->weight}}@endif</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @endif
                @if(count($manifest_bulks) > 0)
                <h3 class="h3 mt-none mb-sm text-dark text-bold" >Bulk Details</h3>

                <div class="table-responsive">
                    <table class="table invoice-items">
                        <thead>
                        <tr class="h5 text-dark">
                            <th id="cell-id" class="text-semibold">Id</th>
                            <th id="cell-item" class="text-semibold">Sender Name</th>
                            <th id="cell-item" class="text-semibold">Recipient Name</th>
                            <th id="cell-desc" class="text-semibold">Source</th>
                            <th id="cell-price" class="text-center text-semibold">Destination</th>
                            <th id="cell-qty" class="text-center text-semibold">No. of Boxes</th>
                            <th id="cell-total" class="text-center text-semibold">Weight(KGS)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($manifest_bulks as $bkey=> $mb)
                            <?php $courier_ids = explode(",",$mb['courier_id']);
                            $bulk_couriers = \App\Models\Courier::whereIn('id', $courier_ids)->get();
                            ?>
                            <tr>
                                <td colspan="6" align="center"><h5 class="h5 text-semibold text-primary">Bulk - {{$bkey}}</h5></td>
                            </tr>
                            @foreach($bulk_couriers as $bc)
                            <tr>
                                <td>{{$bc->unique_name}}</td>
                                <td class="text-semibold text-dark">{{$bc->s_name}} ({{$bc->s_company}})</td>
                                <td class="text-semibold text-dark">{{$bc->r_name}} ({{$bc->r_company}})</td>
                                <td>{{$item_courier->s_state}}, {{$bc->sender_country->name}}</td>
                                <td class="text-center">{{$bc->r_state}}, {{$bc->receiver_country->name}}</td>
                                <td class="text-center">{{$bc->no_of_boxes}}</td>
                                <td class="text-center">@if(isset($bc->shippment)){{$bc->shippment->weight}}@endif</td>
                            </tr>
                           @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>

        </div>
    </section>

@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {

        });



    </script>

@endsection
