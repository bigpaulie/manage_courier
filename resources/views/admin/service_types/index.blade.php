@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Service Types</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Service Types</span></li>
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

                <a href="{{route('service_types.create')}}" class="btn btn-primary pull-right">Create Service Type</a>
                <h2 class="panel-title">Manage Service Types</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Service Type</th>
                    <th class="hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($service_types as $key=> $service_type)

                <tr>
                    <td data-title="Id">{{$service_type->id}}</td>
                    <td data-title="Expense Type" class="hidden-xs hidden-sm">{{$service_type->name}}</td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($service_type->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($service_type,['method' => 'DELETE', 'action' => ['ServicetypeController@destroy', $service_type->id ], 'id'=>'frmdeletservicetype_'.$service_type->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteServiceType('{{$service_type->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('service_types.edit',$service_type->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteServiceType(service_type_id){

        var status= confirm('Are you sure want to delete this service type?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletservicetype_'+service_type_id).submit();
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
