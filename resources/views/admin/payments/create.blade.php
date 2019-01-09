@extends('layouts.admin')
@section('date-styles')
    {!! Html::style("/assets/vendor/bootstrap-datepicker/css/datepicker3.css") !!}
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection


@section('content')

    <header class="page-header">
        <h2>Payment</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Payment</span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <form id="frmcourier" action="{{route('payments.store')}}" class="form-horizontal form-bordered" method="POST">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Payment Details</h2>


                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment Type:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">

                                        <label class="checkbox-inline">
                                            <input type="radio" name="payment_user_type" value="agent_store" v-model="payment_user_type"> Agent/Store
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" name="payment_user_type" value="walking_customer" v-model="payment_user_type"> Walking Customer
                                        </label>

                                        @if ($errors->has('payment_user_type'))
                                            <label for="payment_user_type" class="error">{{ $errors->first('payment_user_type') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('user_id')) has-error @endif" v-show="payment_user_type == 'agent_store'">
                                    <label class="col-sm-4 control-label">Agent Name:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">

                                        <select  class="form-control populate" id="agentSelect" name="user_id">

                                        </select>
                                        @if ($errors->has('user_id'))
                                            <label for="user_id" class="error">{{ $errors->first('user_id') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('customer_phone')) has-error @endif" v-show="payment_user_type == 'walking_customer'">
                                    <label class="col-sm-4 control-label">Customer Name:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">

                                        <select  class="form-control populate" id="customerPhone" name="customer_phone">

                                        </select>
                                        @if ($errors->has('customer_phone'))
                                            <label for="customer_phone" class="error">{{ $errors->first('customer_phone') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Payment Amount:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="amount" value="{{old('amount')}}">

                                        @if ($errors->has('amount'))
                                            <label for="amount" class="error">{{ $errors->first('amount') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('payment_by')) has-error @endif">
                                    <label class="col-sm-4 control-label">Payment By:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('payment_by', $payment_types, 'cash', ['class'=>'form-control mb-md','placeholder' => 'Select Payment by','v-model'=>'payment_type','@change'=>'showPaymentDetails']); !!}
                                        @if ($errors->has('payment_by'))
                                            <label for="payment_by" class="error">{{ $errors->first('payment_by') }}</label>
                                        @endif

                                    </div>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('payment_date')) has-error @endif">
                                    <label class="col-sm-4 control-label">Payment Date:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" data-plugin-datepicker  name="payment_date" class="form-control" value="{{date('m/d/Y')}}">

                                        @if ($errors->has('payment_date'))
                                            <label for="payment_date" class="error">{{ $errors->first('payment_date') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" v-if="payment_user_type == 'walking_customer'">
                                    <label class="col-sm-4 control-label">Discount: </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="discount" class="form-control"  value="{{old('discount')}}" placeholder="">
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('bank_id')) has-error @endif">
                                    <label class="col-sm-4 control-label">Deposited in Bank: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('bank_id', $banks, 'cash', ['class'=>'form-control mb-md','placeholder' => 'Select Bank Name']); !!}
                                        @if ($errors->has('bank_id'))
                                            <label for="payment_by" class="error">{{ $errors->first('bank_id') }}</label>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </section>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">@{{payment_name}} Details</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row"  v-show="cash_details">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('receiver_cash_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Name:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="receiver_cash_name" value="{{old('receiver_cash_name')}}">
                                        @if ($errors->has('receiver_cash_name'))
                                            <label for="receiver_cash_name" class="error">Receiver Name field is required.</label>
                                        @endif
                                    </div>
                                </div>



                            </div>


                        </div>

                        <div class="row"  v-show="cheque_details">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('cheque_no')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque No:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="cheque_no" value="{{old('cheque_no')}}">
                                        @if ($errors->has('cheque_no'))
                                            <label for="cheque_no" class="error">{{ $errors->first('cheque_no') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('cheque_bank_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Bank Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="cheque_bank_name" value="{{old('cheque_bank_name')}}">

                                        @if ($errors->has('cheque_bank_name'))
                                            <label for="cheque_bank_name" class="error">Amount Name field is required.</label>
                                        @endif
                                    </div>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('cheque_date')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque Date:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" data-plugin-datepicker name="cheque_date" class="form-control" value="{{date('m/d/Y')}}" >
                                        @if ($errors->has('cheque_date'))
                                            <label for="cheque_date" class="error">{{ $errors->first('cheque_date') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('reference_no')) has-error @endif">
                                    <label class="col-sm-4 control-label">Reference No#: </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="reference_no" class="form-control" value="{{old('reference_no')}}" >

                                        @if ($errors->has('reference_no'))
                                            <label for="reference_no" class="error">{{ $errors->first('reference_no') }}</label>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row"  v-show="net_banking">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('transaction_id')) has-error @endif">
                                    <label class="col-sm-4 control-label">Transaction ID:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="transaction_id" value="{{old('transaction_id')}}">

                                        @if ($errors->has('transaction_id'))
                                            <label for="transaction_id" class="error">{{ $errors->first('transaction_id') }}</label>
                                        @endif
                                    </div>
                                </div>


                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('net_banking_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="net_banking_name" class="form-control"  value="{{old('net_banking_name')}}">

                                        @if ($errors->has('net_banking_name'))
                                            <label for="net_banking_name" class="error">Receiver  Name field is required.</label>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </section>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Remarks:</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Remarks: </label>
                                    <div class="col-sm-8">
                                        <textarea name="remark" rows="5" cols="100">{{old('remark')}}</textarea>
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>

                </section>

            </div>

        </div>


        <footer class="panel-footer center">
            <button class="btn btn-primary">Submit</button>
        </footer>
    </form>


    <!-- end: page -->

@endsection

@section('scripts')

    <script type="text/javascript">

        var user_type = "{{$user_type}}";
        var logged_user_id = "{{$logged_user_id}}";

        $(document).ready(function($) {

            if(user_type == 'admin'){
              var apiUrl = "/api/get_user_name";
            }else if(user_type == 'store'){
                var apiUrl = "/api/get_store_agent?user_store_id="+logged_user_id;
            }

            $("#agentSelect").select2({
                placeholder: "Select a Agent Name",
                allowClear: true,
                width: '100%',
                minimumInputLength:2,
                ajax: {
                    url: apiUrl,
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

            var customerUrl = "/api/get_walking_customer?user_store_id="+logged_user_id;

            $("#customerPhone").select2({
                placeholder: "Select Customer Name",
                allowClear: true,
                minimumInputLength:2,
                width: '100%',
                ajax: {
                    url: customerUrl,
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


        var old_payment_by = "{{old('payment_by')}}";
        var paymentby ='cash';
          if(old_payment_by !=""){
              paymentby = "{{old('payment_by')}}";
          }
        const oapp = new Vue({
            el:'#app',
            data:{
                cash_details:true,
                net_banking:false,
                cheque_details:false,
                payment_type:paymentby,
                payment_name:"Cash",
                payment_user_type:"{{old('payment_user_type','agent_store')}}",

            },
            created(){
                if(this.payment_type == 'cash'){
                    this.cash_details =true;
                    this.net_banking =false;
                    this.cheque_details =false;
                    this.payment_name="Cash";
                }else if(this.payment_type == 'cheque'){
                    this.cash_details =false;
                    this.net_banking =false;
                    this.cheque_details =true;
                    this.payment_name="Cheque";
                }else if(this.payment_type == 'net_banking'){
                    this.cash_details =false;
                    this.net_banking =true;
                    this.cheque_details =false;
                    this.payment_name="Net Banking";
                }
            },




            methods: {

                showPaymentDetails(){

                     if(this.payment_type == 'cash'){
                        this.cash_details =true;
                        this.net_banking =false;
                        this.cheque_details =false;
                         this.payment_name="Cash";
                     }else if(this.payment_type == 'cheque'){
                         this.cash_details =false;
                         this.net_banking =false;
                         this.cheque_details =true;
                         this.payment_name="Cheque";
                     }else if(this.payment_type == 'net_banking'){
                         this.cash_details =false;
                         this.net_banking =true;
                         this.cheque_details =false;
                         this.payment_name="Net Banking";
                     }
                },

            },

            computed: {

            }

        });


    </script>

@endsection
