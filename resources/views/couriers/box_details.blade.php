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
                            <label class="control-label">Breadth<span class="text-danger">*</span></label>
                            <div class="input-group ">
                                <input type="text" :name="'box[' + [b]+'][breadth]'" class="form-control" required v-model="box.cb.breadth">
                                <span class="input-group-addon ">Inch</span>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Width<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][width]'" class="form-control" required v-model="box.cb.width">
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Height<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" :name="'box[' + [b]+'][height]'" class="form-control" required v-model="box.cb.height">
                                <span class="input-group-addon ">Inch</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Weight<span class="text-danger">*</span></label>
                            <div class="input-group">
                                 <input type="text" :name="'box[' + [b]+'][weight]'" class="form-control" required v-model="box.cb.weight">
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


    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">


                    <h2 class="panel-title">Payment Details</h2>
                </header>
                <div class="panel-body">

                    <input type="hidden" name="courier_id" value="{{$courier->id}}">
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                    <div class="form-group  @if ($errors->has('total')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Total Amount<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control text-capitalize" id="total" name="total"  v-model="total_amount">
                            @if ($errors->has('total'))
                                <label for="total" class="error">{{ $errors->first('total') }}</label>
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->user_type != 'agent')
                        <div class="form-group  @if ($errors->has('pay_amount')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Paid Amount<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="pay_amount" name="pay_amount" v-model="paid_amount">
                                @if ($errors->has('pay_amount'))
                                    <label for="pay_amount" class="error">{{ $errors->first('pay_amount') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('discount')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Discount</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="discount" name="discount" v-model="discount">
                                @if ($errors->has('discount'))
                                    <label for="discount" class="error">{{ $errors->first('discount') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('remaining')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Remaining Amount</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="remaining" name="remaining" v-model="remaining_amount">
                                @if ($errors->has('remaining'))
                                    <label for="remaining" class="error">{{ $errors->first('remaining') }}</label>
                                @endif
                            </div>
                        </div>


                    @endif

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




                </div>


            </section>


        </div>
    </div>

        <footer class="panel-footer center">

            <button class="btn btn-primary" type="submit">Save</button>
           
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
                total_weight:"{{$courier->shippment->weight}}",
                total_amount:"{{$courier_payment->total}}",
                paid_amount:"{{$courier_payment->pay_amount}}",
                discount:"{{$courier_payment->discount}}",
                remaining_amount:"{{$courier_payment->remaining}}",


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
                // When the query value changes, fetch new results from
                // the API - in practice this action should be debounced
                total_amount(value) {
                    if(value == ''){
                        this.remaining_amount = 0;
                    }else{
                        this.remaining_amount = parseFloat(value);
                    }
                },
                paid_amount(value){

                    if(value === ""){
                        this.remaining_amount = parseFloat(this.total_amount);
                        this.paid_amount=0;
                    }else{
                        this.remaining_amount = parseFloat(this.total_amount) - (parseFloat(value)+parseFloat(this.discount));
                    }
                },

                discount(value){

                    if(value === ""){
                        this.remaining_amount = parseFloat(this.total_amount) - parseFloat(this.paid_amount);
                        this.discount=0;
                    }else{
                        this.remaining_amount = parseFloat(this.total_amount) - (parseFloat(this.paid_amount)+parseFloat(value));
                    }
                },


            },

            computed: {

            }

        });

    </script>


@endsection
