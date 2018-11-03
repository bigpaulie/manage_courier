@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Payments</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
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
                @foreach($payments as $key=> $payment)

                <tr>
                    <td data-title="Agent Name">{{$payment->agent->name}}</td>
                    <td data-title="Amount" class="hidden-xs hidden-sm">{{$payment->amount}}</td>
                    <td data-title="TDS" class="text-right">{{$payment->tds}}</td>
                    <td data-title="Payment Date" class="text-right hidden-xs hidden-sm">{{date('d-M-Y',strtotime($payment->payment_date))}}</td>
                    <td data-title="Payment By" class="text-right">{{$payment_types[$payment->payment_by]}}</td>
                    <td data-title="Receiver Name" class="text-right">{{$payment->reciver_name}}</td>
                    <td data-title="Actions" class="text-right actions">


                        {!! Form::model($payment,['method' => 'DELETE', 'action' => ['PaymentController@destroy', $payment->id ], 'id'=>'frmdeletepayment_'.$payment->id ]) !!}
                          <button class="delete-row" type="button" onclick="deletePayment('{{$payment->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}


                        <a href="{{route('payments.edit',$payment->id)}}" class=""><i class="fa fa-pencil"></i></a>

                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="pull-right">{{ $payments->links() }}</div>
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>
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
