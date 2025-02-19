@extends('admin.layout.app')
@section('title', 'Profile')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="padding-20">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                                            aria-selected="false">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings"
                                            role="tab" aria-selected="true">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content tab-bordered" id="myTab3Content">
                                    <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                                        <div class="row">

                                            @if (isset($user))
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Full Name</strong>
                                                    <br>
                                                    @if (auth()->guard('web')->check())
                                                        <p class="text-muted">{{ $user->fname }} {{ $user->lname }}</p>
                                                    @elseif(auth()->guard('admin')->check())
                                                        <p class="text-muted">{{ $user->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Mobile</strong>
                                                    <br>
                                                    @if (isset($user->phone))
                                                        <p class="text-muted">{{ $user->phone }}</p>
                                                    @else
                                                        <p class="text-muted">Phone not provided.</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Email</strong>
                                                    <br>
                                                    <p class="text-muted">{{ $user->email }}</p>
                                                </div>
                                            @else
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Full Name</strong>
                                                    <br>
                                                    <p class="text-muted">Data not found or not provided.</p>
                                                </div>
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Mobile</strong>
                                                    <br>
                                                    <p class="text-muted">Phone not provided.</p>
                                                </div>
                                                <div class="col-md-3 col-6 b-r">
                                                    <strong>Email</strong>
                                                    <br>
                                                    <p class="text-muted">Email not provided.</p>
                                                </div>
                                            @endisset

                                    </div>
                                </div>
                                <div class="tab-pane fade active show" id="settings" role="tabpanel"
                                    aria-labelledby="profile-tab2">
                                    <form method="post" action="{{ url('admin/update-profile') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header">
                                            <h4>Edit Profile</h4>
                                        </div>
                                        @auth('web')
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name"
                                                            value="{{ $user->fname }} {{ $user->lname }}"
                                                            class="form-control">
                                                        @error('name')
                                                            <div class="text-danger">
                                                                Please fill in the Name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" name="email" value="{{ $user->email }}"
                                                            class="form-control">
                                                        @error('email')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-7 col-12">
                                                        <label>Profile Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image" class="custom-file-input"
                                                                id="customFile">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>

                                                        <div class="invalid-feedback">

                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-5 col-12">
                                                        <label>Phone</label>
                                                        <input type="tel" name="phone" value="{{ $user->phone }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endauth
                                        @auth('admin')
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name" value="{{ $user->name }}"
                                                            class="form-control">
                                                        @error('name')
                                                            <div class="text-danger">
                                                                Please fill in the Name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <label>Email</label>
                                                        <input type="email" name="email" value="{{ $user->email }}"
                                                            class="form-control">
                                                        @error('email')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-7 col-12">
                                                        <label>Profile Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image"
                                                                class="custom-file-input" id="customFile">
                                                            <label class="custom-file-label" for="customFile">Choose
                                                                file</label>
                                                        </div>

                                                        <div class="invalid-feedback">

                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-5 col-12">
                                                        <label>Phone</label>
                                                        <input type="tel" name="phone" value="{{ $user->phone }}"
                                                            class="form-control" value="">
                                                        @error('phone')
                                                            <div class="text-danger">
                                                                Please fill in the email
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endauth
                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn btn-dark">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
