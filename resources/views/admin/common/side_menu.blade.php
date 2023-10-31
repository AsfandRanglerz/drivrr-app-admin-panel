<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#"> <img alt="image" src="{{ asset('public/admin/assets/img/logo.png') }}"
                    class="header-logo" /> <span class="logo-name">Drivrr</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            {{-- Dashboard --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Dashboard'))
                <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Dashboard</span></a>
                </li>
            @endif
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="layout"></i><span>UserManagement</span></a>
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
            {{-- Vehicles --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Vehicles'))
                <li class="dropdown {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                    <a href="{{ route('vehicle.index') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Vehicles</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
                    <a href="{{ route('vehicle.index') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Vehicles</span></a>
                </li>
            @endif
            {{-- Roles & Permissions --}}
            @if (auth()->guard('web')->check() &&
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
            @endif
            {{-- Privacy policies --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Privacy policies'))
                <li class="dropdown {{ request()->is('admin/Privacy-policy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Privacy policies</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/Privacy-policy') ? 'active' : '' }}">
                    <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Privacy policies</span></a>
                </li>
            @endif
            {{-- Term & Conditions --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Term&Conditions'))
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/termCondition') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Term
                            & Conditions</span></a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/termCondition') ? 'active' : '' }}">
                    <a href="{{ url('/admin/termCondition') }}" class="nav-link"><i
                            data-feather="monitor"></i><span>Term
                            & Conditions</span></a>
                </li>
            @endif
            {{-- Help & Support --}}
            @if (auth()->guard('web')->check() &&
                    auth()->guard('web')->user()->can('Help & Support'))
                <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                    <a href="{{ route('help-and-support.index') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span>Help & Support</span>
                    </a>
                </li>
            @elseif (auth()->guard('admin')->check())
                <li class="dropdown {{ request()->is('admin/helpAndSupport*') ? 'active' : '' }}">
                    <a href="{{ route('help-and-support.index') }}" class="nav-link">
                        <i data-feather="monitor"></i>
                        <span> Help & Support</span>
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
