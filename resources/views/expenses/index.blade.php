@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Expenses</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
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
        <header class="panel-heading">

                <a href="{{url(Auth::user()->user_type.'/expenses/create')}}" class="btn btn-primary pull-right">Create Expense</a>
                <h2 class="panel-title">Manage Expenses</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
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
                @foreach($expenses as $key=> $expense)

                <tr>
                    <td data-title="Party Name">{{$expense->party_name}}</td>
                    <td data-title="Amount" class="hidden-xs hidden-sm">{{$expense->amount}}</td>
                    <td data-title="Expense Type" class="text-right">{{$expense->expense_type->name}}</td>
                    <td data-title="Expense Date" class="text-right hidden-xs hidden-sm">{{date('d-M-Y',strtotime($expense->expense_date))}}</td>
                    <td data-title="Payment By" class="text-right">{{$expense->payment_by}}</td>
                    <td data-title="Receiver Name" class="text-right">{{$expense->receiver_name}}</td>
                    <td data-title="Actions" class="text-right actions">


                        {!! Form::model($expense,['method' => 'DELETE', 'action' => ['ExpenseController@destroy', $expense->id ], 'id'=>'frmdeleteexpense_'.$expense->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteExpense('{{$expense->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}

                        <a href="{{url(Auth::user()->user_type.'/expenses/'.$expense->id.'/edit')}}" class=""><i class="fa fa-pencil"></i></a>

                    </td>
                </tr>
                @endforeach
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

        function updateStatus(obj,id){

            var status_id = obj.value;
            var courier_id = id;

            axios.post('/api/update_courier_status', {
                status_id: status_id,
                courier_id: courier_id
            })
                .then(function (response) {
                    //currentObj.output = response.data;
                })
                .catch(function (error) {
                    //currentObj.output = error;
                });
        }
        jQuery(document).ready(function($) {

        });



    </script>

@endsection
