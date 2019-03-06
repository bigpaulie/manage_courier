@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Menifest</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Menifest </span></li>
                <li><span>{{$manifest->unique_name}}</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    {!! Form::model($manifest,['method' => 'PATCH', 'action' => ['ManifestController@update', $manifest->id ] ]) !!}
    {{csrf_field()}}

    <div class="row">
        <div class="col-md-12">

            <section class="panel">
                <header class="panel-heading">

                    <h2 class="panel-title">{{$manifest->unique_name}}</h2>

                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group @if ($errors->has('vendor_id')) has-error  @endif">
                                <label class="col-sm-4 control-label">Vendor:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    {!! Form::select('vendor_id', $vendors, $manifest->vendor_id, ['class'=>'form-control ',
                                                                                        'placeholder' => 'Select Vendor',
                                                                                        'onchange'=>'enableVendor();',
                                                                                        'id'=>'selectVendor'
                                                                                        ]); !!}

                                    @if ($errors->has('vendor_id'))
                                        <label for="vendor_id" class="error">{{ $errors->first('vendor_id') }}</label>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group @if ($errors->has('amount')) has-error  @endif">
                                <label class="col-sm-4 control-label">Amount:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="amount" value="{{$manifest->amount}}">

                                    @if ($errors->has('amount'))
                                        <label for="amount" class="error">{{ $errors->first('amount') }}</label>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group @if ($errors->has('manifest_date')) has-error  @endif">
                                <label class="col-sm-4 control-label">Manifest Date:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                        <input type="text" id="manifest_date" name="manifest_date" data-plugin-datepicker="" class="form-control" value="{{date('m/d/Y',strtotime($manifest->manifest_date))}}">
                                    </div>
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
