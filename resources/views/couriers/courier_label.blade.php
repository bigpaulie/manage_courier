@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Courier Details</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Courier</span></li>
                <li><span>{{$courier->unique_name}}</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Prints</h2>
        </header>
        <div class="panel-body">
            <a href="/{{Auth::user()->user_type}}/couriers/{{$courier->id}}"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default">Bill</button></a>

            <a href="/{{Auth::user()->user_type}}/courier_label/{{$courier->id}}"><button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Lable</button></a>

            <button type="button" class="mb-xs mt-xs mr-xs btn btn-success">Package</button>

            <a href="/{{Auth::user()->user_type}}/courier_report/{{$courier->id}}"><button type="button" class="mb-xs mt-xs mr-xs btn btn-info">Invoice</button></a>

            <a href="/{{Auth::user()->user_type}}/couriers"><button type="button" class="mb-xs mt-xs mr-xs btn btn-danger">Close</button></a>

            <button type="button" class="mb-xs mt-xs mr-xs btn btn-warning" onclick="printDiv('printableArea')"><i class="fa fa-print"></i> Print</button>
        </div>
    </section>


    <section class="panel"  id="printableArea">

        @foreach($cb_boxes as  $key => $box)

            <div class="row">

            <div class="pricing-table">
                <div class="col-lg-8">
                    <div class="plan">
                        <h3>

                            <table width="100%" >
                                <tr>
                                    <td style="float: left" >AWB NO: {{$courier->unique_name}}</td>



                                    <td style="float: right; ">PCS: {{$box['box_pics']}}</td>
                                    <td style="float: right; margin-right: 10px;" >Box: {{$key+1}}</td>
                                </tr>
                            </table>
                        </h3>


                        <div class="row">
                            <div class="col-md-12">
                                <table class="" border="0" width="100%">

                                    <tbody>
                                    <tr>

                                        <th>TO :</th>

                                        <td style="float: left;">{{$courier->s_name}}</td>

                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>

                                    <tr>

                                        <th>ADDRESS :</th>

                                        <td style="float: left;">{{$courier->s_address1}}, {{$courier->s_city}},{{$courier->s_state}}</td>

                                    </tr>
                                    <tr><td>&nbsp;</td></tr>


                                    <tr>
                                        <th>COUNTRY :</th>

                                        <td style="float: left;"><b>{{$courier->sender_country->name}}</b></td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>

                                    <tr>
                                        <th>PHONE :</th>

                                        <td style="float: left;">{{$courier->s_phone}}</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>

                                    <tr style="border-top: 1px solid #eee;">
                                        <th>FROM :</th>

                                        <td style="float: left;">{{$courier->r_name}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>



                    </div>


                </div>



            </div>

        </div>
        @endforeach


    </section>

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
