@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{ route('businessOwner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Business Owner</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>First Name</label>
                                                <input type="text" placeholder="First Name" name="fname"
                                                    id="company_name" value="{{ old('fname') }}"
                                                    class="form-control">
                                                @error('fname')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Last Name" name="lname"
                                                    id="first_name" value="{{ old('lname') }}" class="form-control">
                                                @error('lname')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Phone</label>
                                                <input type="text" placeholder="Phone" name="phone"
                                                    id="phone" value="{{ old('phone') }}" class="form-control">
                                                @error('phone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Email</label>
                                                <input type="email" placeholder="Email" name="email"
                                                    id="email" value="{{ old('email') }}" class="form-control">
                                                @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Email</label>
                                                <input type="email" placeholder="Email" name="email" id="email"
                                                    value="{{ old('email') }}" class="form-control" />
                                                @error('email')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Designation</label>
                                                <input type="text" placeholder="Designation" name="designation"
                                                    id="designation" value="{{ old('designation') }}"
                                                    class="form-control" />
                                                @error('designation')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Company Name</label>
                                                <input type="text" placeholder="Company Name" name="company_name"
                                                    id="company_name" value="{{ old('company_name') }}" class="form-control">
                                                @error('company_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Choose Image</label>
                                                <input type="file" name="image" value="{{ old('image') }}"
                                                    class="form-control">
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Company Info</label>
                                                <textarea type="text" placeholder="Company Info" name="company_info"
                                                    id="company_info" value="{{ old('company_info') }}" class="form-control"></textarea>
                                                @error('company_info')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </body>
@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
@endsection
