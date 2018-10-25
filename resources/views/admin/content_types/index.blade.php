@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Content Types</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Content Types</span></li>
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

                <a href="{{route('content_types.create')}}" class="btn btn-primary pull-right">Create Content Type</a>
                <h2 class="panel-title">Manage Content Types</h2>
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
                @foreach($content_types as $key=> $content_type)

                <tr>
                    <td data-title="Id">{{$content_type->id}}</td>
                    <td data-title="Expense Type" class="hidden-xs hidden-sm">{{$content_type->name}}</td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($content_type->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($content_type,['method' => 'DELETE', 'action' => ['ContenttypeController@destroy', $content_type->id ], 'id'=>'frmdeletcontenttype_'.$content_type->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteContentType('{{$content_type->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('content_types.edit',$content_type->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteContentType(content_type_id){

        var status= confirm('Are you sure want to delete this content type?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletcontenttype_'+content_type_id).submit();
             return true;
         }else{
             return false;
         }


        }


        jQuery(document).ready(function($) {

        });



    </script>

@endsection
