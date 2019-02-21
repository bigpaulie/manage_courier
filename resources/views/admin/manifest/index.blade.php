@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Manifest</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Manifest</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>


    <section class="panel">
        <header class="panel-heading">

            <a href="{{url(Auth::user()->user_type.'/manifest/create')}}" class="btn btn-primary pull-right">Create Manifest</a>
            <a href="{{url(Auth::user()->user_type.'/manifest/download')}}" class="btn btn-warning pull-right" style="margin-right: 10px;"><i class="fa fa-download"></i> Export</a>

            <h2 class="panel-title">Manage Manifest</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th>Id</th>
                    @if(Auth::user()->user_type == 'admin')
                         <th>Created By</th>
                    @endif
                    <th>Vendor Name</th>
                    <th>Amount</th>
                    <th>Manifest Contents</th>
                    <th>Created</th>
                    <th>Action</th>


                </tr>
                </thead>
                <tbody>
                 @foreach($manifests as $manifest)
                     <tr>
                         <td><a href="\{{Auth::user()->user_type}}\manifest\{{$manifest->id}}">{{$manifest->unique_name}}</a></td>
                         @if(Auth::user()->user_type == 'admin')
                         <td>{{$manifest->store->name}} ({{$manifest->store->profile->company_name}})</td>
                         @endif
                         <td>{{$manifest->vendor->name}}</td>
                         <td>{{$manifest->amount}}</td>
                         <td>
                             <ul>
                                 <li>
                                     Items-<strong>{{$manifest->manifest_items->where('item_type','item')->count()}}</strong>
                                 </li>
                                 <li>
                                     Bluk-<strong>{{$manifest->manifest_items->where('item_type','bulk')->count()}}</strong>
                                 </li>
                             </ul>

                         </td>
                         <td data-title="Created">{{date('d-M-Y',strtotime($manifest->created_at))}}</td>

                         <td>
                             <a href="\{{Auth::user()->user_type}}\manifest\excel_report\{{$manifest->id}}" style="margin-right: 10px;"><i class="fa fa-file-excel-o"></i></a>
                             <a href="\{{Auth::user()->user_type}}\manifest\print\{{$manifest->id}}"><i class="fa fa-print"></i></a>
                         </td>
                     </tr>

                 @endforeach

                </tbody>
            </table>
        </div>



    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {

        });



    </script>

@endsection
