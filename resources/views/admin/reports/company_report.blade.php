@extends('layouts.admin')
@section('date-styles')

@endsection

@section('content')

    <header class="page-header">
        <h2>Company Reports</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Company</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
        <div class="panel-body">
            <div class="row">


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Company</label>
                        {!! Form::select('company_id', $companies, '', ['class'=>'form-control mb-md','placeholder' => 'Select Company','id'=>'company_id']); !!}

                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" @click="filterCompany" style="margin-top: 25px;"><i class="fa fa-search"></i> Search</button>
                        <button type="button" class="btn btn-primary" @click="downloadCompanyReport" style="margin-top: 25px;"><i class="fa fa-download"></i> Download</button>

                    </div>
                </div>

                <div class="col-md-3" v-if="typeof company_payments != 'undefined' && company_payments.length > 0">
                    <div class="form-group">
                        <label class="control-label text-bold">Total Amount(Dr.) <span class="text-primary">@{{total_amount}}</span></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-bold">Total Paid Amount(Cr.) <span class="text-primary">@{{total_paid_amount}}</span></label>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-bold">Total Remaining <span class="text-primary">@{{total_remaining}}</span></label>
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

            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    <th class="">Payment Date</th>
                    <th class="">Transaction Details</th>
                    <th>Total Amount(Dr.)</th>
                    <th class="">Total Paid Amount(Cr.)</th>

                </tr>
                </thead>
                <tbody>

                <tr v-for="(cp, index) in company_payments">
                    <td data-title="Date">
                        <span v-if="cp.expense_date">@{{cp.expense_date}}</span>
                        <span v-if="cp.payment_date">@{{cp.payment_date}}</span>
                    </td>
                    <td data-title="Transaction Details">
                        <span v-if="cp.payment_date"> Bulk (@{{ cp.manifest.unique_name }})</span>
                        <span v-if="cp.expense_date"> @{{cp.receiver_name}} (By @{{ cp.payment_by }})</span>

                    </td>
                    <td data-title="Total Amount(Dr.)"><span v-if="cp.manifest_item_id">@{{cp.amount}}</span></td>
                    <td data-title="Total Paid Amount(Cr.)">
                        <span v-if="cp.expense_of">@{{cp.amount}}</span>
                    </td>

                </tr>

                <tr v-if="typeof company_payments != 'undefined' && company_payments.length > 0">
                    <td colspan="2">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Dr.) @{{total_amount}}</strong></label>
                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Cr.) @{{total_paid_amount}}</strong></label>

                    </td>
                </tr>

                <tr v-if="typeof company_payments != 'undefined' && company_payments.length > 0">
                    <td colspan="3">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total Remaining : @{{total_remaining}}</strong></label>

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
        var customerUrl = "/api/get_vendors";



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
                company_payments:{},
                looged_user_id:"{{$user_id}}",
                user_type:"{{$user_type}}"

            },
            created(){


            },


            methods: {


                filterCompany(page=1){

                    var company_id = $("#company_id").val();

                    let searchURL = '/api/getCompanyPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&company_id='+company_id;


                    axios.get(searchURL).then(response => {
                    this.company_payments = response.data.company_payment_data;
                    this.total_amount = response.data.total_amount;
                    this.total_paid_amount = response.data.total_paid_amount;
                    this.total_remaining = response.data.total_remaining;
                });

                },

                downloadCompanyReport(){


                    var company_id = $("#company_id").val();

                    let searchURL = '/admin/downloadCompanyReport?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&company_id='+company_id;

                    window.location.href=searchURL;

                },



            },

            computed: {


            },




        });


    </script>


@endsection
