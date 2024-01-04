<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/blacklogo.png') }}"
                    class="header-logo" />
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- Dashboard --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Dashboard'))
                <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                            class="fas fa-th-large"></i><span>Dashboard</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                            class="fas fa-th-large"></i><span>Dashboard</span></a>
                </li>
            @endif
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
                    <ul class="dropdown-menu active">
                        {{-- Business Owner --}}
                        @if (auth()->guard('web')->check() &&
                                auth()->guard('web')->user()->can('Business Owner'))
                            <li class="dropdown {{ request()->is('admin/businessOwner*') ? 'active' : '' }}">
                                <a href="{{ route('businessOwner.index') }}" class="nav-link"><i
                                        data-feather="users"></i><span>Business Owner</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/businessOwner*') ? 'active' : '' }}">
                                <a href="{{ route('businessOwner.index') }}" class="nav-link"><i
                                        data-feather="users"></i><span>Business Owner</span></a>
                            </li>
                        @endif
                        {{-- Driver --}}
                        @if (auth()->guard('web')->check() &&
                                auth()->guard('web')->user()->can('Driver'))
                            <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                                <a href="{{ route('driver.index') }}" class="nav-link"><i
                                        data-feather="users"></i><span>Driver</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/driver*') ? 'active' : '' }}">
                                <a href="{{ route('driver.index') }}" class="nav-link"><i
                                        data-feather="users"></i><span>Driver</span></a>
                            </li>
                        @endif
                        {{-- Sub Admin --}}
                        @if (auth()->guard('web')->check() &&
                                auth()->guard('web')->user()->can('SubAdmin'))
                            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}" class="nav-link"><i
                                        data-feather="users"></i><span>Sub
                                        Admin</span></a>
                            </li>
                        @elseif (auth()->guard('admin')->check())
                            <li class="dropdown {{ request()->is('admin/subadmin*') ? 'active' : '' }}">
                                <a href="{{ route('subadmin.index') }}" class="nav-link"><i
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
                    (auth()->guard('web')->user()->can('DriverWallets') ||
                        auth()->guard('web')->user()->can('WithdrawRequest'))) ||
                    auth()->guard('admin')->check())
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-coins"></i>
                        <span>Wallet Management</span>
                    </a>
                    <ul class="dropdown-menu active">
                        {{-- Driver Wallet --}}
                        @if (auth()->guard('web')->check() &&
                                auth()->guard('web')->user()->can('DriverWallets'))
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
                        @if (auth()->guard('web')->check() &&
                                auth()->guard('web')->user()->can('WithdrawRequest'))
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
            {{-- Owner Recipits --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Payments'))
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
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Vehicles'))
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

            {{-- @if (auth()->guard('web')->check() &&
    auth()->guard('web')->user()->can('Roles & Permissions'))
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
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Privacy policies'))
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
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Term&Conditions'))
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/termCondition') }}" class="nav-link"> <i
                            class="fas fa-globe"></i><span>Term
                            & Conditions</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/termCondition') }}" class="nav-link"> <i
                            class="fas fa-globe"></i><span>Term
                            & Conditions</span></a>
                </li>
            @endif
            {{-- Help & Support --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Help & Support'))
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
            @if (auth()->guard('web')->check() &&
            auth()->guard('web')->user()->can('Notification'))
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
            {{--
            <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                <a href="{{ route('help-and-support.index') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>Help & Support</span></a>
            </li> --}}

        </ul>
    </aside>
</div>
