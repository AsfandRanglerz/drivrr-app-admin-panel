@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    {{-- <a class="btn btn-primary mb-3" href="{{route('category.index')}}">Back</a> --}}
                    <form id="add_student" action="{{ route('vehicle.update', $vehicle['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @method('PUT')

                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Vehicle</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label> Name<span class="text-danger"> *</span></label>
                                                <input type="text" name="name" id="name"
                                                    Value="{{ $vehicle['name'] }}"class="form-control">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Update</button>
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
