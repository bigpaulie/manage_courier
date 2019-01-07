@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Payment Details</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Couriers</span></li>
                <li><span>Payment Details</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>
    @if (Session::has('error_message'))
        <div class="alert alert-danger">
            <strong> {{ Session::get('error_message') }}</strong>
        </div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">


                    <h2 class="panel-title">Payment Details</h2>
                </header>
                <div class="panel-body">
                    {{Form::open(['url' => '/store/save_courier_payment/', 'method' => 'post'])}}
                    {{csrf_field()}}
                    <input type="hidden" name="courier_id" value="{{$courier->id}}">
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                        <div class="form-group  @if ($errors->has('total')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Total Amount<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="total" name="total" value="{{$courier_payment->total}}">
                                @if ($errors->has('total'))
                                    <label for="total" class="error">{{ $errors->first('total') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  @if ($errors->has('pay_amount')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Paid Amount<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="pay_amount" name="pay_amount" value="{{$courier_payment->pay_amount}}">
                                @if ($errors->has('pay_amount'))
                                    <label for="pay_amount" class="error">{{ $errors->first('pay_amount') }}</label>
                                @endif
                            </div>
                        </div>



                        <div class="form-group @if ($errors->has('remaining')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Remaining Amount</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="remaining" name="remaining" value="{{$courier_payment->remaining}}">
                                @if ($errors->has('remaining'))
                                    <label for="remaining" class="error">{{ $errors->first('remaining') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('discount')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Discount</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="discount" name="discount" value="{{$courier_payment->discount}}">
                                @if ($errors->has('discount'))
                                    <label for="discount" class="error">{{ $errors->first('discount') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('email')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Payment Date<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
                                    <input type="text" name="payment_date" data-plugin-datepicker="" class="form-control" value="{{date('m/d/Y',strtotime($courier_payment->payment_date))}}">
                                </div>
                            </div>
                        </div>


                        <br>

                        <footer class="panel-footer center">
                            <button class="btn btn-primary">Save</button>
                        </footer>
                    </form>

                </div>


            </section>


        </div>
    </div>


    <!-- end: page -->

@endsection

@section('scripts')


    <script type="text/javascript">

        jQuery(document).ready(function($) {

        });



    </script>


@endsection
