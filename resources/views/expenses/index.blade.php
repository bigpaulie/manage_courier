@extends('layouts.admin')

@section('date-styles')

    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>
@endsection

@section('content')

    <header class="page-header">
        <h2>Manage Expenses</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Expenses</span></li>
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

                <a href="{{url(Auth::user()->user_type.'/expenses/create')}}" class="btn btn-primary pull-right">Create Expense</a>
                <h2 class="panel-title">Manage Expenses</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Party Name</th>
                    <th>Amount</th>
                    <th class="hidden-xs hidden-sm">Expense Type</th>
                    <th class="text-right">Expense Date</th>
                    <th class="text-right hidden-xs hidden-sm">Payment By</th>
                    <th class="text-right">Receiver Name</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>


                <tr v-for="(expense, index) in expenses.data">
                    <td data-title="Store Name">@{{expense.store.name}} (@{{expense.store.profile.company_name}})</td>
                    <td data-title="Party Name">@{{expense.party_name}}</td>
                    <td data-title="Amount" class="hidden-xs hidden-sm">@{{expense.amount}}</td>
                    <td data-title="Expense Type" class="text-right">@{{expense.expense_type.name}}</td>
                    <td data-title="Expense Date" class="text-right hidden-xs hidden-sm">@{{expense.expense_date}}</td>
                    <td data-title="Payment By" class="text-right">@{{expense.payment_by}}</td>
                    <td data-title="Receiver Name" class="text-right">@{{expense.receiver_name}}</td>
                    <td data-title="Actions" class="text-right actions">

                        {{--@if(Auth::user()->user_type == 'admin')--}}
                        {{--{!! Form::model($expense,['method' => 'DELETE', 'action' => ['ExpenseController@destroy', $expense->id ], 'id'=>'frmdeleteexpense_'.$expense->id ]) !!}--}}
                          {{--<button class="delete-row" type="button" onclick="deleteExpense('{{$expense->id}}')"><i class="fa fa-trash-o"></i></button>--}}
                        {{--{!! Form::close() !!}--}}
                        {{--@endif--}}

                            <a href="javascript:void(0);" @click="deleteExpense(expense.id)" class=""><i class="fa fa-trash-o"></i></a>


                            <a href="javascript:void(0);" @click="editExpense(expense.id)"><i class="fa fa-pencil"></i></a>

                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        {{--<div class="pull-right">{{ $expenses->links() }}</div>--}}
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>
        function deleteExpense(expense_id){

        var status= confirm('Are you sure want to delete this expense?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeleteexpense_'+expense_id).submit();
             return true;
         }else{
             return false;
         }


        }

        jQuery(document).ready(function($) {

            $("#userSelect").select2({
                placeholder: "Search Store Name",
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
                expenses:{},

                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",

            },
            created(){

                let searchURL = '/api/getexpenses?type=all';
                axios.get(searchURL).then(response => {
                    this.expenses = response.data.expense_data;

            });
            },


            methods: {


                searchPayments(page=1){

                    var user_id = $("#userSelect").val();
                    var page_no =1;

                    let searchURL = '/api/getexpenses?page='+page_no+'&user_id='+user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date

                    axios.get(searchURL).then(response => {
                        this.expenses = response.data.expense_data;

                });

                },

                editExpense(id){

                    window.location.href ="/admin/expenses/"+id+"/edit";
                },

                getPaymentType:function(type){

                    return type;
                },

                deleteExpense(id){

                    var status= confirm('Are you sure want to delete this expense?');
                    if(status == true){

                        window.location.href ="/admin/expenses/delete/"+id;

                    }else{
                        return false;
                    }

                },




            },

            computed: {


            },




        });



    </script>

@endsection
