@extends('layouts.admin')
@section('date-styles')
    <link href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' rel='stylesheet' type='text/css'>

@endsection

@section('content')

    <header class="page-header">
        <h2>Manage Payment/Expense Report</h2>

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

                
               <!--  <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <select  class="form-control populate" id="userSelect" name="user_id">

                        </select>
                    </div>
                </div> -->

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
                       <button type="button" class="btn btn-success" @click="searchEP" style="margin-top: 25px;"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="panel">
        <header class="panel-heading">

            <h2 class="panel-title">Payment/Expense</h2>
        </header>
        <div class="panel-body">
            <table class="table table-no-more table-bordered table-striped mb-none">
                <thead>
                <tr>
                    
                    <th class="">Date</th>
                    <th class="">Payment/Expense Type</th>
                    <th>Payment(Cr.)</th>
                    <th class="">Expense(Dr.)</th>
                    <th>Total</th>
                    
                    
                </tr>
                </thead>
                <tbody>
                <tr v-for="(ex, index) in payments_expenses">

                    <td data-title="Date">@{{ex.payment_date}} @{{ex.expense_date}}</td>
                    <td data-title="Payment Type">


                        <span v-if="ex.payment_user_type">@{{ex.payment_by | capitalize}}</span>
                        <span v-if="ex.courier_id">Courier</span>
                        <span v-if="ex.expense_type_id">@{{ex.expense_type.name}}</span>
                    </td>

                    <td data-title="Payment(Cr.)">
                        <span v-if="ex.payment_date">@{{ex.amount}}</span>
                        <span v-if="ex.courier_id">@{{ex.pay_amount}}</span>

                    </td>

                    <td data-title="Expense(Dr.)">
                        <span v-if="ex.expense_type_id">@{{ex.amount}}</span>
                    </td>
                    <td></td>
                 </tr>

                <tr v-if="typeof payments_expenses != 'undefined' && payments_expenses.length > 0">
                    <td colspan="2">

                    </td>

                    <td>
                        <label><strong class="text-primary">Total Payment: @{{total_payment}}</strong></label>
                    </td>

                    <td>
                        <label><strong class="text-primary">Total Expense: @{{total_expense}}</strong></label>

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

        jQuery(document).ready(function($) {


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
                payments_expenses:{},
                couriers:{},
                from_date:"{{date('m/d/Y')}}",
                end_date:"{{date('m/d/Y')}}",
                user_id:"{{$user_id}}"

            },
            created(){

                    var page_no =1;

                    let searchURL = '/api/generate_payment_expense?page='+page_no+'&user_id='+this.user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date

                    axios.get(searchURL).then(response => {
                    this.payments_expenses = response.data.payments_expense_data;
                    this.total_payment = response.data.total_payment;
                    this.total_expense = response.data.total_expense;
                    this.total = response.data.total;
                });
            },


            methods: {


                searchEP(page=1){

                   
                    var page_no =1;

                    let searchURL = '/api/generate_payment_expense?page='+page_no+'&user_id='+this.user_id;
                    searchURL+='&from_date='+this.from_date+'&end_date='+this.end_date

                    axios.get(searchURL).then(response => {
                    this.payments_expenses = response.data.payments_expense_data;
                    this.total_payment = response.data.total_payment;
                    this.total_expense = response.data.total_expense;
                    this.total = response.data.total;
                });

                },



            },

            computed: {


            },




        });


    </script>


@endsection
