@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Couriers</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Couriers</span></li>
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

                <a href="{{url(Auth::user()->user_type.'/couriers/create')}}" class="btn btn-primary pull-right">Create Courier</a>
                <h2 class="panel-title">Manage Couriers</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Agent Name</th>
                    <th>S Company Name</th>
                    <th class="hidden-xs hidden-sm">S Name</th>
                    <th class="text-right">R Company Name</th>
                    <th class="text-right hidden-xs hidden-sm">R Name</th>
                    <th class="text-right">Traking Number</th>
                    <th class="text-right">Status</th>
                    <th class="text-right hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($couriers as $key=> $courier)

                <tr>
                    <td data-title="Agent Name">{{$courier->agent->name}}</td>
                    <td data-title="S Company Name" class="hidden-xs hidden-sm">{{$courier->s_company}}</td>
                    <td data-title="S Name" class="text-right">{{$courier->s_name}}</td>
                    <td data-title="R Company Name" class="text-right hidden-xs hidden-sm">{{$courier->r_company}}</td>
                    <td data-title="R Name" class="text-right">{{$courier->r_name}}</td>
                    <td data-title="Traking Number" class="text-right">{{$courier->tracking_no}}</td>
                    <td data-title="Status" class="text-right hidden-xs hidden-sm">{!! Form::select('status_id', $status, $courier->status_id, ['class'=>'form-control','onchange'=>'updateStatus(this,'.$courier->id.')']); !!}</td>
                    <td data-title="Created" class="text-right hidden-xs hidden-sm">{{date('d-M-Y',strtotime($courier->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">

                        @if(Auth::user()->user_type == 'admin')
                        {!! Form::model($courier,['method' => 'DELETE', 'action' => ['CourierController@destroy', $courier->id ], 'id'=>'frmdeletecourier_'.$courier->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteCourier('{{$courier->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        @endif

                        <a href="{{url(Auth::user()->user_type.'/couriers/'.$courier->id.'/edit')}}" class=""><i class="fa fa-pencil"></i></a>


                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="pull-right">{{ $couriers->links() }}</div>
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>
        function deleteCourier(courier_id){

        var status= confirm('Are you sure want to delete this courier?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletecourier_'+courier_id).submit();
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
