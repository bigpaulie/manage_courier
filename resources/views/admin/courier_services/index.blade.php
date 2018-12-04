@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Courier Services</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Courier Services</span></li>
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

                <a href="{{route('courier_services.create')}}" class="btn btn-primary pull-right">Create Courier Service</a>
                <h2 class="panel-title">Manage Courier Services</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Courier Company</th>
                    <th class="hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courier_services as $key=> $courier_service)

                <tr>
                    <td data-title="Id">{{$courier_service->id}}</td>
                    <td data-title="Courier Company" class="hidden-xs hidden-sm">{{$courier_service->name}}</td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($courier_service->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($courier_service,['method' => 'DELETE', 'action' => ['CourierServiceController@destroy', $courier_service->id ], 'id'=>'frmdeletcc_'.$courier_service->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteCourierCompany('{{$courier_service->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('courier_services.edit',$courier_service->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteCourierCompany(courier_company_id){

        var status= confirm('Are you sure want to delete this courier service?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletcc_'+courier_company_id).submit();
             return true;
         }else{
             return false;
         }


        }


        jQuery(document).ready(function($) {

        });



    </script>

@endsection
