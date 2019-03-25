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

                <li><span>Bulk Payment</span></li>
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
                                <b class="text-uppercase">Amount: {{$manifest->amount}}</b>

                                <br>
                                Manifest Date: {{date('d-m-y',strtotime($manifest->manifest_date))}}
                            </address>

                        </div>
                    </div>
                </header>


                    <h3 class="h3 mt-none mb-sm text-dark text-bold" >Bulk Details</h3>
                {!! Form::open(['url' => 'admin/manifest/save_bulk_payment','method'=>'post','id'=>'frmBulkPayment']) !!}
                 {{csrf_field()}}
                    <div class="table-responsive">
                        <table class="table invoice-items">
                            <thead>
                            <tr class="h5 text-dark">
                                <th id="cell-id" class="text-semibold">Id</th>
                                <th id="cell-item" class="text-semibold">Sender Name</th>
                                <th id="cell-item" class="text-semibold">Recipient Name</th>
                                <th id="cell-desc" class="text-semibold">Source</th>
                                <th id="cell-price" class="text-semibold">Destination</th>
                                <th id="cell-qty" class="text-semibold">No. of Boxes</th>
                                <th id="cell-total" class="text-semibold">Weight(KGS)</th>
                                <th id="cell-total" class="text-semibold">Amount</th>


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($manifest_details  as $key => $md)

                                <tr>
                                    <td>Bulk - {{$key+1}}</td>
                                    <td class="text-semibold text-dark">

                                        {{$md['sender_name']}}
                                    </td>
                                    <td class="text-semibold text-dark">
                                        {{$md['recipient_name']}}</td>
                                    <td class="text-capitalize">
                                        {{$md['source']}}
                                    </td>
                                    <td>

                                        {{$md['destination']}}

                                    </td>
                                    <td class="">{{$md['weight']}}</td>

                                    <td class="">{{$md['no_of_boxes']}}</td>
                                    <td>
                                        <input type="text" name="manifest[{{$key}}][bulk_payment]" class="form-control" value="{{$md['bulk_payment']}}">
                                        <input type="hidden" name="manifest[{{$key}}][manifest_id]" class="form-control" value="{{$manifest->id}}">
                                        <input type="hidden" name="manifest[{{$key}}][item_id]" class="form-control" value="{{$md['item_id']}}">
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="8">
                                    <footer class="panel-footer center">
                                        <a href="/admin/manifest" class="btn btn-warning">Back</a>
                                        <button type="submit" name="save_box" value="save_box" class="btn btn-primary">Save</button>
                                    </footer>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                {!! Form::close() !!}

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
