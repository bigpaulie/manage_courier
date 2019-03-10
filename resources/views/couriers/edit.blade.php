@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Courier</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Couriers</span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->

        {!! Form::model($courier,['method' => 'PATCH', 'action' => ['CourierController@update', $courier->id ] ]) !!}

        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Sender Details</h2>

                    </header>
                    <div class="panel-body">
                        <div class="form-group @if ($errors->has('s_name')) has-error  @endif">
                            <label class="col-sm-4 control-label">Name:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_name" class="form-control" value="{{$courier->s_name}}">
                                @if ($errors->has('s_name'))
                                    <label for="s_name" class="error">{{ $errors->first('s_name') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('s_company')) has-error @endif">
                            <label class="col-sm-4 control-label">Company Name:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_company" class="form-control" value="{{$courier->s_company}}">
                                @if ($errors->has('s_company'))
                                    <label for="s_name" class="error">{{ $errors->first('s_company') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_address1')) has-error  @endif">
                            <label class="col-sm-4 control-label">Addess1:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_address1" class="form-control" value="{{$courier->s_address1}}">
                                @if ($errors->has('s_address1'))
                                    <label for="s_address1" class="error">{{ $errors->first('s_address1') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Addess2: </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_address2" class="form-control" value="{{$courier->s_address2}}">
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_country')) has-error @endif">
                            <label class="col-sm-4 control-label">Country:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <select class="form-control mb-md" id="s_country" name="s_country" v-model="s_country" >
                                    <option value="">Select Country</option>
                                    <option  v-for="country in countries" :value="country.id">@{{country.name}}</option>
                                </select>
                                @if ($errors->has('s_country'))
                                    <label for="s_country" class="error">{{ $errors->first('s_country') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_state')) has-error  @endif">
                            <label class="col-sm-4 control-label">State:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                {{--<select class="form-control mb-md" id="s_state" name="s_state" v-model="s_state" @change="getCities('sender')">--}}
                                    {{--<option value="">Select State</option>--}}
                                    {{--<option  v-for="state in s_states" :value="state.id">@{{state.state_code}}</option>--}}
                                {{--</select>--}}

                                <input type="text" name="s_state" class="form-control" value="{{$courier->s_state}}">

                            @if ($errors->has('s_state'))
                                    <label for="s_state" class="error">{{ $errors->first('s_state') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_city')) has-error  @endif">
                            <label class="col-sm-4 control-label">City:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                {{--<select class="form-control mb-md" id="s_city" name="s_city" v-model="s_city">--}}
                                    {{--<option value="">Select City</option>--}}
                                    {{--<option  v-for="city in s_cities" :value="city.id">@{{city.city_name}}</option>--}}
                                {{--</select>--}}

                            <input type="text" name="s_city" class="form-control" value="{{$courier->s_city}}">

                            @if ($errors->has('s_city'))
                                    <label for="s_city" class="error">{{ $errors->first('s_city') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_phone')) has-error @endif">
                            <label class="col-sm-4 control-label">Phone: </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_phone" class="form-control" value="{{$courier->s_phone}}">
                                @if ($errors->has('s_phone'))
                                    <label for="s_phone" class="error">{{ $errors->first('s_phone') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('s_email')) has-error @endif">
                            <label class="col-sm-4 control-label">Email: </label>
                            <div class="col-sm-8">
                                <input type="text" name="s_email" class="form-control" value="{{$courier->s_email}}">
                                @if ($errors->has('s_email'))
                                    <label for="s_email" class="error">{{ $errors->first('s_email') }}</label>
                                @endif
                            </div>
                        </div>
                    </div>

                </section>

            </div>

            <div class="col-md-4">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Recipient Details</h2>


                    </header>
                    <div class="panel-body">
                        <div class="form-group @if ($errors->has('r_name')) has-error  @endif">
                            <label class="col-sm-4 control-label">Name:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_name" class="form-control" value="{{$courier->r_name}}">
                                @if ($errors->has('r_name'))
                                    <label for="r_name" class="error">{{ $errors->first('r_name') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('r_company')) has-error  @endif">
                            <label class="col-sm-4 control-label">Company Name:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_company" class="form-control" value="{{$courier->r_company}}">
                                @if ($errors->has('r_company'))
                                    <label for="r_company" class="error">{{ $errors->first('r_company') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_address1')) has-error  @endif">
                            <label class="col-sm-4 control-label">Addess1:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_address1" class="form-control" value="{{$courier->r_address1}}">
                                @if ($errors->has('r_address1'))
                                    <label for="r_address1" class="error">{{ $errors->first('r_address1') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Addess2: </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_address2" class="form-control" value="{{$courier->r_address2}}">
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_country')) has-error @endif">
                            <label class="col-sm-4 control-label">Country:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                <select class="form-control mb-md" id="r_country" name="r_country" v-model="r_country" >
                                    <option value="">Select Country</option>
                                    <option  v-for="country in countries" :value="country.id">@{{country.name}}</option>
                                </select>
                                @if ($errors->has('r_country'))
                                    <label for="r_country" class="error">{{ $errors->first('r_country') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_state')) has-error @endif">
                            <label class="col-sm-4 control-label">State:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                {{--<select class="form-control mb-md" id="r_state" name="r_state" v-model="r_state" @change="getCities('reciver')">--}}
                                    {{--<option value="">Select State</option>--}}
                                    {{--<option  v-for="rstate in r_states" :value="rstate.id">@{{rstate.state_code}}</option>--}}
                                {{--</select>--}}

                                <input type="text" name="r_state" class="form-control" value="{{$courier->r_state}}">

                            @if ($errors->has('r_state'))
                                    <label for="r_state" class="error">{{ $errors->first('r_state') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_city')) has-error @endif">
                            <label class="col-sm-4 control-label">City:<span class="text-danger">*</span> </label>
                            <div class="col-sm-8">
                                {{--<select class="form-control mb-md" id="r_city" name="r_city" v-model="r_city">--}}
                                    {{--<option value="">Select City</option>--}}
                                    {{--<option  v-for="r_city in r_cities" :value="r_city.id">@{{r_city.city_name}}</option>--}}
                                {{--</select>--}}

                                <input type="text" name="r_city" class="form-control" value="{{$courier->r_city}}">

                            @if ($errors->has('r_city'))
                                    <label for="r_city" class="error">{{ $errors->first('r_city') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_zip_code')) has-error @endif">
                            <label class="col-sm-4 control-label">Zip Code: </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_zip_code" class="form-control" value="{{$courier->r_zip_code}}">
                                @if ($errors->has('r_zip_code'))
                                    <label for="r_zip_code" class="error">{{ $errors->first('r_zip_code') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_phone')) has-error @endif">
                            <label class="col-sm-4 control-label">Phone: </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_phone" class="form-control" value="{{$courier->r_phone}}">
                                @if ($errors->has('r_phone'))
                                    <label for="r_phone" class="error">{{ $errors->first('r_phone') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('r_email')) has-error @endif">
                            <label class="col-sm-4 control-label">Email: </label>
                            <div class="col-sm-8">
                                <input type="text" name="r_email" class="form-control" value="{{$courier->r_email}}">
                                @if ($errors->has('r_email'))
                                    <label for="r_email" class="error">{{ $errors->first('r_email') }}</label>
                                @endif
                            </div>
                        </div>
                    </div>

                </section>
            </div>

            <div class="col-md-4">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Shipping Details</h2>

                    </header>
                    <div class="panel-body">


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Package Type:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('package_type_id', $package_types, $courier->shippment->package_type_id, ['class'=>'form-control mb-md','placeholder' => 'Select Package Type','required']); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Service Type:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('service_type_id', $service_types, $courier->shippment->service_type_id, ['class'=>'form-control mb-md','placeholder' => 'Select Service Type','required']); !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">No of Boxes:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="no_of_boxes" class="form-control" required value="{{$courier->no_of_boxes}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="status_id">
                                            <option value="">Select Status</option>
                                            @foreach($status as $st)
                                                <option value="{{$st->id}}" @if($st->id == $courier->status_id) {{"selected"}} @endif style="color: {{$st->color_code}}">{{$st->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Weight:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-md">
                                            <input type="text" name="weight" v-model="courier_weight"  class="form-control" required value="{{$courier->shippment->weight}}" />
                                            <span class="input-group-addon ">Kg/Grm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Carriage Value:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="carriage_value" class="form-control" value="{{$courier->shippment->carriage_value}}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Date:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            <input type="text" name="courier_date" data-plugin-datepicker="" class="form-control" value="{{date('m/d/Y',strtotime($courier->courier_date))}}">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tracking No:</label>
                                    <div class="col-sm-8">
                                        <input type="text"  name="tracking_no" class="form-control"  value="{{$courier->tracking_no}}">
                                    </div>
                                </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Description: </label>
                            <div class="col-sm-8">
                                <textarea name="description" rows="3" cols="25">{{$courier->description}}</textarea>
                            </div>
                        </div>

                    </div>

                </section>

            </div>

        </div>

         <div class="row">
            <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">


                    <h2 class="panel-title">Payment Details</h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">


                            <div class="form-group  @if ($errors->has('total')) has-error  @endif">
                                <label class="col-md-4 control-label" for="inputDefault">Total Amount<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control text-capitalize" id="total" name="total"  v-model="total_amount">
                                    @if ($errors->has('total'))
                                        <label for="total" class="error">{{ $errors->first('total') }}</label>
                                    @endif
                                </div>
                            </div>
                            @if(Auth::user()->user_type != 'agent')
                                <div class="form-group  @if ($errors->has('pay_amount')) has-error  @endif">
                                    <label class="col-md-4 control-label" for="inputDefault">Paid Amount<span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-capitalize" id="pay_amount" name="pay_amount" v-model="paid_amount">
                                        @if ($errors->has('pay_amount'))
                                            <label for="pay_amount" class="error">{{ $errors->first('pay_amount') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('discount')) has-error  @endif">
                                    <label class="col-md-4 control-label" for="inputDefault">Discount</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-capitalize" id="discount" name="discount" v-model="discount">
                                        @if ($errors->has('discount'))
                                            <label for="discount" class="error">{{ $errors->first('discount') }}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group @if ($errors->has('remaining')) has-error  @endif">
                                    <label class="col-md-4 control-label" for="inputDefault">Remaining Amount</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control text-capitalize" id="remaining" name="remaining" v-model="remaining_amount">
                                        @if ($errors->has('remaining'))
                                            <label for="remaining" class="error">{{ $errors->first('remaining') }}</label>
                                        @endif
                                    </div>
                                </div>


                            @endif

                            <div class="form-group @if ($errors->has('email')) has-error  @endif">
                                <label class="col-md-4 control-label" for="inputDefault">Payment Date<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
                                        <input type="text" name="payment_date" data-plugin-datepicker="" class="form-control" value="{{date('m/d/Y')}}">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <span>1 kg weight value is <b>@{{ calculate_weight }}</b></span>
                        </div>
                    </div>


                </div>


            </section>


        </div>
        </div>

        <footer class="panel-footer center">
            <button class="btn btn-primary">Next</button>
        </footer>
    </form>


    <!-- end: page -->

@endsection

@section('scripts')

    <script type="text/javascript">

        jQuery(document).ready(function($) {

        });

        const oapp = new Vue({
            el:'#app',
            data:{
                countries:@json($countries),


                s_country:'{{$courier->s_country}}',

                r_country:"{{$courier->r_country}}",

                total_weight:"{{$courier->shippment->weight}}",
                total_amount:"{{$courier_payment->total}}",
                paid_amount:"{{$courier_payment->pay_amount}}",
                discount:"{{$courier_payment->discount}}",
                remaining_amount:"{{$courier_payment->remaining}}",
                calculate_weight:0,
                courier_weight:"{{$courier->shippment->weight}}",

            },
            created(){
                if(this.total_amount > 0){
                    var calculate_value = parseFloat(this.total_amount)/parseFloat(this.courier_weight);
                    this.calculate_weight = calculate_value.toFixed(1);
                }
                //console.log(this.countries);
            },


            methods: {

                getStates(type){
                    if(type == 'sender' && parseInt(this.s_country) > 0 ){
                        let state_url = '/api/getStates?country_id='+this.s_country;
                        axios.get(state_url).then(response => (this.s_states = response.data));
                    }

                    if(type == 'reciver' && parseInt(this.r_country) > 0 ){
                        let state_url = '/api/getStates?country_id='+this.r_country;
                        axios.get(state_url).then(response => (this.r_states = response.data));
                    }

                },
                getCities(type){
                    if( type == 'sender' && parseInt(this.s_state) > 0 ){
                        let city_url = '/api/getCities?state_id='+this.s_state;
                        axios.get(city_url).then(response => (this.s_cities = response.data));
                    }

                    if( type == 'reciver' && parseInt(this.r_state) > 0 ){
                        let city_url = '/api/getCities?state_id='+this.r_state;
                        axios.get(city_url).then(response => (this.r_cities = response.data));
                    }
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
                        if(parseFloat(this.courier_weight) > 0){
                            var calculate_value = parseFloat(value)/parseFloat(this.courier_weight);
                            this.calculate_weight = calculate_value.toFixed(1);
                        }

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
                courier_weight(value){
                    if(parseInt(this.total_amount) > 0){
                        var calculate_value = parseFloat(this.total_amount)/parseFloat(value);
                        this.calculate_weight = calculate_value.toFixed(1);
                    }
                },

            },

            computed: {

            }

        });

    </script>

@endsection
