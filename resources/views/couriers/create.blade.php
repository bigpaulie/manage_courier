@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Courier</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
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
            <div class="col-md-6">

                    <section class="panel">
                        <header class="panel-heading">

                            <h2 class="panel-title">Sender Details</h2>

                        </header>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('s_name')) has-error  @endif">
                                <label class="col-sm-4 control-label">Name: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_name" class="form-control" value="{{old('s_name')}}">
                                    @if ($errors->has('s_name'))
                                        <label for="s_name" class="error">{{ $errors->first('s_name') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('s_company')) has-error  @endif">
                                <label class="col-sm-4 control-label">Company Name: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_company" class="form-control" value="{{old('s_company')}}">
                                    @if ($errors->has('s_company'))
                                        <label for="s_name" class="error">{{ $errors->first('s_company') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_address1')) has-error  @endif">
                                <label class="col-sm-4 control-label">Addess1: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_address1" class="form-control" value="{{old('s_address1')}}">
                                    @if ($errors->has('s_address1'))
                                        <label for="s_address1" class="error">{{ $errors->first('s_address1') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Addess2: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_address2" class="form-control" value="{{old('s_address2')}}">
                                </div>
                            </div>



                            <div class="form-group @if ($errors->has('s_phone')) has-error  @endif">
                                <label class="col-sm-4 control-label">Phone: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_phone" class="form-control" value="{{old('s_phone')}}">
                                    @if ($errors->has('s_phone'))
                                        <label for="s_phone" class="error">{{ $errors->first('s_phone') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_country')) has-error  @endif">
                                <label class="col-sm-4 control-label">Country: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="s_country" name="s_country" v-model="s_country" @change="getStates('sender')">
                                        <option value="">Select Country</option>
                                        <option  v-for="country in countries" :value="country.id">@{{country.name}}</option>
                                    </select>
                                    @if ($errors->has('s_country'))
                                        <label for="s_country" class="error">{{ $errors->first('s_country') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_state')) has-error  @endif">
                                <label class="col-sm-4 control-label">State: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="s_state" name="s_state" v-model="s_state" @change="getCities('sender')">
                                        <option value="">Select State</option>
                                        <option  v-for="state in s_states" :value="state.id">@{{state.state_name}}</option>
                                    </select>
                                    @if ($errors->has('s_state'))
                                        <label for="s_state" class="error">{{ $errors->first('s_state') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_city')) has-error  @endif">
                                <label class="col-sm-4 control-label">City: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="s_city" name="s_city" >
                                        <option value="">Select City</option>
                                        <option  v-for="city in s_cities" :value="city.id">@{{city.city_name}}</option>
                                    </select>
                                    @if ($errors->has('s_city'))
                                        <label for="s_city" class="error">{{ $errors->first('s_city') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('s_email')) has-error  @endif">
                                <label class="col-sm-4 control-label">Email: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="s_email" class="form-control" value="{{old('s_email')}}">

                                    @if ($errors->has('s_email'))
                                        <label for="s_email" class="error">{{ $errors->first('s_email') }}</label>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </section>

            </div>

            <div class="col-md-6">

                    <section class="panel">
                        <header class="panel-heading">

                            <h2 class="panel-title">Recipient Details</h2>


                        </header>
                        <div class="panel-body">
                            <div class="form-group @if ($errors->has('r_name')) has-error  @endif">
                                <label class="col-sm-4 control-label">Name: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_name" class="form-control" value="{{old('r_name')}}">
                                    @if ($errors->has('r_name'))
                                        <label for="r_name" class="error">{{ $errors->first('r_name') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('r_company')) has-error  @endif">
                                <label class="col-sm-4 control-label">Company Name: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_company" class="form-control" value="{{old('r_company')}}">
                                    @if ($errors->has('r_company'))
                                        <label for="r_company" class="error">{{ $errors->first('r_company') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_address1')) has-error  @endif">
                                <label class="col-sm-4 control-label">Addess1: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_address1" class="form-control" value="{{old('r_address1')}}">
                                    @if ($errors->has('r_address1'))
                                        <label for="r_address1" class="error">{{ $errors->first('r_address1') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Addess2: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_address2" class="form-control" value="{{old('r_address2')}}">
                                </div>
                            </div>



                            <div class="form-group @if ($errors->has('r_phone')) has-error @endif">
                                <label class="col-sm-4 control-label">Phone: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_phone" class="form-control" value="{{old('r_phone')}}">
                                    @if ($errors->has('r_phone'))
                                        <label for="r_phone" class="error">{{ $errors->first('r_phone') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_country')) has-error @endif">
                                <label class="col-sm-4 control-label">Country: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="r_country" name="r_country" v-model="r_country" @change="getStates('reciver')">
                                        <option value="">Select Country</option>
                                        <option  v-for="country in countries" :value="country.id">@{{country.name}}</option>
                                    </select>
                                    @if ($errors->has('r_country'))
                                        <label for="r_country" class="error">{{ $errors->first('r_country') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_state')) has-error @endif">
                                <label class="col-sm-4 control-label">State: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="r_state" name="r_state" v-model="r_state" @change="getCities('reciver')">
                                        <option value="">Select State</option>
                                        <option  v-for="rstate in r_states" :value="rstate.id">@{{rstate.state_name}}</option>
                                    </select>
                                    @if ($errors->has('r_state'))
                                        <label for="r_state" class="error">{{ $errors->first('r_state') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_city')) has-error @endif">
                                <label class="col-sm-4 control-label">City: </label>
                                <div class="col-sm-8">
                                    <select class="form-control mb-md" id="r_city" name="r_city" >
                                        <option value="">Select City</option>
                                        <option  v-for="r_city in r_cities" :value="r_city.id">@{{r_city.city_name}}</option>
                                    </select>
                                    @if ($errors->has('r_city'))
                                        <label for="r_city" class="error">{{ $errors->first('r_city') }}</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('r_email')) has-error @endif">
                                <label class="col-sm-4 control-label">Email: </label>
                                <div class="col-sm-8">
                                    <input type="text" name="r_email" class="form-control" value="{{old('r_email')}}">
                                    @if ($errors->has('r_email'))
                                        <label for="r_email" class="error">{{ $errors->first('r_email') }}</label>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </section>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Shipping Details</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Package Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('package_type_id', $package_types, old('package_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Package Type','required']); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Service Type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('service_type_id', $service_types, old('service_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Service Type','required']); !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Content type: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('content_type_id', $content_types, old('content_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Content Type','required']); !!}

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

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Pickup/Drop: </label>
                                    <div class="col-sm-8">
                                        {!! Form::select('courier_status', ['pickup'=>'Pickup','drop'=>'Drop'], old('content_type_id'), ['class'=>'form-control mb-md','placeholder' => 'Select Courier Status','required']); !!}

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </section>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <section class="panel">
                    <header class="panel-heading">

                        <h2 class="panel-title">Description</h2>

                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Description: </label>
                                    <div class="col-sm-8">
                                        <textarea name="description" rows="5" cols="100">{{old('description')}}</textarea>
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

        const oapp = new Vue({
            el:'#app',
            data:{
                countries:@json($countries),

                s_states:null,
                s_cities:null,
                s_country:"",
                s_state:"",
                r_states:null,
                r_cities:null,
                r_country:"",
                r_state:"",
            },
            created(){
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

            computed: {

            }

        });

    </script>

@endsection
