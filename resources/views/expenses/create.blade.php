@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Expense</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Expenses</span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <form id="frmcourier" action="{{route('expenses.store')}}" class="form-horizontal form-bordered" method="POST">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Expense Details</h2>


                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('expense_type_id')) has-error @endif">
                                    <label class="col-sm-4 control-label">Expense Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('expense_type_id', $expense_types, old('expense_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Expense Type']); !!}
                                        @if ($errors->has('expense_type_id'))
                                            <label for="expense_type_id" class="error">{{ $errors->first('expense_type_id') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('party_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Party Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="party_name" value="{{old('party_name')}}">

                                        @if ($errors->has('party_name'))
                                            <label for="party_name" class="error">{{ $errors->first('party_name') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('payment_by')) has-error @endif">
                                    <label class="col-sm-4 control-label">Payment By: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('payment_by', $payment_types, 'cash', ['class'=>'form-control mb-md','placeholder' => 'Select Payment by','v-model'=>'payment_type','@change'=>'showPaymentDetails']); !!}
                                        @if ($errors->has('payment_by'))
                                            <label for="payment_by" class="error">{{ $errors->first('payment_by') }}</label>
                                        @endif

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('expense_date')) has-error @endif">
                                    <label class="col-sm-4 control-label">Expense Date: </label>
                                    <div class="col-sm-8">
                                        <input type="text" data-plugin-datepicker  name="expense_date" class="form-control" value="{{date('m/d/Y')}}">

                                        @if ($errors->has('expense_date'))
                                            <label for="expense_date" class="error">{{ $errors->first('expense_date') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Debited From: </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="debited_from" class="form-control"  value="{{old('debited_from')}}" placeholder="Bank Name">
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

                        <h2 class="panel-title">Payment Details</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row"  v-show="cash_details">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('receiver_cash_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="receiver_cash_name" value="{{old('receiver_cash_name')}}">
                                        @if ($errors->has('receiver_cash_name'))
                                            <label for="expense_date" class="error">Receiver Name field is required.</label>
                                        @endif
                                    </div>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('cash_amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Amount: </label>
                                    <div class="col-sm-8">
                                        <input type="number" name="cash_amount" class="form-control"  value="{{old('cash_amount')}}">
                                        @if ($errors->has('cash_amount'))
                                            <label for="cash_amount" class="error">Amount Name field is required.</label>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row"  v-show="cheque_details">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('cheque_no')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque No: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="cheque_no" value="{{old('cheque_no')}}">
                                        @if ($errors->has('cheque_no'))
                                            <label for="cheque_no" class="error">{{ $errors->first('cheque_no') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('cheque_amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Amount: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="cheque_amount" value="{{old('cheque_amount')}}">

                                        @if ($errors->has('cheque_amount'))
                                            <label for="cheque_amount" class="error">Amount Name field is required.</label>
                                        @endif
                                    </div>
                                </div>



                            </div>

                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('cheque_date')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque Date: </label>
                                    <div class="col-sm-8">
                                        <input type="text" data-plugin-datepicker name="cheque_date" class="form-control" value="{{date('m/d/Y')}}" >
                                        @if ($errors->has('cheque_date'))
                                            <label for="cheque_date" class="error">{{ $errors->first('cheque_date') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('cheque_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="cheque_name" class="form-control" value="{{old('cheque_name')}}" >

                                        @if ($errors->has('cheque_name'))
                                            <label for="cheque_name" class="error">{{ $errors->first('cheque_name') }}</label>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row"  v-show="net_banking">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('transaction_id')) has-error @endif">
                                    <label class="col-sm-4 control-label">Transaction ID: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="transaction_id" value="{{old('transaction_id')}}">

                                        @if ($errors->has('transaction_id'))
                                            <label for="transaction_id" class="error">{{ $errors->first('transaction_id') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('net_banking_amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Amount: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="net_banking_amount" value="{{old('net_banking_amount')}}">
                                        @if ($errors->has('net_banking_amount'))
                                            <label for="net_banking_amount" class="error">Amount Name field is required.</label>
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
                                        <textarea name="description" rows="5" cols="100">{{old('description')}}</textarea>
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

        jQuery(document).ready(function($) {

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

            },
            created(){
                if(this.payment_type == 'cash'){
                    this.cash_details =true;
                    this.net_banking =false;
                    this.cheque_details =false;
                }else if(this.payment_type == 'cheque'){
                    this.cash_details =false;
                    this.net_banking =false;
                    this.cheque_details =true;
                }else if(this.payment_type == 'net_banking'){
                    this.cash_details =false;
                    this.net_banking =true;
                    this.cheque_details =false;
                }
            },


            methods: {

                showPaymentDetails(){

                     if(this.payment_type == 'cash'){
                        this.cash_details =true;
                        this.net_banking =false;
                        this.cheque_details =false;
                     }else if(this.payment_type == 'cheque'){
                         this.cash_details =false;
                         this.net_banking =false;
                         this.cheque_details =true;
                     }else if(this.payment_type == 'net_banking'){
                         this.cash_details =false;
                         this.net_banking =true;
                         this.cheque_details =false;
                     }
                },

            },

            computed: {

            }

        });


    </script>

@endsection
