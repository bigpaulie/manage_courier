@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Box Details</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Couriers</span></li>
                <li><span>Box Details</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>
    @if (Session::has('error_message'))
        <div class="alert alert-danger">
            <strong> {{ Session::get('error_message') }}</strong>
        </div>
    @endif

    <section class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <h5><b>Total Weight:</b> {{$courier->shippment->weight}}</h5>
                </div>
                <div class="col-md-4">
                    <h5><b>No. Of Boxes:</b> {{$courier->no_of_boxes}}</h5>
                </div>
                <div class="col-md-4">
                    <h5><b>Courier Id:</b> {{$courier->unique_name}}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <div class="table-responsive">
                        <table class="table table-striped mb-none">
                            <thead>
                            <tr>
                                <th>Boxes</th>
                                <th>(L*W*H)</th>
                                <th>Cal/365</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="box,b in boxes">
                                <td>Box - @{{b}}</td>
                                <td>(@{{box.cb.breadth}} * @{{box.cb.width}} * @{{box.cb.height}} )</td>
                                <td>@{{(box.cb.breadth * box.cb.width * box.cb.height /365) | round_value}}</td>

                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total Volume: @{{ total_volume }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12"><b>Total Weight - Total Volume = Total</b></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><b>{{$courier->shippment->weight}} - @{{ total_volume }} = @{{courier_weight - total_volume  }}</b></div>
                    </div>
                </div>


            </div>
        </div>
    </section>


    {{Form::open(['url' => 'admin/save_courier_boxes/', 'method' => 'post'])}}
         {{csrf_field()}}
    <input type="hidden" name="courier_id" value="{{$courier->id}}">
        <section class="panel" v-for="box,b in boxes">
            <header class="panel-heading">
                <h2 class="panel-title">Box - @{{b}}</h2>
            </header>
            <div class="panel-body">
                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Length<span class="text-danger">*</span></label>
                            <div class="input-group ">
                                <input type="text" :name="'box[' + [b]+'][breadth]'" class="form-control"  v-model="box.cb.breadth">
                                <span class="input-group-addon ">Inch</span>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Width<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][width]'" class="form-control"  v-model="box.cb.width">
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Height<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][height]'" class="form-control"  v-model="box.cb.height">
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Weight<span class="text-danger">*</span></label>
                            <div class="input-group">
                                 <input type="text" :name="'box[' + [b]+'][weight]'" class="form-control"  v-model="box.cb.weight">
                                <span class="input-group-addon ">Kg/Grm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <h4 class="text-primary">Items</h4>
                    </div>
                    {{--<div class="col-md-9">--}}
                        {{--<button style="margin-top: 10px;" type="button"  class="btn btn-primary pull-right" v-on:click="addItem(b)">Add Item</button>--}}
                    {{--</div>--}}
                </div>
                <div class="row" v-for="item,i in box.items">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Item Name</label>
                            <select class="form-control" :name="'box[' + [b]+'][items]['+[i]+'][content_type_id]'" v-model="item.item_name" @change="getUnitType(b,i,this)">
                                <option value="" >Select Item</option>
                                @foreach ($content_types as $key =>$ct)
                                    <option value="{{$ct->id}}">{{$ct->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Unit</label>
                            <input type="text" :name="'box[' + [b]+'][items]['+[i]+'][unit_type]'" v-model="item.item_unit" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Qty</label>
                            <input type="text" :name="'box[' + [b]+'][items]['+[i]+'][qty]'" class="form-control" v-model="item.qty">
                        </div>
                    </div>

                    <div class="col-sm-3" v-if=" i == 0">
                        <div class="form-group">
                            <label class="control-label"></label>
                            <button style="margin-top: 25px;" type="button"  class="btn btn-primary " v-on:click="addItem(b)">Add Item</button>

                        </div>
                    </div>

                    <div class="col-sm-3" v-if=" i > 0">
                        <div class="form-group">
                            <label class="control-label"></label>
                            <button type="button" v-if="box.items.length > 1" class="btn btn-danger" v-on:click="removeItem(b,i)" style="margin-top: 25px;">Remove Item</button>

                        </div>
                    </div>
                </div>

            </div>
        </section>




        <footer class="panel-footer center">

            <button class="btn btn-warning" type="submit" name="back" value="back">Back</button>
            <button class="btn btn-primary" type="submit" name="save_box" value="save_box">Save</button>
           
        </footer>

    {!! Form::close() !!}


    <!-- end: page -->

@endsection

@section('scripts')


    <script type="text/javascript">
        var user_type = "{{Auth::user()->user_type}}";
        var courier_id = "{{$courier->id}}";
        jQuery(document).ready(function($) {

        });

        function fnEditPage(){
            window.location.href ="/"+user_type+"/couriers/"+courier_id+"/edit";
        }

        Vue.filter('round_value', function (value) {

            if (!value) return ''

            value = value.toFixed(1)
            return value;
        });


        const oapp = new Vue({
            el:'#app',
            data:{
                no_of_boxes:"{{$no_of_boxes}}",
                boxes: @json($boxes),
                content_unints:@json($content_unints),
                courier_weight:"{{$courier->shippment->weight}}",

            },

            methods: {
                addItem: function (b) {
                    this.boxes[b].items.push([{"item_name":"","item_unit":null,"qty":null,}])
                },
                removeItem:function(b,i){
                  this.$delete(this.boxes[b].items, i);
                },
                getUnitType:function(b,i,obj){
                    console.log(this.boxes[b].items[i].item_name);
                    var itemId =this.boxes[b].items[i].item_name;
                    var item_unit = this.content_unints[itemId];
                    this.boxes[b].items[i].item_unit = item_unit;
                },
                checkBoxWeight:function(){

                    //     var ls=0;
                    // total_box_weight = this.boxes.forEach(function(item) {
                    //     var tw = ls+parseInt(item.weight);
                    //     return tw;
                    // });
                    //
                    // console.log(total_box_weight);
                     return true;
                }

            },

            watch: {



            },

            computed: {

                total_volume: function(){

                    var total_vol=0;
                    for(i=1;i<=this.no_of_boxes;i++){
                        if(this.boxes[i].cb.breadth != ""){
                            var box_l = this.boxes[i].cb.breadth;
                        } else{ var box_l = 0; }
                        if(this.boxes[i].cb.width != "") {
                            var box_w = this.boxes[i].cb.width;
                        }else {
                            var box_w=0;
                        }
                        if(this.boxes[i].cb.width != "") {
                            var box_h = this.boxes[i].cb.height;
                        }else{
                            var box_h =0;
                        }
                        var cal_mul = parseFloat(box_l)*parseFloat(box_w)*parseFloat(box_h);
                        var cal_div = cal_mul/365;
                        total_vol= total_vol+cal_div;
                    }
                    return total_vol.toFixed(1);
                }

            }

        });

    </script>


@endsection
