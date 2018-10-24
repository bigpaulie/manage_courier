@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Change Password</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>{{ucfirst(Auth::user()->user_type)}}</span></li>
                <li><span>Change Password</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    @if (Session::has('message'))
        <div class="alert alert-success">
            <strong> {{ Session::get('message') }}</strong>
        </div>
    @endif

    <!-- start: page -->
        {!! Form::open(['url' => 'admin/update_password/'.$user->id, 'method' => 'put','class'=>'form-horizontal form-bordered']) !!}
        {{csrf_field()}}

        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Change Password</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group @if ($errors->has('password')) has-error  @endif">
                                    <label class="col-sm-4 control-label">New Password: </label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control" id="password" name="password" >
                                        @if ($errors->has('password'))
                                            <label for="password" class="error">{{ $errors->first('password') }}</label>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('password_confirmation')) has-error  @endif">
                                    <label class="col-sm-4 control-label">Confirm Password: </label>
                                    <div class="col-sm-4">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                                        @if ($errors->has('password_confirmation'))
                                            <label for="password_confirmation" class="error">{{ $errors->first('password_confirmation') }}</label>
                                        @endif
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>

                </section>

            </div>

        </div>

        <footer class="panel-footer center">
            <button class="btn btn-primary">Submit</button>
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
