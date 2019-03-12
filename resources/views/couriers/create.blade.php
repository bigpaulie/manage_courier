@extends('layouts.admin')
@section('date-styles')
@endsection

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
    <form id="frmcourier" action="{{route('couriers.store')}}" class="form-horizontal form-bordered" method="POST">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4">

                    <section class="panel">
                        <header class="panel-heading">

                            <h2 class="panel-title">Sender Details</h2>

                        </header>
                        <div class="panel-body">

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Self Address: </label>
                                <div class="col-sm-8">
                                    <input type="checkbox" v-model="self_address" @if(old('self_address') == 1) {{"checked"}} @endif name="self_address" class="checkbox" value="1" @click="fillUserData">
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_phone')) has-error  @endif">
                                <label class="col-sm-4 control-label">Phone:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    {{--<input type="text" name="s_phone" class="form-control" value="{{old('s_phone')}}" v-model="s_phone">--}}

                                    <input type="hidden" name="s_phone" id="senderPhone">
                                    <vue-bootstrap-typeahead
                                            v-model="s_phone"
                                            :data="senderPhones"
                                            :serializer="item => item.s_phone"
                                            @hit="selectedPhone = $event"
                                            class="phone_typeahead"

                                    />

                                        <template slot="suggestion" slot-scope="{ data, htmlText }">
                                            <div class="d-flex align-items-center">

                                                <div style="width: 100%">@{{data.s_phone}} <b>(@{{data.s_name}} - @{{data.s_company}})</b></div>
                                            </div>
                                        </template>



                                    @if ($errors->has('s_phone'))
                                        <label for="s_phone" class="error">{{ $errors->first('s_phone') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_name')) has-error  @endif">
                                <label class="col-sm-4 control-label">Name:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">


                                    <input type="hidden" name="s_name" id="senderName">
                                    <vue-bootstrap-typeahead
                                            v-model="s_name"
                                            :data="senderNames"
                                            :serializer="item => item.s_name"
                                            @hit="selectedSName = $event"
                                            class="phone_typeahead"

                                    />

                                    <template slot="suggestion" slot-scope="{ data, htmlText }">
                                        <div class="d-flex align-items-center">

                                            <div style="width: 100%">@{{data.s_phone}} <b>(@{{data.s_name}} - @{{data.s_company}})</b></div>
                                        </div>
                                    </template>

                                    {{--<input type="text" name="s_name" class="form-control text-capitalize" value="{{old('s_name')}}" v-model="s_name">--}}
                                    @if ($errors->has('s_name'))
                                        <label for="s_name" class="error">{{ $errors->first('s_name') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('s_company')) has-error  @endif">
                                <label class="col-sm-4 control-label">Company Name:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_company" class="form-control text-capitalize" value="{{old('s_company')}}" v-model="s_company">
                                    @if ($errors->has('s_company'))
                                        <label for="s_name" class="error">{{ $errors->first('s_company') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_address1')) has-error  @endif">
                                <label class="col-sm-4 control-label">Addess1:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_address1" class="form-control" value="{{old('s_address1')}}" v-model="s_address1">
                                    @if ($errors->has('s_address1'))
                                        <label for="s_address1" class="error">{{ $errors->first('s_address1') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Addess2: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_address2" class="form-control" value="{{old('s_address2')}}" v-model="s_address2">
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('s_country')) has-error  @endif">
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


                                    <input type="text" name="s_state" class="form-control" value="{{old('s_state')}}" v-model="s_state">

                                @if ($errors->has('s_state'))
                                        <label for="s_state" class="error">{{ $errors->first('s_state') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_city')) has-error  @endif">
                                <label class="col-sm-4 control-label">City:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                  <input type="text" name="s_city" class="form-control" value="{{old('s_city')}}" v-model="s_city">

                                @if ($errors->has('s_city'))
                                        <label for="s_city" class="error">{{ $errors->first('s_city') }}</label>
                                    @endif
                                </div>
                            </div>

                            {{--<div class="form-group @if ($errors->has('s_zip_code')) has-error  @endif">--}}
                                {{--<label class="col-sm-4 control-label">Zip Code: </label>--}}
                                {{--<div class="col-sm-8">--}}
                                    {{--<input type="text" name="s_zip_code" class="form-control" value="{{old('s_zip_code')}}" v-model="s_zip_code">--}}
                                    {{--@if ($errors->has('s_zip_code'))--}}
                                        {{--<label for="s_zip_code" class="error">{{ $errors->first('s_zip_code') }}</label>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}



                            <div class="form-group @if ($errors->has('s_email')) has-error  @endif">
                                <label class="col-sm-4 control-label">Email: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_email" class="form-control" value="{{old('s_email')}}" v-model="s_email">

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


                            <div class="form-group ">
                                <label class="col-sm-4 control-label">Courier No: </label>
                                <div class="col-sm-8">
                                        <label class="control-label text-primary"><strong>{{$courier_unique_no}}</strong></label>
                                </div>
                            </div>


                            <div class="form-group @if ($errors->has('r_name')) has-error  @endif">
                                <label class="col-sm-4 control-label">Name:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_name" v-model="r_name" class="form-control text-capitalize" value="{{old('r_name')}}">
                                    @if ($errors->has('r_name'))
                                        <label for="r_name" class="error">{{ $errors->first('r_name') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('r_company')) has-error  @endif">
                                <label class="col-sm-4 control-label">Company Name:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_company" v-model="r_company" class="form-control text-capitalize" value="{{old('r_company')}}">
                                    @if ($errors->has('r_company'))
                                        <label for="r_company" class="error">{{ $errors->first('r_company') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_address1')) has-error  @endif">
                                <label class="col-sm-4 control-label">Addess1:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_address1" v-model="r_address1" class="form-control" value="{{old('r_address1')}}">
                                    @if ($errors->has('r_address1'))
                                        <label for="r_address1" class="error">{{ $errors->first('r_address1') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Addess2: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_address2" v-model="r_address2" class="form-control" value="{{old('r_address2')}}">
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_country')) has-error @endif">
                                <label class="col-sm-4 control-label">Country:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="r_country" v-model="r_country" name="r_country" v-model="r_country">
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

                                    <input type="text" name="r_state" class="form-control" v-model="r_state" value="{{old('r_state')}}" >

                                @if ($errors->has('r_state'))
                                        <label for="r_state" class="error">{{ $errors->first('r_state') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_city')) has-error @endif">
                                <label class="col-sm-4 control-label">City:<span class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_city" v-model="r_city" class="form-control" value="{{old('r_city')}}">

                                @if ($errors->has('r_city'))
                                        <label for="r_city" class="error">{{ $errors->first('r_city') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_zip_code')) has-error @endif">
                                <label class="col-sm-4 control-label">Zip Code: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_zip_code" v-model="r_zip_code" class="form-control" value="{{old('r_zip_code')}}">
                                    @if ($errors->has('r_zip_code'))
                                        <label for="r_zip_code" class="error">{{ $errors->first('r_zip_code') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_phone')) has-error @endif">
                                <label class="col-sm-4 control-label">Phone: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_phone" v-model="r_phone" class="form-control" value="{{old('r_phone')}}">
                                    @if ($errors->has('r_phone'))
                                        <label for="r_phone" class="error">{{ $errors->first('r_phone') }}</label>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group @if ($errors->has('r_email')) has-error @endif">
                                <label class="col-sm-4 control-label">Email: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_email" v-model="r_email" class="form-control" value="{{old('r_email')}}">
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
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Package Type:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('package_type_id', $package_types, old('package_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Package Type','required']); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Service Type:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('service_type_id', $service_types, old('service_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Service Type','required']); !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">No of Boxes:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="no_of_boxes" class="form-control" required value="{{old('no_of_boxes')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Weight:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-md">
                                            <input type="text" name="weight" v-model="courier_weight"  required class="form-control" value="{{old('weight')}}" />
                                            <span class="input-group-addon ">Kg/Grm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Carriage Value:<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="number" min="1" name="carriage_value" class="form-control" required value="{{old('carriage_value')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Date:<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            <input type="text" name="courier_date" data-plugin-datepicker="" class="form-control" value="{{date('m/d/Y')}}">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description: </label>
                                    <div class="col-sm-8">
                                        <textarea name="description" rows="10" cols="25">{{old('description')}}</textarea>
                                    </div>
                                </div>


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

    @include('couriers.recipient_model')
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
                user_data:@json($user_data),
                s_name:"",
                s_company:"",
                s_address1:"",
                s_address2:"",
                s_phone:"",
                s_zip_code:"",
                s_email:"",
                s_country:"",
                s_state:"",
                s_city:"",
                r_name:"",
                r_company:"",
                r_address1:"",
                r_address2:"",
                r_phone:"",
                r_zip_code:"",
                r_email:"",
                r_city:"",
                r_states:null,
                r_cities:null,
                r_country:"{{old('r_country')}}",
                r_state:"",
                self_address:"{{old('self_address',false)}}",
                s_states:@json($s_states),
                s_cities:@json($s_cities),
                senderPhones:[],
                repicipentAddress:[],
                selectedPhone:null,
                selectedRecipient:null,
                total_amount:"{{$courier_payment->total}}",
                paid_amount:"{{$courier_payment->pay_amount}}",
                discount:"{{$courier_payment->discount}}",
                remaining_amount:"{{$courier_payment->remaining}}",
                calculate_weight:0,
                courier_weight:0,
                senderNames:[],
                selectedSName:null,




            },
            created(){
               if(this.self_address){
                         this.s_name = this.user_data.name;
                         this.s_company = this.user_data.profile.company_name;
                         this.s_address1 = this.user_data.profile.address;
                         this.s_phone = this.user_data.profile.phone;
                         //this.s_zip_code = this.user_data.profile.zip_code;
                         this.s_email = this.user_data.email;
                         this.s_country = this.user_data.profile.country_id;
                         this.s_state = this.user_data.profile.state_id;
                         this.s_city = this.user_data.profile.city_id;
                     }
            },

            watch: {
                // When the query value changes, fetch new results from
                // the API - in practice this action should be debounced
                s_phone(newQuery) {
                    axios.get(`/api/get_sender_phone?q=${newQuery}`)
                        .then((res) => {
                        this.senderPhones = res.data;
                })
                    $('#senderPhone').val(newQuery);

                },

                s_name(newQuery) {
                    axios.get(`/api/get_sender_name?q=${newQuery}`)
                        .then((res) => {
                        this.senderNames = res.data;
                })
                    $('#senderName').val(newQuery);

                },
                selectedPhone(obj){
                    console.log(obj);
                    this.s_name = obj.s_name;
                    this.s_company = obj.s_company;
                    this.s_address1 = obj.s_address1;
                    this.s_email = obj.s_email;
                    this.s_country = obj.s_country;
                    this.s_state = obj.s_state;
                    this.s_city = obj.s_city;

                    axios.get(`/api/get_recipient_address?q=${obj.s_phone}`)
                        .then((res) => {
                        this.repicipentAddress = res.data;
                })

                    $.magnificPopup.open({
                        items: {
                            src: '#recipientModal'
                        },
                        type: 'inline'
                    });

                },

                selectedSName(obj){
                    console.log(obj);

                    this.s_phone = obj.s_phone;
                    this.s_company = obj.s_company;
                    this.s_address1 = obj.s_address1;
                    this.s_email = obj.s_email;
                    this.s_country = obj.s_country;
                    this.s_state = obj.s_state;
                    this.s_city = obj.s_city;

                    axios.get(`/api/get_recipient_address?q=${obj.s_phone}`)
                        .then((res) => {
                        this.repicipentAddress = res.data;
                })

                    $.magnificPopup.open({
                        items: {
                            src: '#recipientModal'
                        },
                        type: 'inline'
                    });

                },
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


            methods: {

                fillUserData(){
                    this.self_address = !this.self_address;
                     if(this.self_address){
                         this.s_name = this.user_data.name;
                         this.s_company = this.user_data.profile.company_name;
                         this.s_address1 = this.user_data.profile.address;
                         this.s_phone = this.user_data.profile.phone;
                        // this.s_zip_code = this.user_data.profile.zip_code;
                         this.s_email = this.user_data.email;
                         this.s_country = this.user_data.profile.country_id;
                         this.s_state = this.user_data.profile.state_id;
                         this.s_city = this.user_data.profile.city_id;

                     }else{
                         this.s_name = "";
                         this.s_company = "";
                         this.s_address1 = "";
                         this.s_phone = "";
                        // this.s_zip_code = "";
                         this.s_email = "";
                         this.s_country = "";
                         this.s_state = "";
                         this.s_city = "";

                     }
                },

                fillRecipient(){
                        this.r_name=this.selectedRecipient.r_name;
                        this.r_company=this.selectedRecipient.r_company;
                        this.r_address1=this.selectedRecipient.r_address1;
                        this.r_country=this.selectedRecipient.r_country;
                        this.r_company=this.selectedRecipient.r_company;
                        this.r_phone=this.selectedRecipient.r_phone;
                        this.r_state=this.selectedRecipient.r_state;
                        this.r_city=this.selectedRecipient.r_city;
                        this.r_zip_code=this.selectedRecipient.r_zip_code;
                        this.r_email=this.selectedRecipient.r_email;
                        $.magnificPopup.close();
                },

                cancelFillRecipent(){
                    $.magnificPopup.close();
                },

                selectRecipient(recipient_data){
                    this.selectedRecipient =recipient_data;
                },

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

            computed: {

            }

        });

    </script>

@endsection
