@extends('layouts.admin')
@section('date-styles')
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection

@section('content')

    <header class="page-header">
        <h2>Manifest Reports</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Manifest</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
        <div class="panel-body">
            <div class="row">


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Vendor</label>
                        <select  class="form-control populate" id="selectVendor" name="user_id">

                        </select>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" @click="filterWalkingCustomer" style="margin-top: 25px;"><i class="fa fa-search"></i> Search</button>
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
                    <th class="">Vendor Name</th>
                    <th>Total Amount(Dr.)</th>
                    <th class="">Total Paid Amount(Cr.)</th>

                </tr>
                </thead>
                <tbody>

                <tr v-for="(mp, index) in manifest_payments">
                    <td data-title="Date">
                        <span v-if="mp.expense_date">@{{mp.expense_date}}</span>
                        <span v-if="mp.payment_date">@{{mp.payment_date}}</span>
                    </td>
                    <td data-title="Vendor">@{{mp.vendor.name}} </td>
                     <td data-title="Total Amount(Dr.)"><span v-if="mp.unique_name">@{{mp.amount}}</span></td>
                    <td data-title="Total Paid Amount(Cr.)">
                        <span v-if="mp.expense_of">@{{mp.amount}}</span>
                    </td>

                </tr>

                <tr v-if="typeof manifest_payments != 'undefined' && manifest_payments.length > 0">
                    <td colspan="2">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Dr.) @{{total_amount}}</strong></label>
                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Cr.) @{{total_paid_amount}}</strong></label>

                    </td>
                </tr>

                <tr v-if="typeof manifest_payments != 'undefined' && manifest_payments.length > 0">
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

        jQuery(document).ready(function($) {
            $("#selectVendor").select2({
                placeholder: "Search Vendor",
                allowClear: true,
                minimumInputLength:2,
                ajax: {
                    url: customerUrl,
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
                manifest_payments:{},
                looged_user_id:"{{$user_id}}",
                user_type:"{{$user_type}}"

            },
            created(){


            },


            methods: {


                filterWalkingCustomer(page=1){

                    var vendor_id = $("#selectVendor").val();

                    let searchURL = '/api/getManifestPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&vendor_id='+vendor_id;


                    axios.get(searchURL).then(response => {
                        this.manifest_payments = response.data.manifest_payment_data;
                    this.total_amount = response.data.total_amount;
                    this.total_paid_amount = response.data.total_paid_amount;
                    this.total_remaining = response.data.total_remaining;
                });





                },



            },

            computed: {


            },




        });


    </script>


@endsection
