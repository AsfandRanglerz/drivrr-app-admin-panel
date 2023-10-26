@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                    <form id="add_student" action="{{ route('owner-job.update', $data['job']->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Job</h4>
                                    <div class="row mx-0 px-4">

                                        {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                                <div class="form-group mb-2">
                                                    <label>Select</label>
                                                    <select type="text" name="vehicle_id" class="form-control">
                                                        @foreach ($data['vehicle'] as $vehicle)
                                                        <option value={{$vehicle->id}} {{$vehicle->id == $data['details']->vehicle_id  ? 'selected' : ''}}>{{$vehicle->name}}</option>
                                                        @endforeach
                                                     </select>
                                                </div>
                                            </div> --}}

                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Vehicle</label>
                                                    <select type="text" name="vehicle_id" class="form-control">
                                                        @foreach ($data['vehicle'] as $vehicle)
                                                        <option value={{$vehicle->id}} {{$vehicle->id == $data['job']->vehicle_id  ? 'selected' : ''}}>{{$vehicle->name}}</option>
                                                        @endforeach
                                                     </select>
                                                    {{-- <input type="text" placeholder="Vehicle" name="vehicle"
                                                        id="vehicle" value="{{$data->vehicle->name}}" class="form-control">
                                                    @error('vehicle')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror --}}
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Pickup</label>
                                                <input type="text" placeholder="Pickup" name="pickup"
                                                    id="pickup" value="{{$data['job']->pickup}}" class="form-control">
                                                @error('pickup')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Destination</label>
                                                <input type="text" placeholder="Destination" name="destination"
                                                    id="destination" value="{{ $data['job']->destination }}" class="form-control">
                                                @error('destination ')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Date</label>
                                                <input type="text" placeholder="Date" name="date"
                                                    id="date" value="{{ $data['job']->date }}" class="form-control">
                                                @error('date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Time</label>
                                                <input type="text" placeholder="Time" name="time"
                                                    id="time" value="{{ $data['job']->time }}" class="form-control">
                                                @error('time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Duration</label>
                                                <input type="text" placeholder="Duration" name="duration"
                                                    id="duration" value="{{ $data['job']->duration }}" class="form-control">
                                                @error('duration')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Service Type</label>
                                                <input type="text" placeholder="Service Type" name="service_type"
                                                    id="service_type" value="{{ $data['job']->service_type }}" class="form-control">
                                                @error('service_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <input type="text" placeholder="Price" name="price"
                                                    id="price" value="{{ $data['job']->price }}" class="form-control">
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Description</label>
                                                <input type="text" placeholder="Description" name="description"
                                                    id="description" value="{{ $data['job']->description }}" class="form-control">
                                                @error('description')
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
