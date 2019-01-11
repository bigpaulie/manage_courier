@extends('layouts.admin')
@section('date-styles')
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection

@section('content')

    <header class="page-header">
        <h2>Walking Customer Reports</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="javascript:void(0);">
                        <i class="fa fa-home"></i>
                    </a>
                </li>

                <li><span>Walking Customer</span></li>
            </ol>

            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
        </div>
    </header>

    <section class="panel">
        <div class="panel-body">
            <div class="row">


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Walking Customer</label>
                        <select  class="form-control populate" id="walkingCustomer" name="user_id">

                        </select>
                    </div>
                </div>

                {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                {{--<label class="control-label">From Date</label>--}}
                {{--<div class="input-group mb-md">--}}
                {{--<span class="input-group-addon">--}}
                {{--<i class="fa fa-calendar"></i>--}}
                {{--</span>--}}
                {{--<date-picker v-model="from_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>--}}

                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                {{--<label class="control-label">End Date</label>--}}
                {{--<div class="input-group mb-md">--}}
                {{--<span class="input-group-addon">--}}
                {{--<i class="fa fa-calendar"></i>--}}
                {{--</span>--}}
                {{--<date-picker v-model="end_date" :config="{format: 'MM/DD/YYYY'}"></date-picker>--}}

                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}



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
                    <th class="">Courier Id</th>
                    <th class="">Customer Name</th>
                    <th class="">Phone</th>
                    <th class="">Address</th>
                    <th class="">City</th>
                    <th>Total Amount(Dr.)</th>
                    <th class="">Total Paid Amount(Cr.)</th>
                    <th class="">Discount</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(wp, index) in walking_payments">
                    <td data-title="Date">@{{wp.payment_date}} </td>
                    <td data-title="Courier Id">@{{wp.courier.unique_name}} </td>
                    <td data-title="Customer Name">@{{wp.courier.s_name}}</td>
                    <td data-title="Phone">@{{wp.courier.s_phone}}</td>
                    <td data-title="Phone">@{{wp.courier.s_address1}}</td>
                    <td data-title="Phone">@{{wp.courier.s_city}}</td>
                    <td data-title="Total Amount(Dr.)"><span v-if="wp.total">@{{wp.total}}</span></td>
                    <td data-title="Total Paid Amount(Cr.)">
                        <span v-if="wp.amount">@{{wp.amount}}</span>
                        <span v-if="wp.pay_amount">@{{wp.pay_amount}}</span>
                    </td>
                    <td data-title="Discount"><span v-if="wp.discount">@{{wp.discount}}</span></td>



                </tr>

                <tr v-if="typeof walking_payments != 'undefined' && walking_payments.length > 0">
                    <td colspan="6">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Dr.) @{{total_amount}}</strong></label>
                    </td>

                    <td>
                        <label><strong class="text-primary">Total (Cr.) @{{total_paid_amount}}</strong></label>

                    </td>

                    <td>
                        <label><strong class="text-primary">Total Discount @{{total_discount}}</strong></label>

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
        var customerUrl = "/api/get_walking_customer";

        jQuery(document).ready(function($) {
            $("#walkingCustomer").select2({
                placeholder: "Search Walking Customer",
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
                walking_payments:{},
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",
                looged_user_id:"{{$user_id}}",
                user_type:"{{$user_type}}"

            },
            created(){

                //     let searchURL = '/api/getWalkingCustomerPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                //     searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date
                //     axios.get(searchURL).then(response => {
                //         this.walking_payments = response.data.walking_payment_data;
                //         //this.total_amount = response.data.total_amount;
                //         //this.total_paid_amount = response.data.total_paid_amount;
                // });
            },


            methods: {


                filterWalkingCustomer(page=1){

                    var customer_phone = $("#walkingCustomer").val();

                    let searchURL = '/api/getWalkingCustomerPayment?type=all&user_type='+this.user_type+'&logged_user_id='+this.looged_user_id;
                    searchURL+='&customer_phone='+customer_phone;


                    axios.get(searchURL).then(response => {
                        this.walking_payments = response.data.walking_payment_data;
                    this.total_amount = response.data.total_amount;
                    this.total_paid_amount = response.data.total_paid_amount;
                    this.total_discount = response.data.total_discount;
                });





                },



            },

            computed: {


            },




        });


    </script>


@endsection
