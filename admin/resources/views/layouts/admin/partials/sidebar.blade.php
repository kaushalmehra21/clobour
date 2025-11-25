<header class="main-nav">
    <nav>
        <div class="main-navbar">
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    @php
                        $user = auth()->user();
                        $isSuperAdmin = $user && $user->is_super_admin;
                        $prefix = $isSuperAdmin ? 'super-admin' : 'colony';
                    @endphp
                    
                    @if($isSuperAdmin)
                        {{-- Super Admin Menu --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="{{ route('super-admin.dashboard') }}">
                                <i data-feather="home"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="building"></i><span>Colonies</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('super-admin.colonies.index') }}">Manage Colonies</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="credit-card"></i><span>Subscription Plans</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('super-admin.plans.index') }}">Manage Plans</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="users"></i><span>Users</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('super-admin.users.index') }}">Manage Users</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- Colony Admin Menu --}}
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="{{ route('colony.dashboard') }}">
                                <i data-feather="home"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="building"></i><span>Residents & Units</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.units.index') }}">Units</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.residents.index') }}">Residents</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="dollar-sign"></i><span>Billing & Payments</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.billing.index') }}">Bills</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.charges.index') }}">Charges</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.payments.index') }}">Payments</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="file-text"></i><span>Expenses</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.expenses.index') }}">Expenses</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.expense-categories.index') }}">Categories</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.vendors.index') }}">Vendors</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="alert-circle"></i><span>Complaints</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.complaints.index') }}">Complaints</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.complaint-categories.index') }}">Categories</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="user-check"></i><span>Visitors & Security</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.visitors.index') }}">Visitors</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.vehicles.index') }}">Vehicles</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="calendar"></i><span>Amenities</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.amenities.index') }}">Amenities</a>
                                </li>
                                <li>
                                    <a href="{{ route('colony.bookings.index') }}">Bookings</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="bell"></i><span>Notices</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.notices.index') }}">Notices</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="javascript:void(0)">
                                <i data-feather="bar-chart-2"></i><span>Reports</span>
                            </a>
                            <ul class="nav-submenu menu-content">
                                <li>
                                    <a href="{{ route('colony.reports.index') }}">Reports</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link menu-title" href="{{ route('colony.settings.index') }}">
                                <i data-feather="settings"></i><span>Settings</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
