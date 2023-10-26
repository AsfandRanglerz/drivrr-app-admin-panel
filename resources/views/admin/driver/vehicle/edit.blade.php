@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{ route('driver-vehicle.update', $data['details']->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Vehicle</h4>
                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                                <div class="form-group mb-2">
                                                    <label>Select</label>
                                                    <select type="text" name="vehicle_id" class="form-control">
                                                        @foreach ($data['vehicle'] as $vehicle)
                                                        <option value={{$vehicle->id}} {{$vehicle->id == $data['details']->vehicle_id  ? 'selected' : ''}}>{{$vehicle->name}}</option>
                                                        @endforeach
                                                     </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Vehicle Brand</label>
                                                <input type="text" placeholder="brand" name="vehicle_brand"
                                                    id="name" value="{{$data['details']->vehicle_brand}}" class="form-control">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Model</label>
                                                <input type="text" placeholder="Model" name="model"
                                                    id="model" value="{{ $data['details']->model }}" class="form-control">
                                                @error('model')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Year</label>
                                                <input type="text" placeholder="year" name="year"
                                                    id="year" value="{{ $data['details']->year }}" class="form-control">
                                                @error('year')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Lisence Plate</label>
                                                <input type="text" placeholder="Lisence Plate" name="license_plate"
                                                    id="license_plate" value="{{ $data['details']->license_plate }}" class="form-control">
                                                @error('license_plate')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Color</label>
                                                <input type="text" placeholder="Color" name="color"
                                                    id="color" value="{{ $data['details']->color }}" class="form-control">
                                                @error('color')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    {{-- <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Choose Image</label>
                                                <input type="file" name="image" value="{{ $details->image }}"
                                                    class="form-control">
                                                @error('image')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> --}}


                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            style="justify-content:center"
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

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
@endsection
