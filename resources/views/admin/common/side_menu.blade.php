<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/blacklogo.png') }}"
                    class="header-logo w-75" />
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- Dashboard --}}

            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard*') ? 'text-white' : '' }}"><i
                        class="fas fa-th-large"></i><span>Dashboard</span></a>
            </li>
            {{-- User Mangement --}}
            @if (
                (auth()->guard('web')->check() &&
                    (auth()->guard('web')->user()->can('Business Owner') ||
                        auth()->guard('web')->user()->can('Driver') ||
                        auth()->guard('web')->user()->can('SubAdmin'))) ||
                    auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fa fa-users"></i>
                        <span>UserManagement</span></a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/busniessOwner*') || request()->is('admin/drivers*') || request()->is('admin/subadmin*') ? 'show' : '' }}">
                        {{-- Business Owner --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Business Owner'))
                            <li class="dropdown {{ request()->is('admin/busniessOwner*') ? 'active' : '' }}">
                                <a href="{{ route('usniessOwner.index') }}"
                                    class="nav-link {{ request()->is('admin/busniessOwner*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Business Owner</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/busniessOwner*') ? 'active' : '' }}">
                                <a href="{{ route('busniessOwner.index') }}"
                                    class="nav-link {{ request()->is('admin/busniessOwner*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Business Owner</span></a>
                            </li>
                        @endif
                        {{-- Driver --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Driver'))
                            <li class="dropdown {{ request()->is('admin/drivers*') ? 'active' : '' }}">
                                <a href="{{ route('drivers.index') }}"
                                    class="nav-link {{ request()->is('admin/driver*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Driver</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/drivers*') ? 'active' : '' }}">
                                <a href="{{ route('drivers.index') }}"
                                    class="nav-link {{ request()->is('admin/driver*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Driver</span></a>
                            </li>
                        @endif

                        {{-- Sub Admin --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('SubAdmin'))
                            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Sub
                                        Admin</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}"
                                    class="nav-link {{ request()->is('admin/subadmin*') ? 'text-white' : '' }}"><i
                                        data-feather="users"></i><span>Sub
                                        Admin</span></a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            {{-- Wallet Management --}}
            @if (
                (auth()->guard('web')->check() &&
                    (auth()->guard('web')->user()->can('DriverWallets') || auth()->guard('web')->user()->can('WithdrawRequest'))) ||
                    auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-coins"></i>
                        <span>Wallet Management</span>
                    </a>
                    <ul
                        class="dropdown-menu {{ request()->is('admin/wallet*') || request()->is('admin/withdrawal_requests*') ? 'show' : '' }}">
                        {{-- Driver Wallet --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('DriverWallets'))
                            <li class="dropdown {{ request()->is('admin/wallet*') ? 'active' : '' }}">
                                <a href="{{ route('show-wallets') }}" class="nav-link"><i
                                        class="fas fa-wallet"></i><span>Driver Wallets</span></a>
                            </li>
                        @elseif(auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/wallet*') ? 'active' : '' }}">
                                <a href="{{ route('show-wallets') }}" class="nav-link"><i
                                        class="fas fa-wallet"></i><span>Driver Wallets</span></a>
                            </li>
                        @endif

                        {{-- Withdrawal Requests --}}
                        @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('WithdrawRequest'))
                            <li class="dropdown {{ request()->is('admin/withdrawal_requests*') ? 'active' : '' }}">
                                @php
                                    $requestCount = App\Models\WithdrawalRequest::where('status', '0')
                                        ->where('seen', '0')
                                        ->count();
                                @endphp
                                <a href="{{ route('show-withdrawal-requests') }}" class="nav-link">
                                    <i class="fab fa-twitch"></i>
                                    @if ($requestCount > 0)
                                        <span class="danger">
                                            Withdrawal Req.
                                            <span class="px-1 py-0.5 rounded-circle text-white bg-danger"
                                                style="border-radius: 50%; font-size:11px">
                                                {{ $requestCount }}
                                            </span>
                                        </span>
                                    @else
                                        <span>Withdrawal Requests</span>
                                    @endif
                                </a>
                            </li>
                        @elseif(auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/withdrawal_requests*') ? 'active' : '' }}">
                                @php
                                    $requestCount = App\Models\WithdrawalRequest::where('status', '0')
                                        ->where('seen', '0')
                                        ->count();
                                @endphp
                                <a href="{{ route('show-withdrawal-requests') }}" class="nav-link">
                                    <i class="fab fa-twitch"></i>
                                    @if ($requestCount > 0)
                                        <span class="danger">
                                            Withdrawal Req.
                                            <span class="px-1 py-0.5 rounded-circle text-white bg-danger"
                                                style="border-radius: 50%; font-size:11px">
                                                {{ $requestCount }}
                                            </span>
                                        </span>
                                    @else
                                        <span>Withdrawal Requests</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            {{-- Lisence Approvel Request --}}
            <li class="dropdown {{ request()->is('admin/lisenceApprovel*') ? 'active' : '' }}">
                <a href="{{ route('lisenceApprovel.index') }}" class="nav-link"><i
                        class="fas fa-receipt"></i><span>License Approvals</span>
                    <div id="lisenceApprovel"
                        class="badge {{ request()->is('admin/lisenceApprovel*') ? 'bg-white text-dark' : 'bg-dark text-white' }} rounded-circle ">
                    </div>
                </a>
            </li>
            {{-- Owner Recipits --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Payments'))
                <li class="dropdown {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <a href="{{ route('business-owner-payments') }}" class="nav-link"><i
                            class="fas fa-receipt"></i><span>Payments</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <a href="{{ route('business-owner-payments') }}" class="nav-link"><i
                            class="fas fa-receipt"></i><span>Payments</span></a>
                </li>
            @endif
            {{-- Vehicles --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Vehicles'))
                <li class="dropdown {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                    <a href="{{ route('vehicle.index') }}" class="nav-link"> <i
                            class="fas fa-bus"></i><span>Vehicles</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                    <a href="{{ route('vehicle.index') }}" class="nav-link"> <i
                            class="fas fa-bus"></i><span>Vehicles</span></a>
                </li>
            @endif
            {{-- Roles & Permissions --}}

            {{-- @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Roles & Permissions'))
                <li class="dropdown {{ request()->is('admin/roles-permission') ? 'active' : '' }}">
                    <a href="{{ route('roles-permission.index') }}" class="nav-link"><i
                            data-feather="users"></i><span>Roles & Permissions</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/roles-permission') ? 'active' : '' }}">
                    <a href="{{ route('roles-permission.index') }}" class="nav-link"><i
                            data-feather="users"></i><span>Roles & Permissions</span></a>
                </li>
            @endif --}}

            {{-- Privacy policies --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Privacy policies'))
                <li class="dropdown {{ request()->is('admin/Privacy-policy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"> <i
                            class="fa fa-lock"></i><span>Privacy Policy</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/Privacy-policy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"> <i
                            class="fa fa-lock"></i><span>Privacy Policy</span></a>
                </li>
            @endif
            {{-- Term & Conditions --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Term&Conditions'))
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/term-condition') }}" class="nav-link"> <i
                            class="fas fa-globe"></i><span>Term
                            & Conditions</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/term-condition') }}" class="nav-link"> <i
                            class="fas fa-globe"></i><span>Term
                            & Conditions</span></a>
                </li>
            @endif

            {{-- About Us --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('AboutUs'))
                <li class="dropdown {{ request()->is('admin/aboutus') ? 'active' : '' }}">
                    <a href="{{ url('/admin/about-us') }}" class="nav-link"> <i class="fas fa-globe"></i><span>About
                            Us</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/aboutus') ? 'active' : '' }}">
                    <a href="{{ url('/admin/about-us') }}" class="nav-link"> <i class="fas fa-globe"></i><span>About
                            Us</span></a>
                </li>
            @endif

            {{-- Help & Support --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Help & Support'))
                <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                    <a href="{{ route('help-and-support.index') }}" class="nav-link">
                        <i class="fa fa-info-circle"></i>
                        <span>Help & Support</span>
                    </a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                    <a href="{{ route('help-and-support.index') }}" class="nav-link">
                        <i class="fa fa-info-circle"></i>
                        <span> Help & Support</span>
                    </a>
                </li>
            @endif

            {{-- Push Notifications --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('Notification'))
                <li class="dropdown {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>
            @endif

            {{-- Driver Reviews --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('DriverReviews'))
                <li class="dropdown {{ request()->is('admin/driverreview*') ? 'active' : '' }}">
                    <a href="{{ route('driverreview.index') }}" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span>Driver Ranking</span>
                    </a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/driverreview*') ? 'active' : '' }}">
                    <a href="{{ route('driverreview.index') }}" class="nav-link">
                        <i class="fas fa-star"></i>
                        <span>Driver Ranking</span>
                    </a>
                </li>
            @endif
            {{-- Completed Jobs --}}
            @if (auth()->guard('web')->check() && auth()->guard('web')->user()->can('CompleteJob'))
                <li class="dropdown {{ request()->is('admin/completedjobs*') ? 'active' : '' }}">
                    <a href="{{ route('completedjobs.index') }}" class="nav-link">
                        <i class="fas fa-check-circle"></i>
                        <span>Driver Completed Jobs</span>
                    </a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/driverreview*') ? 'active' : '' }}">
                    <a href="{{ route('completedjobs.index') }}" class="nav-link">
                        <i class="fas fa-check-circle"></i>
                        <span>Driver Completed Jobs</span>
                    </a>
                </li>
            @endif
            {{-- <li class="dropdown {{ request()->is('admin/otp*') ? 'active' : '' }}">
                <a href="{{ route('otp.index') }}" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Otp</span>
                </a>
            </li> --}}
            {{--
            <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                <a href="{{ route('help-and-support.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Help & Support</span></a>
            </li> --}}

        </ul>
    </aside>
</div>
