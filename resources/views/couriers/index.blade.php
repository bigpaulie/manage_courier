@extends('layouts.admin')
@section('date-styles')
{!! Html::style("/assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css") !!}
<link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>


@endsection

@section('content')

    <header class="page-header">
        <h2>Manage Couriers</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
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
    <section class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">From Date</label>
                    <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <date-picker v-model="from_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>

                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">End Date</label>
                    <div class="input-group mb-md">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <date-picker v-model="end_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Status</label>
                    <select class="form-control" v-model="status_id">
                        <option value="">Select Status</option>
                        @foreach($status as $statu)
                          <option value="{{$statu->id}}" style="color: {{$statu->color_code}}">{{$statu->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>


            @if(Auth::user()->user_type == 'admin')
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Agent/Store Name</label>
                        <select  class="form-control populate" id="userSelect" name="user_id">

                        </select>
                    </div>
                </div>
            @endif

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Traking Number</label>
                    <input type="text"  class="form-control" name="tracking_number" v-model="traking_number" v-on:keyup.enter="onEnter">
                </div>
            </div>
            @if(Auth::user()->user_type == 'store')

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Agent Name</label>
                        <select  class="form-control populate" id="agentSelect" name="agent_id">

                        </select>
                    </div>
                </div>
            @endif


            @if(Auth::user()->user_type == 'agent')
            <div class="col-md-2">
            <div class="form-group">
            <label class="control-label text-bold">Total Charge: <span class="text-primary">{{$total_amount}}</span></label>
            </div>
            <div class="form-group">
            <label class="control-label text-bold">Total Paid Amount: <span class="text-primary">{{$paid_amount}}</span></label>
            </div>
            <div class="form-group">
            <label class="control-label text-bold">Grand Total: <span class="@if($remaining > 0) text-danger @else text-success @endif">{{$remaining}}</span></label>
            </div>
            </div>
            @endif

        </div>
        <div class="row">


            <div class="col-md-6">
                <div class="form-group">
                    <button type="button" class="btn btn-success" @click="searchCouriers"><i class="fa fa-search"></i> Search</button>
                    <button type="button" class="btn btn-warning" @click="createCourierCsv"><i class="fa fa-download"></i> Export</button>
                    <button type="button" class="btn btn-reset" @click="resetFilter"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>

        </div>
    </div>
    </section>
    @if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'store')
      <section class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('couriers.import-csv')}}" class="form-horizontal form-bordered"  enctype="multipart/form-data" method="POST">
                        {{csrf_field()}}
                         <div class="form-group">
                        <label class="col-md-2 control-label">Upload Courier CSV</label>
                        <div class="col-md-5">
                            <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden">
                                <div class="input-append">
                                    <div class="uneditable-input">
                                        <i class="fa fa-file fileupload-exists"></i>
                                        <span class="fileupload-preview"></span>
                                    </div>
                                    <span class="btn btn-default btn-file">
																<span class="fileupload-exists">Change</span>
																<span class="fileupload-new">Select CSV</span>
																<input type="file" name="courier_csv">
															</span>
                                    <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import CSV</button>

                        </div>
                    </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
    @endif

    @if(Auth::user()->user_type == 'agent' || Auth::user()->user_type == 'store')
        {{--<section class="panel">--}}
            {{--<div class="panel-body">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}

                        {{--<form class="form-inline">--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="" for="exampleInputUsername2">Pickup/Drop</label>--}}
                                {{--<select class="form-control" v-model="pickup_status">--}}
                                    {{--<option value="">Select Pickup/Drop</option>--}}
                                    {{--<option value="drop">Drop</option>--}}
                                    {{--<option value="pickup">Pickup</option>--}}

                                {{--</select>--}}
                            {{--</div>--}}

                            {{--<div class="clearfix visible-xs mb-sm"></div>--}}

                            {{--<button type="button" class="btn btn-primary" @click="changeCourierStatus">Submit</button>--}}
                        {{--</form>--}}

                    {{--</div>--}}

                {{--</div>--}}

            {{--</div>--}}
        {{--</section>--}}
    @endif

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
                    <th>Customer Name</th>
                    <th class="text-right">T-Id</th>
                    <th class="text-right">Status</th>
                    <th class="text-right">Shipped</th>
                    <th class="text-right hidden-xs hidden-sm">Country</th>
                    <th>Paid Amount</th>
                    {{--<th>Remaining</th>--}}
                    <th>Total</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>


                <tr v-for="(courier, index) in couriers.data">
                    <td><input type="checkbox" @click="select" class="checkbox-custom chekbox-primary" v-model="courierIds" :value="courier.id"></td>
                    <td data-title="Id"><a href="javascript:void(0);"  @click="showCourier(courier.id)" >@{{courier.unique_name}}</a></td>
                    <td data-title="Agent Name" v-if="user_type == 'admin'">@{{courier.agent.name}}</td>
                    <td data-title="Customer Name">@{{ courier.r_name }}</td>
                    <td data-title="Traking Number" class="text-right">
                        <span v-if="courier.tracking_no == null">NA</span>
                        <span v-if="courier.tracking_no != null"><a href="javascript:void(0);">@{{courier.tracking_no}}</a></span>

                    </td>
                    <td data-title="Status" class="text-right hidden-xs hidden-sm">
                        {{--@if(Auth::user()->user_type == 'admin')--}}
                        {{--<select class="form-control" v-bind:style="{ color:courier.status.color_code  }" v-model="courier.status.id" @change="createCharge(courier)" >--}}
                            {{--<option value="">Select Status</option>--}}
                            {{--<option v-for=" (status, key) in status_master" :value="status.id" v-bind:style="{ color:status.color_code  }">@{{ status.name }}</option>--}}
                        {{--</select>--}}
                        {{--@endif--}}

                        <span v-bind:style="{ color:courier.status.color_code  }">@{{ courier.status.name }}</span>

                    </td>
                    <td data-title="Shipped">
                        <span v-if="courier.courier_charge != null && courier.courier_charge.courier_service != null">@{{courier.courier_charge.courier_service.name | exists }}</span>
                        <span v-if="courier.courier_charge == null || courier.courier_charge.courier_service == null">NA</span>

                    </td>
                    <td data-title="Country" class="text-right hidden-xs hidden-sm">@{{courier.receiver_country.name }}</td>
                    <td data-title="Amount"><span v-if="courier.courier_payment != null">@{{courier.courier_payment.pay_amount | exists }}</span> <span v-if="courier.courier_payment == null">NA</span></td>
                    {{--<td data-title="Pickup Charge"><span v-if="courier.courier_payment != null">@{{courier.courier_payment.remaining | exists }}</span><span v-if="courier.courier_payment == null">NA</span></td>--}}
                    <td data-title="Total"><span v-if="courier.courier_payment != null">@{{courier.courier_payment.total | exists }}</span><span v-if="courier.courier_payment == null">NA</span></td>
                    <td data-title="Actions" class="text-right actions">



                        <a href="javascript:void(0);" @click="editCourier(courier.id)" class=""><i class="fa fa-pencil"></i></a>
                        <a href="javascript:void(0);" @click="showCourier(courier.id)" class=""><i class="fa fa-eye"></i></a>
                        <a href="javascript:void(0);" @click="courierReport(courier.id)" class=""><i class="fa fa-file-excel-o"></i></a>
                        @if(Auth::user()->user_type == 'admin')
                            <a href="javascript:void(0);" @click="deleteCourier(courier.id)" class=""><i class="fa fa-trash-o"></i></a>
                        @endif


                    </td>
                </tr>

                <tr v-if="typeof couriers.data != 'undefined' && couriers.data.length == 0"><td colspan="12">
                        <div class="alert alert-danger">
                            <strong>Oh snap!</strong> No Couriers Found.
                        </div>
                    </td>
                </tr>
                <?php $user_type = Auth::user()->user_type;
                        $colspan=7;
                      if($user_type == 'admin'){
                          $colspan =8;
                      }
                ?>

                <tr v-if="typeof couriers.data != 'undefined' && couriers.data.length > 0">
                    <td colspan="{{$colspan}}">

                    </td>
                    <td>
                        <label><strong class="text-primary">Total Paid Amount: @{{total_paid_amount}}</strong></label>
                    </td>
                    {{--<td>--}}
                        {{--<label><strong class="text-primary">Total Remaining: @{{total_remaining}}</strong></label>--}}

                    {{--</td>--}}

                    <td>
                        <label><strong class="text-primary">Total: @{{total}}</strong></label>

                    </td>
                    <td>

                    </td>

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

            $("#userSelect").select2({
                placeholder: "Search Store Name",
                allowClear: true,
                minimumInputLength:2,
                ajax: {
                    url: "/api/get_user_name",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            var logged_user_id = "{{Auth::user()->id}}";

            var apiUrl = "/api/get_store_agent?user_store_id="+logged_user_id;
            $("#agentSelect").select2({
                placeholder: "Search Agent",
                allowClear: true,
                minimumInputLength:2,
                ajax: {
                    url: apiUrl,
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

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
                total_paid_amount:"NA",
                total_remaining:"NA",
                total:"NA",
                pickup_status:""

            },
            created(){
                let searchURL = '/api/getCouriers?type=all&user_type='+this.user_type+'&user_id='+this.user_id;
                axios.get(searchURL).then(response => {
                    this.couriers = response.data.courier_data;
                    this.total_paid_amount = response.data.total_paid_amount;
                    this.total_remaining = response.data.total_remaining;
                    this.total = response.data.total;
                });

            },


            methods: {
                selectAll: function() {
                    this.courierIds = [];
                    this.allSelected = !this.allSelected;
                    if (this.allSelected) {

                        for (courier in this.couriers.data) {
                            this.courierIds.push(this.couriers.data[courier].id.toString());
                        }
                    }
                },


                select: function() {
                    this.allSelected = false;
                },

                searchCouriers(page = 1){

                    this.filter_type="filter";
                    if(this.user_type == 'admin'){
                        var agent_id = $("#userSelect").val();

                    }else if(this.user_type =='store'){
                        var agent_id = $("#agentSelect").val();

                    }

                    let searchURL = '/api/getCouriers?page='+page+'&type=filter&traking_number='+this.traking_number;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                    searchURL+='&agent_name='+agent_id+'&status_id='+this.status_id
                    searchURL+='&user_type='+this.user_type+'&user_id='+this.user_id
                    axios.get(searchURL).then(response => {
                        this.couriers = response.data.courier_data;
                            this.total_paid_amount = response.data.total_paid_amount;
                            this.total_remaining = response.data.total_remaining;
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

                deleteCourier(id){

                    var status= confirm('Are you sure want to delete this courier?');
                    if(status == true){

                        window.location.href ="/"+this.user_type+"/couriers/delete/"+id;

                    }else{
                        return false;
                    }

                },

                generateBarocode(id){
                    window.location.href ="/"+this.user_type+"/generate_barcode/"+id;

                },
                courierReport(id){
                    window.location.href ="/"+this.user_type+"/courier_report/"+id;
                },

                showCourier(id){
                    window.location.href ="/"+this.user_type+"/couriers/"+id;
                },

                createCourierCsv(page){

                    // var agent_id = $("#userSelect").val();
                    // let csvURL = '/admin/create_courier_csv?page='+page+'&type='+this.filter_type+'&traking_number='+this.traking_number;
                    // csvURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                    // csvURL+='&agent_name='+agent_id+'&status_id='+this.status_id
                    // csvURL+='&user_type='+this.user_type+'&user_id='+this.user_id


                    if(this.user_type == 'admin'){
                        var agent_id = $("#userSelect").val();

                    }else if(this.user_type =='store'){
                        var agent_id = $("#agentSelect").val();

                    }

                    let csvURL = '/admin/create_courier_csv?page='+1+'&type='+this.filter_type+'&traking_number='+this.traking_number;
                    csvURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                    csvURL+='&agent_name='+agent_id+'&status_id='+this.status_id
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
                changeCourierStatus:function(){
                        if(this.courierIds.length > 0){
                            var pickup_status = this.pickup_status;
                            if(pickup_status != ""){

                                var pickup_data ={courierIds:this.courierIds,pickup_status:pickup_status};
                                axios.post('/api/update_pickup_status', pickup_data)
                                    .then(function (response) {
                                        window.location.reload();
                                    })
                                    .catch(function (error) {
                                        //currentObj.output = error;
                                    });

                            }else{
                                alert("Please Select Pickup/Drop status.");
                            }

                        }else{
                            alert("Please Select Courier");
                        }
                }



            },

            computed: {

                totalCharge: function () {
                    return parseFloat(this.selectedCourier.amount) + parseFloat(this.selectedCourier.pickup_charge)
                }
            },




        });



    </script>

@endsection
