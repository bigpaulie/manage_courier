@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Stores</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Stores</span></li>
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

                <a href="{{route('stores.create')}}" class="btn btn-primary pull-right">Create Store</a>
                <h2 class="panel-title">Manage Stores</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Company Name</th>
                    <th class="hidden-xs hidden-sm">Name</th>
                    <th class="text-right">Email</th>
                    <th class="text-right hidden-xs hidden-sm">Phone</th>
                    <th class="text-right">Country</th>
                    <th class="text-right">Address</th>
                    <th class="text-right hidden-xs hidden-sm">Zip Code</th>
                    <th class="text-right hidden-xs hidden-sm">Created</th>
                    {{--<th class="text-right">Actions</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($stores as $key=> $store)

                <tr>
                    <td data-title="Company Name">{{$store->profile->company_name}}</td>
                    <td data-title="Name" class="hidden-xs hidden-sm">{{$store->profile->first_name}} {{$store->profile->last_name}}</td>
                    <td data-title="Email" class="text-right">{{$store->email}}</td>
                    <td data-title="Phone" class="text-right hidden-xs hidden-sm">{{$store->profile->phone}}</td>
                    <td data-title="Country" class="text-right">
                       @if($store->profile->country != null)
                        {{$store->profile->country->name}}
                       @endif
                    </td>
                    <td data-title="Address" class="text-right">{{$store->profile->address}}</td>
                    <td data-title="High" class="text-right hidden-xs hidden-sm">{{$store->profile->zip_code}}</td>
                    <td data-title="Created" class="text-right hidden-xs hidden-sm">{{date('d-M-Y',strtotime($store->created_at))}}</td>
                    {{--<td data-title="Actions" class="text-right actions">--}}
                        {{--{!! Form::model($store,['method' => 'DELETE', 'action' => ['StoreController@destroy', $store->id ], 'id'=>'frmdeletestore_'.$store->id ]) !!}--}}
                          {{--<button class="delete-row" type="button" onclick="deleteStore('{{$store->id}}')"><i class="fa fa-trash-o"></i></button>--}}
                        {{--{!! Form::close() !!}--}}
                        {{--<a href="{{route('stores.edit',$store->id)}}" class=""><i class="fa fa-pencil"></i></a>--}}

                    {{--</td>--}}
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="pull-right">{{ $stores->links() }}</div>
    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>
        function deleteStore(store_id){

        var status= confirm('Are you sure want to delete this store?');
         if(status == true){

             event.preventDefault();
             document.getElementById('frmdeletestore_'+store_id).submit();
             return true;
         }else{
             return false;
         }


        }
        jQuery(document).ready(function($) {

        });

    </script>

@endsection
