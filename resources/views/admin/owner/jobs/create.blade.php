@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{route('driver-vehicle.store',$data['user']->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Vehicle</h4>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Select</label>
                                                <select type="text" name="vehicle_id" class="form-control">
                                                   @foreach ($data['vehicles'] as $vehicle)
                                                   <option value={{$vehicle->id}} >{{$vehicle->name}}</option>
                                                   @endforeach
                                                </select>
                                                {{-- @error('vehicle_brand')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror --}}
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Vehicle Brand</label>
                                                <input type="text" placeholder="Brand" name="vehicle_brand"
                                                    id="vehicle_brand" value="{{ old('vehicle_brand') }}" class="form-control">
                                                @error('vehicle_brand')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Model</label>
                                                <input type="text" placeholder="Model" name="model"
                                                    id="model" value="{{ old('model') }}" class="form-control">
                                                @error('model')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Year</label>
                                                <input type="text" placeholder="Year" name="year"
                                                    id="year" value="{{ old('year') }}" class="form-control">
                                                @error('year')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Lisence</label>
                                                <input type="text" placeholder="Lisence" name="license_plate"
                                                    id="license_plate" value="{{ old('license_plate') }}" class="form-control">
                                                @error('license_plate')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Color</label>
                                                <input type="text" placeholder="Color" name="color"
                                                    id="color" value="{{ old('color') }}" class="form-control">
                                                @error('color')
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
