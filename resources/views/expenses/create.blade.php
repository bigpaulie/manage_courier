@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Expense</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Expenses</span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <form id="frmcourier" action="{{route('expenses.store')}}" class="form-horizontal form-bordered" method="POST">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Expense Details</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Expense Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('expense_type_id', $expense_types, old('expense_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Expense Type','required']); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Service Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('service_type_id', $service_types, old('service_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Service Type','required']); !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('payment_type', $payment_types, old('content_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Content Type','required']); !!}

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Weight: </label>
                                    <div class="col-sm-8">
                                        <input type="number" name="weight" class="form-control" required value="{{old('weight')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Carriage Value: </label>
                                    <div class="col-sm-8">
                                        <input type="number" name="carriage_value" class="form-control" required value="{{old('carriage_value')}}">
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
