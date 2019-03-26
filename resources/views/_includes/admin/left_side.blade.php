<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            Navigation
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">

            <?php
                $current_routename =\Route::currentRouteName();
                $route_arr = explode('.',$current_routename);

                $controller_name = $route_arr[0];

                $action_name = $route_arr[1];

                $notification_count = \App\Models\Notification::where('status','unread')->count();
            ?>
            @if(Auth::user()->user_type == 'admin')

            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li @if($action_name == 'dashboard')class="nav-active nav-expanded" @endif>
                        <a href="/admin/dashboard">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li @if($controller_name == 'couriers')class="nav-active nav-expanded" @endif>
                        <a href="/admin/couriers">

                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span>Couriers</span>
                        </a>
                    </li>

                    <li @if($controller_name == 'stores')class="nav-active nav-expanded" @endif>
                        <a href="/admin/stores">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span>Stores</span>
                        </a>

                    </li>

                    <li @if($controller_name == 'agents')class="nav-active nav-expanded" @endif>
                        <a href="/admin/agents">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Agents</span>
                        </a>

                    </li>
                    <li @if($controller_name == 'expenses')class="nav-active nav-expanded" @endif>
                        <a href="/admin/expenses">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Expenses</span>
                        </a>

                    </li>
                    <li @if($action_name == 'notifications')class="nav-active nav-expanded" @endif>
                        <a href="/admin/notifications">
                            <span class="pull-right label label-primary">{{$notification_count}}</span>

                            <i class="fa fa-bell" aria-hidden="true"></i>
                            <span>Notifications</span>
                        </a>

                    </li>
                    <li @if($controller_name == 'payments')class="nav-active nav-expanded" @endif>
                        <a href="/admin/payments">
                            <i class="fa fa-dollar" aria-hidden="true"></i>
                            <span>Payments</span>
                        </a>

                    </li>

                    {{--<li @if($controller_name == 'payments')class="nav-active nav-expanded" @endif>--}}
                        {{--<a href="/admin/couriers/new_courier">--}}
                            {{--<i class="fa fa-envelope" aria-hidden="true"></i>--}}
                            {{--<span>Import New Courier</span>--}}
                        {{--</a>--}}

                    {{--</li>--}}
                    {{--<li @if($controller_name == 'reports')class="nav-active nav-expanded" @endif>--}}
                        {{--<a href="/admin/reports">--}}
                            {{--<i class="fa fa-file" aria-hidden="true"></i>--}}
                            {{--<span>Reports</span>--}}
                        {{--</a>--}}

                    {{--</li>--}}
                    {{--<li @if($controller_name == 'payment_expense')class="nav-active nav-expanded" @endif>--}}
                        {{--<a href="/admin/payment_expense">--}}
                            {{--<i class="fa fa-file" aria-hidden="true"></i>--}}
                            {{--<span>Payment/Expense Report</span>--}}
                        {{--</a>--}}

                    {{--</li>--}}

                    <li @if($controller_name == 'manifest')class="nav-active nav-expanded" @endif>
                        <a href="/admin/manifest">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                            <span>Manifest</span>
                        </a>

                    </li>

                    <?php $reports_array = ['walking_customer','agent_payment','payment_expense','manifest','company']; ?>

                    <li class="nav-parent @if(in_array($action_name, $reports_array))nav-expanded nav-active @endif">
                        <a>
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="nav nav-children">

                            <li @if($action_name == 'walking_customer')class="nav-active" @endif>
                                <a href="/admin/reports/walking_customer">
                                    Walking Customer Payment
                                </a>
                            </li>

                            <li @if($action_name == 'agent_payment')class="nav-active" @endif>
                                <a href="/admin/reports/agent_payment">
                                    Agent Payment
                                </a>
                            </li>
                            <li @if($action_name == 'payment_expense')class="nav-active" @endif>
                                <a href="/admin/reports/payment_expense">
                                    Payment/Expense Report
                                </a>
                            </li>
                            <li @if($action_name == 'manifest')class="nav-active" @endif>
                                <a href="/admin/reports/manifest">
                                    Manifest Report
                                </a>
                            </li>

                            <li @if($action_name == 'company')class="nav-active" @endif>
                                <a href="/admin/reports/company">
                                    Company Report
                                </a>
                            </li>

                        </ul>
                    </li>




                <?php $nav_array = ['expense_types','status','package_types','service_types', 'content_types','courier_services','banks','vendors','companies']; ?>

                    <li class="nav-parent @if(in_array($controller_name, $nav_array))nav-expanded nav-active @endif">
                        <a>
                            <i class="fa fa-database" aria-hidden="true"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="nav nav-children">

                            <li @if($controller_name == 'banks')class="nav-active" @endif>
                                <a href="/admin/banks">
                                    Banks
                                </a>
                            </li>

                            <li @if($controller_name == 'expense_types')class="nav-active" @endif>
                                <a href="/admin/expense_types">
                                    Expense Types
                                </a>
                            </li>
                            <li @if($controller_name == 'status')class="nav-active" @endif>
                                <a href="/admin/status">
                                    Status
                                </a>
                            </li>
                            <li @if($controller_name == 'package_types')class="nav-active" @endif>
                                <a href="/admin/package_types">
                                    Package Types
                                </a>
                            </li>
                            <li @if($controller_name == 'service_types')class="nav-active" @endif>
                                <a href="/admin/service_types">
                                    Service Types
                                </a>
                            </li>
                            <li @if($controller_name == 'content_types')class="nav-active" @endif>
                                <a href="/admin/content_types">
                                    Content Types
                                </a>
                            </li>

                            <li @if($controller_name == 'courier_services')class="nav-active" @endif>
                                <a href="/admin/courier_services">
                                    Courier Services
                                </a>
                            </li>
                            <li @if($controller_name == 'vendors')class="nav-active" @endif>
                                <a href="/admin/vendors">
                                    Vendors
                                </a>
                            </li>

                            <li @if($controller_name == 'companies')class="nav-active" @endif>
                                <a href="/admin/companies">
                                    Companies
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>
            </nav>
            @endif

            @if(Auth::user()->user_type == 'agent')
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li @if($action_name == 'dashboard')class="nav-active nav-expanded" @endif>
                            <a href="/agent/dashboard">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li @if($controller_name == 'couriers')class="nav-active nav-expanded" @endif>
                            <a href="/agent/couriers">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>Couriers</span>
                            </a>
                        </li>

                        <li @if($controller_name == 'reports')class="nav-active nav-expanded" @endif>
                            <a href="/agent/reports/agent_payment">
                                <i class="fa fa-file" aria-hidden="true"></i>
                                <span>Agent Payment Report</span>
                            </a>
                        </li>

                    </ul>
                </nav>
            @endif

            @if(Auth::user()->user_type == 'store')
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li @if($action_name == 'dashboard')class="nav-active nav-expanded" @endif>
                            <a href="/store/dashboard">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li @if($controller_name == 'couriers')class="nav-active nav-expanded" @endif>
                            <a href="/store/couriers">

                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>Couriers</span>
                            </a>
                        </li>

                         <li @if($controller_name == 'agents')class="nav-active nav-expanded" @endif>
                        <a href="/store/agents">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Agents</span>
                        </a>

                    </li>

                        <li @if($controller_name == 'payments')class="nav-active nav-expanded" @endif>
                            <a href="/store/payments">
                                <i class="fa fa-dollar" aria-hidden="true"></i>
                                <span>Payments</span>
                            </a>

                        </li>
                        <li @if($controller_name == 'expenses')class="nav-active nav-expanded" @endif>
                            <a href="/store/expenses">
                                <i class="fa fa-money" aria-hidden="true"></i>
                                <span>Expenses</span>
                            </a>
                        </li>

                        <li @if($controller_name == 'manifest')class="nav-active nav-expanded" @endif>
                            <a href="/store/manifest">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                <span>Manifest</span>
                            </a>

                        </li>

                        <?php $reports_array = ['walking_customer','agent_payment','payment_expense','manifest','company']; ?>

                        <li class="nav-parent @if(in_array($action_name, $reports_array))nav-expanded nav-active @endif">
                            <a>
                                <i class="fa fa-file" aria-hidden="true"></i>
                                <span>Reports</span>
                            </a>
                            <ul class="nav nav-children">

                                <li @if($action_name == 'walking_customer')class="nav-active" @endif>
                                    <a href="/store/reports/walking_customer">
                                        Walking Customer Payment
                                    </a>
                                </li>

                                <li @if($action_name == 'agent_payment')class="nav-active" @endif>
                                    <a href="/store/reports/agent_payment">
                                        Agent Payment
                                    </a>
                                </li>
                                <li @if($action_name == 'payment_expense')class="nav-active" @endif>
                                    <a href="/store/reports/payment_expense">
                                        Payment/Expense Report
                                    </a>
                                </li>

                                <li @if($action_name == 'manifest')class="nav-active" @endif>
                                    <a href="/store/reports/manifest">
                                        Manifest Report
                                    </a>
                                </li>

                                <li @if($action_name == 'company')class="nav-active" @endif>
                                    <a href="/store/reports/company">
                                        Company Report
                                    </a>
                                </li>

                            </ul>
                        </li>


                        {{--<li @if($controller_name == 'reports')class="nav-active nav-expanded" @endif>--}}
                            {{--<a href="/store/reports">--}}
                                {{--<i class="fa fa-file" aria-hidden="true"></i>--}}
                                {{--<span>Reports</span>--}}
                            {{--</a>--}}

                        {{--</li>--}}

                        {{--<li @if($controller_name == 'payment_expense')class="nav-active nav-expanded" @endif>--}}
                        {{--<a href="/store/payment_expense">--}}
                            {{--<i class="fa fa-file" aria-hidden="true"></i>--}}
                            {{--<span>Payment/Expense Report</span>--}}
                        {{--</a>--}}

                    {{--</li>--}}


                    </ul>
                </nav>
            @endif

        </div>

    </div>

</aside>