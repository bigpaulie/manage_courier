@extends('layouts.admin')

@section('content')

    <header class="page-header">
        <h2>Manage Couriers</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Couriers</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    @if (Session::has('message'))
    <div class="alert alert-success">
       <strong> {{ Session::get('message') }}</strong>
    </div>
    @endif
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">From Date</label>
                    <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <date-picker v-model="from_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>

                        {{--<input type="text" data-plugin-datepicker class="form-control" name="from_date" v-model="from_date">--}}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">End Date</label>
                    <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <date-picker v-model="end_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>
                        {{--<input type="text" data-plugin-datepicker class="form-control" name="end_date" v-model="end_date">--}}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Status</label>
                    {{--{!! Form::select('status', $status, old('status'), ['class'=>'form-control','placeholder' => 'Select Status','v-model'=>'status_id']); !!}--}}
                    <select class="form-control" v-model="status_id">
                        <option value="">Select Status</option>
                        @foreach($status as $statu)
                          <option value="{{$statu->id}}" style="color: {{$statu->color_code}}">{{$statu->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            @if(Auth::user()->user_type == 'admin')
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Agent Name</label>
                    <input type="text"  class="form-control" name="agent_name" v-model="agent_name" v-on:keyup.enter="onEnter">
                </div>
            </div>
            @endif
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Traking Number</label>
                    <input type="text"  class="form-control" name="tracking_number" v-model="traking_number" v-on:keyup.enter="onEnter">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <button type="button" class="btn btn-success" @click="searchCouriers"><i class="fa fa-search"></i> Search</button>
                    <button type="button" class="btn btn-warning" @click="createCourierCsv"><i class="fa fa-download"></i> Export</button>
                    <button type="button" class="btn btn-reset" @click="resetFilter"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>

        </div>

        </div>

    <section class="panel">
        <header class="panel-heading">

                <a href="{{url(Auth::user()->user_type.'/couriers/create')}}" class="btn btn-primary pull-right">Create Courier</a>
                <h2 class="panel-title">Manage Couriers</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th><input type="checkbox" class="checkbox-custom chekbox-primary"  @click="selectAll" v-model="allSelected"></th>
                    <th class="text-right">Id</th>
                    <th v-if="user_type == 'admin'">Agent Name</th>
                    <th class="text-right">T-Id</th>
                    <th class="text-right">Status</th>
                    <th class="text-right">Shipped</th>
                    <th class="text-right hidden-xs hidden-sm">Pickup/Drop</th>
                    <th>Amount</th>
                    <th>Pickup Charge</th>
                    <th>Total</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>


                <tr v-for="(courier, index) in couriers.data">
                    <td><input type="checkbox" @click="select" class="checkbox-custom chekbox-primary" v-model="courierIds" :value="courier.id"></td>
                    <td data-title="Id">@{{courier.id}}</td>
                    <td data-title="Agent Name" v-if="user_type == 'admin'">@{{courier.agent.name}}</td>
                    <td data-title="Traking Number" class="text-right">
                        <span v-if="courier.tracking_no == null">NA</span>
                        <span v-if="courier.tracking_no != null"><a href="javascript:void(0);">@{{courier.tracking_no}}</a></span>

                    </td>
                    <td data-title="Status" class="text-right hidden-xs hidden-sm">
                        @if(Auth::user()->user_type == 'admin')
                        <select class="form-control" v-bind:style="{ color:courier.status.color_code  }" v-model="courier.status.id" @change="createCharge(courier)" >
                            <option value="">Select Status</option>
                            <option v-for=" (status, key) in status_master" :value="status.id" v-bind:style="{ color:status.color_code  }">@{{ status.name }}</option>
                        </select>
                        @endif
                        @if(Auth::user()->user_type == 'agent')
                                <span v-bind:style="{ color:courier.status.color_code  }">@{{ courier.status.name }}</span>
                        @endif
                    </td>
                    <td data-title="Shipped">
                        <span v-if="courier.courier_charge != null && courier.courier_charge.courier_service != null">@{{courier.courier_charge.courier_service.name | exists }}</span>
                        <span v-if="courier.courier_charge == null || courier.courier_charge.courier_service == null">NA</span>

                    </td>
                    <td data-title="Pickup/Drop" class="text-right hidden-xs hidden-sm">@{{courier.shippment.courier_status | capitalize}}</td>
                    <td data-title="Amount"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.amount | exists }}</span> <span v-if="courier.courier_charge == null">NA</span></td>
                    <td data-title="Pickup Charge"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.pickup_charge | exists }}</span><span v-if="courier.courier_charge == null">NA</span></td>
                    <td data-title="Total"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.total | exists }}</span><span v-if="courier.courier_charge == null">NA</span></td>
                    <td data-title="Actions" class="text-right actions">

                        {{--@if(Auth::user()->user_type == 'admin')--}}
                        {{--{!! Form::model($courier,['method' => 'DELETE', 'action' => ['CourierController@destroy', $courier->id ], 'id'=>'frmdeletecourier_'.$courier->id ]) !!}--}}
                          {{--<button class="delete-row" type="button" onclick="deleteCourier('{{$courier->id}}')"><i class="fa fa-trash-o"></i></button>--}}
                        {{--{!! Form::close() !!}--}}
                        {{--@endif--}}

                        <a href="javascript:void(0);" @click="editCourier(courier.id)" class=""><i class="fa fa-pencil"></i></a>


                    </td>
                </tr>

                <tr v-if="typeof couriers.data != 'undefined' && couriers.data.length == 0"><td colspan="11">
                        <div class="alert alert-danger">
                            <strong>Oh snap!</strong> No Couriers Found.
                        </div>
                    </td>
                </tr>

                <tr v-if="typeof couriers.data != 'undefined' && couriers.data.length > 0">
                    <td colspan="7">

                    </td>
                    <td>
                        <label><strong class="text-primary">Total Amount: @{{total_amount}}</strong></label>
                    </td>
                    <td>
                        <label><strong class="text-primary">Total Charge: @{{total_charge}}</strong></label>

                    </td>

                    <td>
                        <label><strong class="text-primary">Total: @{{total}}</strong></label>

                    </td>
                    {{--<td>--}}

                    {{--</td>--}}

                </tr>

                </tbody>
            </table>
        </div>

        <div class="pull-right">
            <pagination :data="couriers" @pagination-change-page="searchCouriers">
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
            </pagination>
        </div>


    </section>
    <!-- end: page -->

   @include('couriers.charge_modal')
   @include('couriers.shipped_modal')



