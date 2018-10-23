@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Profile</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Agents</span></li>
                <li><span>{{$profile->name}}</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">


                    <h2 class="panel-title">{{$profile->name}}</h2>
                </header>
                <div class="panel-body">
                    {!! Form::model($profile,['method' => 'PATCH', 'action' => ['AgentController@update', $profile->id ] ]) !!}
                    {{csrf_field()}}
                    <div class="form-group @if ($errors->has('company_name')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Company Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{$profile->profile->company_name}}">
                            @if ($errors->has('company_name'))
                                <label for="company_name" class="error">{{ $errors->first('company_name') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group @if ($errors->has('first_name')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">First Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{$profile->profile->first_name}}">
                            @if ($errors->has('first_name'))
                                <label for="first_name" class="error">{{ $errors->first('first_name') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if ($errors->has('last_name')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Last Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{$profile->profile->last_name}}">
                            @if ($errors->has('last_name'))
                                <label for="last_name" class="error">{{ $errors->first('last_name') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group  @if ($errors->has('email')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Email</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="email" name="email" value="{{$profile->email}}">
                            @if ($errors->has('email'))
                                <label for="email" class="error">{{ $errors->first('email') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if ($errors->has('phone')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Phone</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="{{$profile->profile->phone}}">
                            @if ($errors->has('phone'))
                                <label for="phone" class="error">{{ $errors->first('phone') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if ($errors->has('gender')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputSuccess">Gender</label>
                        <div class="col-md-6">

                            <div class="radio-custom checkbox-inline">
                                <input type="radio" id="maleg_ender" name="gender" value="Male" @if($profile->profile->gender == 'Male') checked @endif>
                                <label for="maleg_ender">Male</label>
                            </div>

                            <div class="radio-custom checkbox-inline">
                                <input type="radio" id="female_gender" name="gender" value="Female" @if($profile->profile->gender == 'Female') checked @endif>
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
                            <input type="text" class="form-control" id="address" name="address" value="{{$profile->profile->address}}">
                            @if ($errors->has('address'))
                                <label for="address" class="error">{{ $errors->first('address') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if ($errors->has('country_id')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">Country</label>
                        <div class="col-md-6">
                            <select class="form-control mb-md" id="country_id" name="country_id" v-model="country_id" @change="getStates">
                                <option value="0">Select Country</option>
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
                            <select class="form-control mb-md" id="state_id" name="state_id" v-model="state_id" @change="getCities">
                                <option value="0">Select State</option>
                                <option  v-for="state in states" :value="state.id">@{{state.state_name}}</option>
                            </select>
                            @if ($errors->has('state_id'))
                                <label for="state_id" class="error">{{ $errors->first('state_id') }}</label>
                            @endif
                        </div>
                    </div>


                    <div class="form-group @if ($errors->has('city_id')) has-error  @endif">
                        <label class="col-md-3 control-label" for="inputDefault">City</label>
                        <div class="col-md-6">
                            <select class="form-control mb-md" id="city_id" name="city_id" v-model="city_id">
                                <option value="0">Select City</option>
                                <option  v-for="city in cities" :value="city.id">@{{city.city_name}}</option>

                            </select>
                            @if ($errors->has('city_id'))
                                <label for="city_id" class="error">{{ $errors->first('city_id') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="inputDefault">Pickup Charges</label>
                        <div class="col-md-9" >
                            <div class="col-md-9" v-for="charge, idx in pickup_charges">
                                <label class="col-md-1 control-label" for="inputDefault">Weight</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" :name="'pickup_charge[' + [idx]+'][weight]'">
                                </div>

                                <label class="col-md-1 control-label" for="inputDefault">Amount</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control"  :name="'pickup_charge[' + [idx]+'][amount]'">
                                </div>
                                <div class="col-md-3" v-if="idx > 0">
                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" @click="removePickup(idx)">Remove</button>
                                </div>

                            </div>


                        </div>

                    </div>

                    <div class="col-md-3 pull-right">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" @click="addPickup">Add More</button>
                    </div>

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
                states:@json($states),
                cities:@json($cities),
                country_id:'{{$profile->profile->country_id}}',
                state_id:'{{$profile->profile->state_id}}',
                city_id:'{{$profile->profile->city_id}}'
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
