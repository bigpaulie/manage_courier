@extends('layouts.admin')
@section('date-styles')

@endsection

@section('content')

    <header class="page-header">
        <h2>Manage Reports</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Reports</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
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

                        </div>
                    </div>
                </div>



                <div class="col-md-3">
                    <div class="form-group">
                       <button type="button" class="btn btn-success" @click="searchCouriers"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Couriers</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th class="text-right">Id</th>
                    <th>Agent Name</th>
                    <th>Customer Name</th>
                    <th class="text-right">T-Id</th>
                    <th class="text-right">Status</th>
                    <th class="text-right">Shipped</th>
                    <th class="text-right hidden-xs hidden-sm">Country</th>
                    <th>Amount</th>
                    <th>Pickup Charge</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(courier, index) in couriers.data">
                    <td data-title="Id">@{{courier.unique_name}}</td>
                    <td data-title="Agent Name">@{{courier.agent.name}}</td>
                    <td data-title="Customer Name">@{{ courier.r_name }}</td>
                    <td data-title="Traking Number" class="text-right">
                        <span v-if="courier.tracking_no == null">NA</span>
                        <span v-if="courier.tracking_no != null"><a href="javascript:void(0);">@{{courier.tracking_no}}</a></span>

                    </td>

                    <td data-title="Status" class="text-right hidden-xs hidden-sm">
                            <span v-bind:style="{ color:courier.status.color_code  }">@{{ courier.status.name }}</span>
                    </td>

                    <td data-title="Shipped">
                        <span v-if="courier.courier_charge != null && courier.courier_charge.courier_service != null">@{{courier.courier_charge.courier_service.name | exists }}</span>
                        <span v-if="courier.courier_charge == null || courier.courier_charge.courier_service == null">NA</span>

                    </td>

                    <td data-title="Country" class="text-right hidden-xs hidden-sm">@{{courier.receiver_country.name }}</td>
                    <td data-title="Amount"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.amount | exists }}</span> <span v-if="courier.courier_charge == null">NA</span></td>
                    <td data-title="Pickup Charge"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.pickup_charge | exists }}</span><span v-if="courier.courier_charge == null">NA</span></td>
                    <td data-title="Total"><span v-if="courier.courier_charge != null">@{{courier.courier_charge.total | exists }}</span><span v-if="courier.courier_charge == null">NA</span></td>

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


                </tr>



                </tbody>
            </table>
        </div>



    </section>
    <!-- end: page -->


@endsection



@section('scripts')
    <script type="text/javascript">


        Vue.filter('exists', function (value) {
            if (!value) return 'NA'

            return value;
        });


        const oapp = new Vue({
            el:'#app',

            data:{
                couriers:{},
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",
                user_id:"{{$user_id}}"

            },
            created(){


            },


            methods: {


                searchCouriers(page=1){

                    var page_no =1;

                    let searchURL = '/api/generate_report?page='+page_no+'&user_id='+this.user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date

                    axios.get(searchURL).then(response => {
                    this.couriers = response.data.courier_data;
                    this.total_amount = response.data.total_amount;
                    this.total_charge = response.data.total_pickup_charge;
                    this.total = response.data.total;
                });

                },



            },

            computed: {


            },




        });


    </script>


@endsection
