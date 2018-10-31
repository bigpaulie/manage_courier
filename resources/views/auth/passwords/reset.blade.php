@extends('layouts.front')

@section('content')

    <!-- start: page -->
    <section class="body-sign">
        <div class="center-sign">
            <a href="/" class="logo pull-left">
                <img src="/assets/images/logo.png" height="54" alt="PACE" />
            </a>

            <div class="panel panel-sign">
                <div class="panel-title-sign mt-xl text-right">
                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Reset Password</h2>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-lg">
                            <label>E-Mail Address</label>
                            <div class="input-group input-group-icon">
                                <input id="email" type="email" class="form-control input-lg{{ $errors->has('email') ? ' has-error' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
                            </div>

                            @if ($errors->has('email'))
                                <label class="error" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </label>
                            @endif
                        </div>

                        <div class="form-group mb-lg">
                            <label>Password</label>
                            <div class="input-group input-group-icon">
                                <input id="password" type="password" class="form-control input-lg{{ $errors->has('password') ? ' has-error' : '' }}" name="password" required>
                                <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
                            </div>

                            @if ($errors->has('password'))
                                <label class="error" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </label>
                            @endif
                        </div>

                        <div class="form-group mb-lg">
                            <label>Confirm Password</label>
                            <div class="input-group input-group-icon">
                                <input id="password-confirm" type="password" class="form-control input-lg{{ $errors->has('password') ? ' has-error' : '' }}" name="password_confirmation" required>
                                <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">

                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="submit" class="btn btn-primary hidden-xs">Reset Password</button>
                                <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Reset Password</button>
                            </div>
                        </div>




                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-md mb-md">&copy; Copyright {{date('Y')}}. All Rights Reserved.</p>
        </div>
    </section>
    <!-- end: page -->

@endsection
