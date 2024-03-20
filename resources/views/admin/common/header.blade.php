<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
            {{-- <li>
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li> --}}
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        {{-- <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                                                     class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i>
                <span class="badge headerBadge1">
                6 </span> </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Messages
                    <div class="float-right">
                        <a href="#">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    <a href="#" class="dropdown-item"> <span class="dropdown-item-avatar
											text-white"> <img alt="image" src="{{ asset('public/admin/assets/img/users/user-1.png')}}" class="rounded-circle">
                  </span> <span class="dropdown-item-desc"> <span class="message-user">John
                      Deo</span>
                    <span class="time messege-text">Please check your mail !!</span>
                    <span class="time">2 Min Ago</span>
                  </span>
                    </a>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li> --}}

        {{-- <li class="dropdown dropdown-list-toggle">
    <a href="{{ route('notify') }}" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
        <i data-feather="bell" class="bell"></i>
    </a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
        <div class="dropdown-header">
            Notifications
            <div class="float-right">
                <a href="#">Mark All As Read</a>
            </div>
        </div>
        <div class="dropdown-list-content dropdown-list-icons">
            <a href="#" class="dropdown-item dropdown-item-unread">
                <span class="dropdown-item-icon bg-primary text-white">
                    <i class="fas fa-code"></i>
                </span>
                <span class="dropdown-item-desc">
                    Template update is available now!
                    <span class="time">2 Min Ago</span>
                </span>
            </a>
            @foreach (auth()->guard('admin')->user()->notifications as $notification)
                <div>{{ $notification->data['user_id'] }}</div>
            @endforeach
        </div>
        <div class="dropdown-footer text-center">
            <a href="#">View All <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
</li> --}}
        <li class="dropdown dropdown-list-toggle">
            @php
                // Assuming you have the currently authenticated user available
                $userId = Auth::id();
                use App\Models\PushNotification;
                $updatedNotifications = PushNotification::where('push_notifications.user_id', $userId)
                    ->where('seen_by', 0)
                    ->leftJoin('users', 'push_notifications.user_name', '=', 'users.id')
                    ->leftJoin('role_user', 'push_notifications.user_name', '=', 'role_user.user_id')
                    ->select(
                        'push_notifications.*',
                        'users.image as user_image',
                        'users.fname as user_fname',
                        'users.lname as user_lname',
                        'role_user.role_id',
                    )
                    ->get();

                $counter = $updatedNotifications->count();
            @endphp

            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                <span class="badge badge-danger" id="notificationCounter">{{ $counter }}</span>
                <i data-feather="bell" class="bell"></i>
            </a>

            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                @if ($counter > 0)
                    @foreach ($updatedNotifications as $notification)
                        <a href="#" class="dropdown-item dropdown-item-unread">
                            <span class="dropdown-item-icon bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="dropdown-item-desc">
                                <strong>{{ $notification->title }}</strong>
                                <p>{{ $notification->description }}</p>
                            </span>
                        </a>
                        @php
                            $notification->update(['seen_by' => now()]);
                        @endphp
                    @endforeach
                @else
                    <p class="text-center m-3">No new notifications</p>
                @endif
            </div>
        </li>

        @auth('admin')
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="User Image"
                        src="{{ Auth::guard('admin')->user()->image ? asset(Auth::guard('admin')->user()->image) : asset('admin/assets/img/user.png') }}"
                        class="user-img-radious-style mt-2">
                    <span class="d-sm-none d-lg-inline-block"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pullDown">
                    <div class="dropdown-title">{{ Auth::guard('admin')->user()->name }}</div>
                    <a href="{{ url('admin/profile') }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i>Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('admin/logout') }}" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                </div>
            </li>
        @endauth

        @auth('web')
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <!-- Use web user's image if available, otherwise use default -->
                    <img alt="image"
                        src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('web/assets/images/default-user.png') }}"
                        class="user-img-radious-style mt-2">
                    <span class="d-sm-none d-lg-inline-block"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pullDown">
                    <div class="dropdown-title">{{ Auth::user()->fname }}</div>
                    <a href="{{ url('admin/profile') }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i>Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('admin/logout') }}" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                </div>
            </li>
        @endauth
    </ul>
</nav>
