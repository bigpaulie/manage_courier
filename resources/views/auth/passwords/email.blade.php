
@extends('layouts.front')

@section('content')

    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo pull-left">
                <img src="/assets/images/logo.png" height="54" alt="Porto Admin" />
            </a>

            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Recover Password</h2>
                </div>
                <div class="panel-body">
                    {{--<div class="alert alert-info">--}}
                        {{--<p class="m-none text-semibold h6">Enter your e-mail below and we will send you reset instructions!</p>--}}
                    {{--</div>--}}

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group mb-none">
                            <div class="input-group">
                                    <input name="email" type="email" placeholder="E-mail" class="form-control input-lg" />
                                   <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary btn-lg" type="submit">Reset!</button>
                                    </span>


                            </div>

                            @if ($errors->has('email'))
                                <label class="error" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </label>
                            @endif
                        </div>

                        <p class="text-center mt-lg"><a href="{{ route('login') }}">Sign In!</a>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-md mb-md">&copy; Copyright {{date('Y')}}. All Rights Reserved.</p>
        </div>
    </section>    <!-- end: page -->

@endsection

