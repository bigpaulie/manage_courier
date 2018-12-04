@extends('layouts.admin')

@section('date-styles')

    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>
@endsection

@section('content')

    <header class="page-header">
        <h2>Manage Payments</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Payments</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    @if (Session::has('message'))
    <div class="alert alert-success">
       <strong> {{ Session::get('message') }}</strong>
    </div>
    @endif

    <section class="panel">
        <div class="panel-body">
            <div class="row">


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <select  class="form-control populate" id="userSelect" name="user_id">

                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                            <date-picker v-model="from_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">End Date</label>
                        <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                            <date-picker v-model="end_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>

                        </div>
                    </div>
                </div>



                <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" @click="searchPayments"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="panel">
        <header class="panel-heading">

                <a href="{{route('payments.create')}}" class="btn btn-primary pull-right">Create Payment</a>
                <h2 class="panel-title">Manage Payments</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Agent Name</th>
                    <th>Amount</th>
                    <th class="hidden-xs hidden-sm">TDS</th>
                    <th class="text-right">Payment Date</th>
                    <th class="text-right hidden-xs hidden-sm">Payment By</th>
                    <th class="text-right">Receiver Name</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(payment, index) in payments.data">
                    <td data-title="Agent Name">@{{payment.agent.name}}</td>
                    <td data-title="Amount" class="hidden-xs hidden-sm">@{{payment.amount}}</td>
                    <td data-title="TDS" class="text-right">@{{payment.tds}}</td>
                    <td data-title="Payment Date" class="text-right hidden-xs hidden-sm">@{{payment.payment_date}}</td>
                    <td data-title="Payment By" class="text-right">@{{getPaymentType(payment.payment_by)}}</td>
                    <td data-title="Receiver Name" class="text-right">@{{payment.reciver_name}}</td>
                    <td data-title="Actions" class="text-right actions">


                        {{--{!! Form::model($payment,['method' => 'DELETE', 'action' => ['PaymentController@destroy', $payment->id ], 'id'=>'frmdeletepayment_'.$payment->id ]) !!}--}}
                          {{--<button class="delete-row" type="button" onclick="deletePayment('{{$payment->id}}')"><i class="fa fa-trash-o"></i></button>--}}
                        {{--{!! Form::close() !!}--}}


                        <a href="javascript:void(0);" @click="editPayment(payment.id)" class=""><i class="fa fa-pencil"></i></a>

                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        {{--<div class="pull-right">{{ $payments->links() }}</div>--}}
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {

            $("#userSelect").select2({
                placeholder: "Search User Name",
                allowClear: true,
                minimumInputLength:2,
                ajax: {
                    url: "/api/get_user_name",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

        });




        const oapp = new Vue({
            el:'#app',

            data:{
                payments:{},
                payment_types: @json($payment_types),
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",

            },
            created(){

                let searchURL = '/api/getpayments?type=all';
                    axios.get(searchURL).then(response => {
                        this.payments = response.data.payment_data;

                });
            },


            methods: {


                searchPayments(page=1){

                    var user_id = $("#userSelect").val();
                    var page_no =1;

                    let searchURL = '/api/getpayments?page='+page_no+'&user_id='+user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date

                    axios.get(searchURL).then(response => {
                        this.payments = response.data.payment_data;

                });

                },

                editPayment(id){

                    window.location.href ="/admin/payments/"+id+"/edit";
                },

                getPaymentType:function(type){

                    return this.payment_types[type];
                },




            },

            computed: {


            },




        });



        function deletePayment(payment_id){

        var status= confirm('Are you sure want to delete this payment?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletepayment_'+payment_id).submit();
             return true;
         }else{
             return false;
         }


        }

    </script>

@endsection
