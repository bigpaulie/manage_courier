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
                            <label class="control-label">Breadth<span class="text-danger">*</span></label>
                            <div class="input-group ">
                                <input type="text" :name="'box[' + [b]+'][breadth]'" class="form-control" required>
                                <span class="input-group-addon ">Inch</span>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Width<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][width]'" class="form-control" required>
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Height<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][height]'" class="form-control" required>
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Weight<span class="text-danger">*</span></label>
                            <div class="input-group">
                                 <input type="text" :name="'box[' + [b]+'][weight]'" class="form-control" required>
                                <span class="input-group-addon ">Kg/Grm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <h4 class="text-primary">Items</h4>
                    </div>
                    <div class="col-md-9">
                        <button style="margin-top: 10px;" type="button"  class="btn btn-primary pull-right" v-on:click="addItem(b)">Add Item</button>
                    </div>
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
                            <input type="text" :name="'box[' + [b]+'][items]['+[i]+'][qty]'" class="form-control">
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
            <button class="btn btn-primary" type="submit">Submit</button>
        </footer>

    {!! Form::close() !!}


    <!-- end: page -->

@endsection

@section('scripts')


    <script type="text/javascript">

        jQuery(document).ready(function($) {

        });

        const oapp = new Vue({
            el:'#app',
            data:{
                no_of_boxes:"{{$no_of_boxes}}",
                boxes: @json($boxes),
                content_unints:@json($content_unints),

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
                }

            },

            computed: {

            }

        });

    </script>


@endsection