@endsection



@section('scripts')

    <script>


        $(document).on('click', '.modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });
         function deleteCourier(courier_id){

             var status= confirm('Are you sure want to delete this courier?');
             if(status == true){

                 event.preventDefault();
                 document.getElementById('frmdeletecourier_'+courier_id).submit();
                 return true;
             }else{
                 return false;
             }


        }

        jQuery(document).ready(function($) {

        });

        Vue.filter('capitalize', function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        });

        Vue.filter('exists', function (value) {
            if (!value) return 'NA'

            return value;
        });

        const oapp = new Vue({
            el:'#app',

            data:{
                couriers:{},
                status_master: @json($status),
                cc_master: @json($courier_companies),
                user_type:"{{Auth::user()->user_type}}",
                user_id:"{{Auth::user()->id}}",
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",
                agent_name:'',
                traking_number:'',
                status_id:'',
                selected: [],
                allSelected:false,
                courierIds:[],
                selectedCourier:{},
                accepted_status_id:"{{$accepted_status_id}}",
                shipped_status_id:"{{$shipped_status_id}}",
                filter_type:"all",
                total_amount:"NA",
                total_charge:"NA",
                total:"NA",

            },
            created(){
                let searchURL = '/api/getCouriers?type=all&user_type='+this.user_type+'&user_id='+this.user_id;
                axios.get(searchURL).then(response => {
                    this.couriers = response.data.courier_data;
                    this.total_amount = response.data.total_amount;
                    this.total_charge = response.data.total_pickup_charge;
                    this.total = response.data.total;
                });

            },


            methods: {
                selectAll: function() {
                    this.courierIds = [];
                    this.allSelected = !this.allSelected;
                    if (this.allSelected) {

                        for (courier in this.couriers) {
                            this.courierIds.push(this.couriers[courier].id.toString());
                        }
                    }
                },


                select: function() {
                    this.allSelected = false;
                },

                searchCouriers(page = 1){

                    this.filter_type="filter";

                    let searchURL = '/api/getCouriers?page='+page+'&type=filter&traking_number='+this.traking_number;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                    searchURL+='&agent_name='+this.agent_name+'&status_id='+this.status_id
                    searchURL+='&user_type='+this.user_type+'&user_id='+this.user_id
                    axios.get(searchURL).then(response => {
                        this.couriers = response.data.courier_data;
                        this.total_amount = response.data.total_amount;
                        this.total_charge = response.data.total_pickup_charge;
                        this.total = response.data.total;
                    });

                },

                resetFilter(){
                    this.from_date="{{date('m/d/Y')}}";
                    this.end_date="{{date('m/d/Y')}}";
                    this.agent_name='';
                    this.traking_number='';
                    this.status_id='';
                    this.searchCouriers();
                },

                editCourier(id){

                    window.location.href ="/"+this.user_type+"/couriers/"+id+"/edit";
                },

                createCourierCsv(page=1){

                    let csvURL = '/admin/create_courier_csv?page='+page+'&type='+this.filter_type+'&traking_number='+this.traking_number;
                    csvURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                    csvURL+='&agent_name='+this.agent_name+'&status_id='+this.status_id
                    csvURL+='&user_type='+this.user_type+'&user_id='+this.user_id
                    window.location.href=csvURL;

                },
                onEnter: function() {
                   this.searchCouriers();
                },
                createCharge(courier){

                    if(courier.status.id == this.accepted_status_id){
                            let c_data={};
                            c_data.courier_id =courier.id;
                            c_data.weight =courier.shippment.weight;
                            c_data.is_pickup =courier.shippment.courier_status;
                            c_data.amount=0;
                            c_data.pickup_charge=0;
                            c_data.status_id = courier.status.id,
                            c_data.status_code_name = 'accepted',
                            c_data.user_id=courier.user_id
                            this.selectedCourier=c_data;

                        $.magnificPopup.open({
                            items: {
                                src: '#chargeModal'
                            },
                            type: 'inline'
                        });
                    }

                    else if(courier.status.id == this.shipped_status_id){

                        let c_data={};
                        c_data.courier_id =courier.id;
                        c_data.delivery_date ="{{date('m/d/Y')}}";
                        c_data.status_id = courier.status.id,
                        c_data.user_id=courier.user_id,
                        c_data.status_code_name = 'shipped',
                        this.selectedCourier=c_data;
                        $.magnificPopup.open({
                            items: {
                                src: '#shippedModal'
                            },
                            type: 'inline'
                        });
                    }else{

                        let status_data={};
                        status_data.courier_id =courier.id;
                        status_data.status_id = courier.status.id,
                        axios.post('/api/update_courier_status', status_data)
                            .then(function (response) {

                            })
                            .catch(function (error) {
                                //currentObj.output = error;
                            });
                        this.searchCouriers();
                    }

                },
                savePayment(){
                        this.selectedCourier.total_charge =this.totalCharge;
                        var charge_data = this.selectedCourier;
                        axios.post('/api/save_courier_charge', charge_data)
                            .then(function (response) {

                            })
                            .catch(function (error) {
                                //currentObj.output = error;
                            });
                   // e.preventDefault();
                    this.searchCouriers();
                    $.magnificPopup.close();

                },



            },

            computed: {

                totalCharge: function () {
                    return parseFloat(this.selectedCourier.amount) + parseFloat(this.selectedCourier.pickup_charge)
                }
            },




        });



    </script>

@endsection
