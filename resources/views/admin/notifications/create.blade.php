@extends('admin.layout.app')
@section('title', 'Add Notification')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Notification</h4>
                                    <form action="{{ route('notifications.store') }}" method="POST">
                                        <div class="row mx-0 px-4">
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Title</label>
                                                    <input type="text" placeholder="Title" name="title" id="title"
                                                        value="{{ old('title') }}" class="form-control">
                                                    @error('title')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Send To</label>
                                                    <select name="role_id[]" id="user_role" class="form-control selectric"
                                                        multiple="">
                                                        <option value="">Select Option</option>
                                                        <option value="1">Subadmin</option>
                                                        <option value="2">Business Owner</option>
                                                        <option value="3">Driver</option>
                                                    </select>
                                                    @error('user_role')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mx-0 px-4">
                                            <div class="col-sm-12 pl-sm-0 pr-sm-3 col-md-12 col-lg-12">
                                                <div class="form-group mb-2">
                                                    <label>Description</label>
                                                    <textarea type="text" placeholder="Description" name="description" id="description" value="{{ old('description') }}"
                                                        class="form-control"></textarea>
                                                    @error('description')
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
                                    </form>

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
