@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Company</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Company </span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <form id="frmcourier" action="{{route('companies.store')}}" class="form-horizontal form-bordered" method="POST">
        {{csrf_field()}}

        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Company</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group @if ($errors->has('name')) has-error  @endif">
                                    <label class="col-sm-4 control-label">Name: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">

                                        @if ($errors->has('name'))
                                            <label for="name" class="error">{{ $errors->first('name') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('country_id')) has-error  @endif">
                                    <label class="col-sm-4 control-label">Country: </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="country_id">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $con)
                                                <option value="{{$con->id}}">{{$con->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country_id'))
                                            <label for="country_id" class="error">{{ $errors->first('country_id') }}</label>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </section>

            </div>

        </div>


        <footer class="panel-footer ">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>

        </footer>
    </form>


    <!-- end: page -->

@endsection

@section('scripts')

    <script type="text/javascript">

        jQuery(document).ready(function($) {

        });



    </script>

@endsection
