@extends('layouts.admin')
@section('date-styles')
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection

@section('content')

    <header class="page-header">
        <h2>Agent Payment Reports</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Agent Payment</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
        <div class="panel-body">
            <div class="row">


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Agent Name</label>
                        <select  class="form-control populate" id="userSelect" name="user_id">

                        </select>
                    </div>
                </div>

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

            <h2 class="panel-title">Reports</h2>
        </header>
        <div class="panel-body">
            <h2>Comming Soon</h2>
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
