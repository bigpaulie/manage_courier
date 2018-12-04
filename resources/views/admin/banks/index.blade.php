@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Banks</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Banks</span></li>
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

                <a href="{{route('banks.create')}}" class="btn btn-primary pull-right">Create Bank</a>
                <h2 class="panel-title">Manage Banks</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Bank Name</th>
                    <th class="hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($banks as $key=> $bank)

                <tr>
                    <td data-title="Id">{{$bank->id}}</td>
                    <td data-title="Expense Type" class="hidden-xs hidden-sm">{{$bank->name}}</td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($bank->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($bank,['method' => 'DELETE', 'action' => ['BankController@destroy', $bank->id ], 'id'=>'frmdeletbank_'.$bank->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteBank('{{$bank->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('banks.edit',$bank->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteBank(bank_id){

        var status= confirm('Are you sure want to delete this bank?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletbank_'+bank_id).submit();
             return true;
         }else{
             return false;
         }


        }

    </script>

@endsection
