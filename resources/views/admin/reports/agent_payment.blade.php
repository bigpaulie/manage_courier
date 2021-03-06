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


                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Agent Name</label>
                        <select  class="form-control populate" id="agentSelect" name="user_id">

                        </select>
                    </div>
                </div>

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



                <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" @click="filterAgentReport" style="margin-top: 25px;"><i class="fa fa-search"></i> Search</button>
                        <button type="button" class="btn btn-primary" @click="downloadAgentReport" style="margin-top: 25px;"><i class="fa fa-download"></i> Download</button>

                    </div>
                </div>

                <div class="col-md-3"  v-if="typeof agent_payments != 'undefined' ">
                    <p><b>Total Amount(Dr.) :@{{all_total_amount}}</b></p>
                    <p><b>Total Paid Amount(Cr.) :@{{all_total_paid_amount}}</b></p>
                    <p><b>Remaining :@{{all_remaining_amount}}</b></p>
                </div>

            </div>
        </div>
    </section>


    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Reports</h2>
        </header>
        <div class="panel-body">

            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th class="">PaymentDate</th>
                    <th class="">Agent Name</th>
                    <th>Total Amount(Dr.)</th>
                    <th class="">Total Paid Amount(Cr.)</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(ap, index) in agent_payments">
                    <!--  <td data-title="Name">@{{ex.user.name}}</td> -->
                    <td data-title="Date">@{{ap.payment_date}} </td>
                    <td data-title="Agent Name">@{{ap.agent.name | capitalize}}</td>

                    <td data-title="Total Amount(Dr.)"><span v-if="ap.total">@{{ap.total}}</span></td>
                    <td data-title="Total Paid Amount(Cr.)"><span v-if="ap.amount">@{{ap.amount}}</span></td>

                </tr>

                <tr v-if="typeof agent_payments != 'undefined' && agent_payments.length > 0">
                    <td colspan="2">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Dr.) @{{total_amount}}</strong></label>
                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Cr.) @{{total_paid_amount}}</strong></label>

                    </td>


                </tr>

                <tr v-if="typeof agent_payments != 'undefined' && agent_payments.length > 0">
                    <td colspan="3">

                    </td>



                    <td>
                        <label><strong class="text-primary">Total Remaining @{{remaining_amount}}</strong></label>

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

        var logged_user_id = "{{$user_id}}";
        var apiUrl = "/api/get_store_agent";

        jQuery(document).ready(function($) {
            $("#agentSelect").select2({
                placeholder: "Search Agent Name",
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




        Vue.filter('exists', function (value) {
            if (!value) return 'NA'

            return value;
        });

        Vue.filter('capitalize', function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        });



        const oapp = new Vue({
            el:'#app',

            data:{
                agent_payments:{},
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",
                looged_user_id:"{{$user_id}}",
                user_type:"{{$user_type}}",
                all_total_amount:"",
                all_total_paid_amount:"",
                all_remaining_amount:"",

            },
            created(){

                // let searchURL = '/api/getAgentPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                // searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                // axios.get(searchURL).then(response => {
                // this.agent_payments = response.data.agent_payment_data;
                // this.total_amount = response.data.total_amount;
                // this.total_paid_amount = response.data.total_paid_amount;
                // this.remaining_amount = response.data.remaining_amount;

            // });
            },


            methods: {


                filterAgentReport(page=1){

                    var agent_id = $("#agentSelect").val();

                    let searchURL = '/api/getAgentPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date+'&agent_id='+agent_id;
                    axios.get(searchURL).then(response => {
                        this.agent_payments = response.data.agent_payment_data;
                    this.total_amount = response.data.total_amount;
                    this.total_paid_amount = response.data.total_paid_amount;
                    this.remaining_amount = response.data.remaining_amount;
                    this.all_total_amount = response.data.all_total_amount;
                    this.all_total_paid_amount = response.data.all_total_paid_amount;
                    this.all_remaining_amount = response.data.all_remaining_amount;
                });


                },

                downloadAgentReport(){

                    var agent_id = $("#agentSelect").val();

                    let searchURL = '/admin/downloadAgentPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date+'&agent_id='+agent_id;

                    window.location.href=searchURL;

                },




            },

            computed: {


            },




        });


    </script>



@endsection
