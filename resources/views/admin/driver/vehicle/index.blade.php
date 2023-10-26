@extends('admin.layout.app')
@section('title', 'index')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Vehicles</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                                <a class="btn btn-success mb-3" href="{{ route('driver-vehicle.create',$data->id) }}">Add Vehicle</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Year</th>
                                            <th>Lisense</th>
                                            <th>Color</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($data->driverVehicle as $vehicle)
                                            <tr>
                                                @if($vehicle)
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $vehicle->vehicle->name}}</td>
                                                <td>{{ $vehicle->vehicle_brand}}</td>
                                                <td>{{ $vehicle->model}}</td>
                                                <td>{{ $vehicle->year}}</td>
                                                <td>{{ $vehicle->license_plate}}</td>
                                                <td>{{ $vehicle->color}}</td>
                                                {{-- <td>
                                                    <a href="{{route('driver-vehicle.show', $vehicle->id,$data->id)}}">View</a>
                                                </td> --}}
                                                {{-- @dd([$vehicle->id,$data->id]); --}}
                                                <td
                                                style="display: flex;align-items: center;justify-content: center;column-gap: 8px">

                                                @if ($vehicle->is_active == 1)
                                                    <a href="{{ route('driver-vehicle.status', ['id' => $vehicle->id ]) }}"
                                                        class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor"
                                                            stroke-width="2"stroke-linecap="round"
                                                            stroke-linejoin="round"class="feather feather-toggle-left">
                                                            <rect x="1" y="5" width="22"
                                                                height="14" rx="7" ry="7"></rect>
                                                            <circle cx="16" cy="12" r="3">
                                                            </circle>
                                                        </svg></a>
                                                @else
                                                    <a href="{{ route('driver-vehicle.status', ['id' => $vehicle->id]) }}"
                                                        class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-toggle-right">
                                                            <rect x="1" y="5" width="22"
                                                                height="14" rx="7" ry="7"></rect>
                                                            <circle cx="8" cy="12" r="3">
                                                            </circle>
                                                        </svg></a>
                                                @endif
                                                <a class="btn btn-info"
                                                    href="{{ route('driver-vehicle.edit', $vehicle->id) }}">Edit</a>
                                                <form method="post"
                                                    action="{{ route('driver-vehicle.destroy', $vehicle->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                        data-toggle="tooltip">Delete</button>
                                                </form>
                                            </td>
                                                @endif
                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable()

        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
@endsection
