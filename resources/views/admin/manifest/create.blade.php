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

    @if (Session::has('error_message'))
        <div class="alert alert-danger">
            <strong> {{ Session::get('error_message') }}</strong>
        </div>
    @endif

    <?php   $item_exists=0;
            if(Session::has('manifest_data')){
                $menifest_data = Session::get('manifest_data');
                $item_exists=1;
                $courier_ids = $menifest_data['courier_ids'];
//                echo "<pre>";
//                print_r($menifest_data);
//                exit;

            }
    ?>


    <section class="panel">
        {!! Form::open(['url' => 'admin/manifest/create_manifest','method'=>'post']) !!}
        {{csrf_field()}}
        <header class="panel-heading">

            <input name="bulk" class="btn btn-primary pull-right hide" type="submit" value="Create a Bulk" id="btn_bulk" >
            <input name="item" style="margin-right: 10px;"  class="btn btn-primary pull-right hide" id="btn_item" type="submit" value="Add Item">
            <h2 class="panel-title">Manage Couriers</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th><input type="checkbox" id="selectall" class="checkbox-custom chekbox-primary" ></th>
                    <th >Id</th>
                    <th>Agent Name</th>
                    <th>Customer Name</th>
                    <th >Status</th>
                    <th >Country</th>
                    <th>Item/Bulked</th>

                </tr>
                </thead>
                <tbody>
                    @foreach($couriers as $courier)
                        <tr>
                            <td>
                                <?php if($item_exists && in_array($courier->id, $courier_ids)) {

                                }else { ?>
                                <input type="checkbox" name="courier_id[]" class="case"  value="{{$courier->id}}">
                                <?php }?>
                            </td>
                            <td>{{$courier->unique_name}}</td>
                            <td>{{$courier->agent->name}}</td>
                            <td>{{$courier->r_name}}</td>
                            <td><span style="color:{{$courier->status->color_code}};">{{$courier->status->name}}</span></td>
                            <td>{{$courier->receiver_country->name}}</td>
                            <td>
                                <?php if($item_exists){

                                    if(isset($menifest_data['items'])){
                                        $items = $menifest_data['items'];
                                        if(in_array($courier->id, $items)){
                                            echo "<strong>Item Added</strong>";
                                        }
                                    }
                                    if(isset($menifest_data['bulk_items'])){

                                        $bulk_items = $menifest_data['bulk_items'];
                                        $count_bulk=count($bulk_items);
                                        if($count_bulk > 0){
                                            foreach($bulk_items as $key=> $bi){
                                                $bulk_no = $key+1;
                                                if(in_array($courier->id, $bi)){
                                                    echo "<strong>Bulk".$bulk_no." </strong>";
                                                }
                                            }
                                        }
                                    }


                                } ?>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! Form::close() !!}

        {!! Form::open(['url' => 'admin/manifest/save_manifest','method'=>'post']) !!}
        {{csrf_field()}}

        <footer class="panel-footer">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('vendor_id')) has-error  @endif"">
                        <label class="col-sm-3 control-label">Vendors<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {!! Form::select('vendor_id', $vendors, old('vendor_id'), ['class'=>'form-control mb-md',
                                                                                        'placeholder' => 'Select Vendor',
                                                                                        'onchange'=>'enableVendor();',
                                                                                        'id'=>'selectVendor'
                                                                                        ]); !!}

                            @if ($errors->has('vendor_id'))
                                <label for="name" class="error">{{ $errors->first('vendor_id') }}</label>
                            @endif

                        </div>
                    </div>

                </div>
                <div class="col-sm-3">

                    <button class="btn btn-primary" disabled type="submit" id="btnSave">Save</button>
                </div>
            </div>
        </footer>
        {!! Form::close() !!}

    </section>
    <!-- end: page -->


@endsection

@section('scripts')

    <script>

        jQuery(document).ready(function($) {




        });

        $(function(){

            // add multiple select / deselect functionality
            $("#selectall").click(function () {
                //alert(this.checked);
                if(this.checked){
                    $("#btn_bulk").removeClass("hide");
                    $("#btn_item").addClass("hide");
                }else{
                    $("#btn_item").addClass("hide");
                    $("#btn_bulk").addClass("hide");
                }
                $(".case").prop("checked",$(this).prop("checked"));
            });

            // if all checkbox are selected, check the selectall checkbox
            // and viceversa
            $(".case").click(function(){

                if($(".case:checked").length == 1){
                    $("#btn_item").removeClass("hide");
                    $("#btn_bulk").addClass("hide");
                }
                if($(".case:checked").length > 1){
                    $("#btn_bulk").removeClass("hide");
                    $("#btn_item").addClass("hide");
                }

                if($(".case:checked").length == 0){
                    $("#btn_item").addClass("hide");
                    $("#btn_bulk").addClass("hide");
                }

                if($(".case").length == $(".case:checked").length) {
                    $("#selectall").attr("checked", "checked");
                } else {
                    $("#selectall").removeAttr("checked");
                }

            });
        });

        function enableVendor(){
            var vendor = $('#selectVendor').val();
            if(vendor !=""){
                $('#btnSave').removeAttr('disabled');
            }
        }



    </script>

@endsection