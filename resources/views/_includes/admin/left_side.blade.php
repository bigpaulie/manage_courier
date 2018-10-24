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
            ?>
            @if(Auth::user()->user_type == 'admin')

            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li @if($action_name == 'dashboard')class="nav-active" @endif>
                        <a href="/admin/dashboard">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li @if($controller_name == 'couriers')class="nav-active" @endif>
                        <a href="/admin/couriers">

                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span>Couriers</span>
                        </a>
                    </li>
                    <li @if($controller_name == 'agents')class="nav-active" @endif>
                        <a href="/admin/agents">
                            <i class="fa fa-copy" aria-hidden="true"></i>
                            <span>Agents</span>
                        </a>

                    </li>
                    <li @if($controller_name == 'stores')class="nav-active" @endif>
                        <a href="/admin/stores">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                            <span>Stores</span>
                        </a>

                    </li>
                    <li @if($controller_name == 'expenses')class="nav-active" @endif>
                        <a href="/admin/expenses">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            <span>Expenses</span>
                        </a>

                    </li>
                    <li @if($action_name == 'notifications')class="nav-active" @endif>
                        <a href="/admin/notifications">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <span>Notifications</span>
                        </a>

                    </li>
                    <li class="">
                        <a>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>Payments</span>
                        </a>

                    </li>
                    <li class="">
                        <a>
                            <i class="fa fa-columns" aria-hidden="true"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="nav-active">
                                <a href="layouts-default.html">
                                    Default
                                </a>
                            </li>
                            <li>
                                <a href="layouts-boxed.html">
                                    Boxed
                                </a>
                            </li>
                            <li>
                                <a href="layouts-menu-collapsed.html">
                                    Menu Collapsed
                                </a>
                            </li>
                            <li>
                                <a href="layouts-scroll.html">
                                    Scroll
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a>
                            <i class="fa fa-copy" aria-hidden="true"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a href="/admin/expense_types">
                                    Expense Types
                                </a>
                            </li>
                            <li>
                                <a href="/admin/status">
                                    Status
                                </a>
                            </li>
                            <li>
                                <a href="/admin/package_types">
                                    Package Types
                                </a>
                            </li>
                            <li>
                                <a href="/admin/service_types">
                                    Service Types
                                </a>
                            </li>
                            <li>
                                <a href="/admin/content_types">
                                    Content Types
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
                        <li @if($action_name == 'dashboard')class="nav-active" @endif>
                            <a href="/agent/dashboard">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li @if($controller_name == 'couriers')class="nav-active" @endif>
                            <a href="/agent/couriers">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>Couriers</span>
                            </a>
                        </li>

                        <li class="">
                            <a>
                                <i class="fa fa-copy" aria-hidden="true"></i>
                                <span>Reports</span>
                            </a>

                        </li>

                    </ul>
                </nav>
            @endif

            @if(Auth::user()->user_type == 'store')
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li @if($action_name == 'dashboard')class="nav-active" @endif>
                            <a href="/store/dashboard">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li @if($controller_name == 'expenses')class="nav-active" @endif>
                            <a href="/store/expenses">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>Expenses</span>
                            </a>
                        </li>
                        <li class="">
                            <a>
                                <i class="fa fa-copy" aria-hidden="true"></i>
                                <span>Reports</span>
                            </a>

                        </li>


                    </ul>
                </nav>
            @endif

        </div>

    </div>

</aside>