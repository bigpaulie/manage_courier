@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Vendors</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Vendors</span></li>
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

                <a href="{{route('vendors.create')}}" class="btn btn-primary pull-right">Create Vendor</a>
                <h2 class="panel-title">Manage Vendors</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th class="hidden-xs hidden-sm">Created</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vendors as $key=> $vendor)

                <tr>
                    <td data-title="Id">{{$vendor->id}}</td>
                    <td data-title="Expense Type" class="hidden-xs hidden-sm">{{$vendor->name}}</td>
                    <td data-title="Created" class="text-right">{{date('d-M-Y',strtotime($vendor->created_at))}}</td>
                    <td data-title="Actions" class="text-right actions">
                        {!! Form::model($vendor,['method' => 'DELETE', 'action' => ['VendorController@destroy', $vendor->id ], 'id'=>'frmdeletevendor_'.$vendor->id ]) !!}
                          <button class="delete-row" type="button" onclick="deleteVendor('{{$vendor->id}}')"><i class="fa fa-trash-o"></i></button>
                        {!! Form::close() !!}
                        <a href="{{route('vendors.edit',$vendor->id)}}" class=""><i class="fa fa-pencil"></i></a>
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
        function deleteVendor(vendor_id){

        var status= confirm('Are you sure want to delete this vendor?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletevendor_'+vendor_id).submit();
             return true;
         }else{
             return false;
         }


        }


        jQuery(document).ready(function($) {

        });



    </script>

@endsection
