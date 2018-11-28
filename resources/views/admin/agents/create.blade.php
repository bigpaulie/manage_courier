@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Agent</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Agents</span></li>
                <li><span>Create</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">


                    <h2 class="panel-title">Agent</h2>
                </header>
                <div class="panel-body">
                    <form action="{{route('agents.store')}}" class="form-horizontal form-bordered" method="POST">
                        {{csrf_field()}}

                        <div class="form-group  @if ($errors->has('unique_name')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Unique Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="unique_name" name="unique_name" value="{{old('unique_name')}}">
                                @if ($errors->has('unique_name'))
                                    <label for="unique_name" class="error">{{ $errors->first('unique_name') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  @if ($errors->has('company_name')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Company Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="company_name" name="company_name" value="{{old('company_name')}}">
                                @if ($errors->has('company_name'))
                                     <label for="company_name" class="error">{{ $errors->first('company_name') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  @if ($errors->has('store_id')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Store Name</label>
                            <div class="col-md-6">
                                <select name="store_id" class="form-control">
                                    <option id="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name}} ({{$store->profile->company_name}})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('store_id'))
                                    <label for="store_id" class="error">{{ $errors->first('store_id') }}</label>
                                @endif
                            </div>
                        </div>


                        <div class="form-group @if ($errors->has('first_name')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">First Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="first_name" name="first_name" value="{{old('first_name')}}">
                                @if ($errors->has('first_name'))
                                    <label for="first_name" class="error">{{ $errors->first('first_name') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('last_name')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Last Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-capitalize" id="last_name" name="last_name" value="{{old('last_name')}}">
                                @if ($errors->has('last_name'))
                                    <label for="last_name" class="error">{{ $errors->first('last_name') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('email')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Email</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                                @if ($errors->has('email'))
                                    <label for="email" class="error">{{ $errors->first('email') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('phone')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Phone</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="phone" name="phone" value="{{old('phone')}}">
                                @if ($errors->has('phone'))
                                    <label for="phone" class="error">{{ $errors->first('phone') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('gender')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputSuccess">Gender</label>
                            <div class="col-md-6">

                                <div class="radio-custom checkbox-inline">
                                    <input type="radio" id="maleg_ender" name="gender" value="Male">
                                    <label for="maleg_ender">Male</label>
                                </div>

                                <div class="radio-custom checkbox-inline">
                                    <input type="radio" id="female_gender" name="gender" value="Female">
                                    <label for="female_gender">Female</label>

                                </div>

                            </div>
                            @if ($errors->has('gender'))
                                <label for="gender" class="error">{{ $errors->first('gender') }}</label>
                            @endif
                        </div>

                        <div class="form-group @if ($errors->has('address')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Address</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="address" name="address" value="{{old('address')}}">
                                @if ($errors->has('address'))
                                    <label for="address" class="error">{{ $errors->first('address') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('country_id')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Country</label>
                            <div class="col-md-6">
                                <select class="form-control mb-md" id="country_id" name="country_id" v-model="country_id">
                                    <option value="">Select Country</option>
                                    <option  v-for="country in countries" :value="country.id">@{{country.name}}</option>
                                </select>
                                @if ($errors->has('country_id'))
                                    <label for="country_id" class="error">{{ $errors->first('country_id') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('state_id')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">State</label>
                            <div class="col-md-6">
                                {{--<select class="form-control mb-md" id="state_id" name="state_id" v-model="state_id" @change="getCities">--}}
                                    {{--<option value="">Select State</option>--}}
                                    {{--<option  v-for="state in states" :value="state.id">@{{state.state_name}}</option>--}}
                                {{--</select>--}}

                                <input type="text" class="form-control" id="state_id" name="state_id" value="{{old('state_id')}}">

                            @if ($errors->has('state_id'))
                                    <label for="state_id" class="error">{{ $errors->first('state_id') }}</label>
                                @endif
                            </div>
                        </div>


                        <div class="form-group @if ($errors->has('city_id')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">City</label>
                            <div class="col-md-6">
                                {{--<select class="form-control mb-md" id="city_id" name="city_id">--}}
                                    {{--<option value="">Select City</option>--}}
                                    {{--<option  v-for="city in cities" :value="city.id">@{{city.city_name}}</option>--}}
                                {{--</select>--}}

                            <input type="text" class="form-control" id="city_id" name="city_id" value="{{old('city_id')}}">

                            @if ($errors->has('city_id'))
                                    <label for="city_id" class="error">{{ $errors->first('city_id') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group @if ($errors->has('zip_code')) has-error  @endif">
                            <label class="col-md-3 control-label" for="inputDefault">Zip Code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{old('zip_code')}}">
                                @if ($errors->has('zip_code'))
                                    <label for="zip_code" class="error">{{ $errors->first('zip_code') }}</label>
                                @endif
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

        const oapp = new Vue({
            el:'#app',
            data:{
                countries:@json($countries),
                pickup_charges: [{weight:0.0,amount:0.0}],
                states:null,
                cities:null,
                country_id:"",
                state_id:"",
            },
            created(){
                //console.log(this.countries);
            },


            methods: {
                addPickup(){

                  this.pickup_charges.push({weight:0,amount:0})
                },
                removePickup(idx){
                    this.pickup_charges.splice(idx, 1)
                },
                getStates(){
                    if(parseInt(this.country_id) > 0 ){
                        let state_url = '/api/getStates?country_id='+this.country_id;
                        axios.get(state_url).then(response => (this.states = response.data));
                    }

                },
                getCities(){
                    if(parseInt(this.state_id) > 0 ){
                        let city_url = '/api/getCities?state_id='+this.state_id;
                        axios.get(city_url).then(response => (this.cities = response.data));
                    }
                }

            },

            computed: {

            }

        });

    </script>

@endsection
