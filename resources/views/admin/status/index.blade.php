@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Status</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Status</span></li>
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

                <a href="{{route('status.create')}}" class="btn btn-primary pull-right">Create Status</a>
                <h2 class="panel-title">Manage Status</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Status</th>
                    <th class="hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($statuses as $key=> $status)

                <tr>
                    <td data-title="Id">{{$status->id}}</td>
                    <td data-title="Expense Type" class="hidden-xs hidden-sm"><span  style="color: {{$status->color_code}}">{{$status->name}}</span></td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($status->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($status,['method' => 'DELETE', 'action' => ['StatusController@destroy', $status->id ], 'id'=>'frmdeletestatus_'.$status->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteStatus('{{$status->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('status.edit',$status->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteStatus(status_id){

        var status= confirm('Are you sure want to delete this status?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletestatus_'+status_id).submit();
             return true;
         }else{
             return false;
         }


        }


        jQuery(document).ready(function($) {

        });



    </script>

@endsection
