@extends('layouts.admin')
@section('date-styles')
    {!! Html::style("/assets/vendor/bootstrap-datepicker/css/datepicker3.css") !!}
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection

@section('content')

    <header class="page-header">
        <h2>Expense</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
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
    {!! Form::model($expense,['method' => 'PATCH', 'action' => ['ExpenseController@update', $expense->id ] ]) !!}
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

                                <div class="form-group @if ($errors->has('expense_of')) has-error @endif">
                                    <label class="col-sm-4 control-label">Expense : </label>
                                    <div class="col-sm-8">
                                        <label class="checkbox-inline">
                                            <input type="radio" id="inlineCheckbox1" name="expense_of" value="office" v-model="expense_of"> Office
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio" id="inlineCheckbox2" name="expense_of" value="vendor" v-model="expense_of"> Vendor
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('expense_type_id')) has-error @endif" v-show="expense_of == 'office'">
                                    <label class="col-sm-4 control-label">Expense Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('expense_type_id', $expense_types,$expense->expense_type_id, ['class'=>'form-control','placeholder' => 'Select Expense Type']); !!}
                                        @if ($errors->has('expense_type_id'))
                                            <label for="expense_type_id" class="error">{{ $errors->first('expense_type_id') }}</label>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group @if ($errors->has('vendor_id')) has-error @endif" v-show="expense_of == 'vendor'">
                                    <label class="col-sm-4 control-label">Vendor: </label>
                                    <div class="col-sm-8">
                                        <select  class="form-control populate" id="selectVendor" name="vendor_id">
                                            @if($expense->vendor != null)
                                            <option value="{{$expense->vendor_id}}">{{$expense->vendor->name}}</option>
                                            @endif

                                        </select>
                                        @if ($errors->has('vendor_id'))
                                            <label for="vendor_id" class="error">{{ $errors->first('vendor_id') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group @if ($errors->has('party_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Party Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="party_name" value="{{$expense->party_name}}">
                                        @if ($errors->has('party_name'))
                                            <label for="party_name" class="error">{{ $errors->first('party_name') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('payment_by')) has-error @endif">
                                    <label class="col-sm-4 control-label">Payment By: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('payment_by', $payment_types, $expense->payment_by, ['class'=>'form-control mb-md','placeholder' => 'Select Payment by','required','v-model'=>'payment_type','@change'=>'showPaymentDetails']); !!}
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
                                        <input type="text" data-plugin-datepicker  name="expense_date" class="form-control" value="{{$expense->expense_date}}">
                                        @if ($errors->has('expense_date'))
                                            <label for="expense_date" class="error">{{ $errors->first('expense_date') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Debited From: </label>
                                    <div class="col-sm-8">
                                        <input type="text" name="debited_from" class="form-control"  value="{{$expense->debited_from}}" placeholder="Bank Name">
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
                                        <input type="text" class="form-control" name="receiver_cash_name"
                                              @if($expense->payment_by == 'cash')
                                               value="{{$expense->receiver_name}}"
                                               @endif
                                        >

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
                                        <input type="number" name="cash_amount" class="form-control"
                                               @if($expense->payment_by == 'cash')
                                                    value="{{$expense->amount}}"
                                                    @endif
                                               >
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
                                        <input type="text" class="form-control" name="cheque_no"
                                               @if($expense->payment_by == 'cheque')
                                                    value="{{$expense->cheque_no}}"
                                                @endif
                                        >
                                        @if ($errors->has('cheque_no'))
                                            <label for="cheque_no" class="error">{{ $errors->first('cheque_no') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('cheque_amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Amount: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="cheque_amount"
                                               @if($expense->payment_by == 'cheque')
                                                     value="{{$expense->amount}}"
                                                @endif
                                        >
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
                                        <input type="text" data-plugin-datepicker name="cheque_date" class="form-control"
                                               @if($expense->payment_by == 'cheque')
                                                    value="{{$expense->cheque_date}}"
                                                @endif
                                                >
                                        @if ($errors->has('cheque_date'))
                                            <label for="cheque_date" class="error">{{ $errors->first('cheque_date') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('cheque_name')) has-error @endif">
                                    <label class="col-sm-4 control-label">Cheque Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="cheque_name" class="form-control"
                                               @if($expense->payment_by == 'cheque')
                                                    value="{{$expense->receiver_name}}"
                                                @endif
                                        >
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
                                        <input type="text" class="form-control" name="transaction_id"

                                               @if($expense->payment_by == 'net_banking')
                                                    value="{{$expense->transaction_id}}"
                                               @endif
                                              >
                                        @if ($errors->has('transaction_id'))
                                            <label for="transaction_id" class="error">{{ $errors->first('transaction_id') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('net_banking_amount')) has-error @endif">
                                    <label class="col-sm-4 control-label">Amount: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="net_banking_amount"

                                               @if($expense->payment_by == 'net_banking')
                                               value="{{$expense->amount}}"
                                               @endif
                                              >
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
                                        <input type="text" name="net_banking_name" class="form-control"

                                               @if($expense->payment_by == 'net_banking')
                                                      value="{{$expense->receiver_name}}"
                                                @endif
                                        >
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
                                        <textarea name="description" rows="5" cols="100">{{$expense->description}}</textarea>
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

            var apiUrl = "/api/get_vendors";
            $("#selectVendor").select2({
                placeholder: "Select a Vendor",
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


        });

        const oapp = new Vue({
            el:'#app',
            data:{
                cash_details:true,
                net_banking:false,
                cheque_details:false,
                payment_type:"{{$expense->payment_by}}",
                expense_of:"{{$expense->expense_of}}",

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
