@extends('admin.layout.app')
@section('title', 'index')
@section('content')

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Jobs</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                                {{-- <a class="btn btn-success mb-3" href="{{ route('driver-vehicle.create',$data->id) }}">Add Vehicle</a> --}}
                                <table class="table table-striped table-bordered  text-center" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Vehicle</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Hours</th>
                                            <th>Days</th>
                                            <th>Price</th>
                                            <th>Description</th>
                                            <th>Own Vehicle</th>
                                            <th>Order</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data->job as $item)
                                            <tr>
                                                @if ($item)
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->vehicle->name }}</td>
                                                    <td> {!! strlen($item->location) > 10 ? substr($item->location, 0, 10) . '...' : $item->location !!}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->time }}</td>
                                                    <td>{{ $item->hours }}</td>
                                                    <td>{{ $item->days }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td> {!! strlen($item->description) > 10 ? substr($item->description, 0, 10) . '...' : $item->description !!}</td>
                                                    @if ($item->on_vehicle == 1)
                                                        <td>
                                                            <span>Needed</span>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span>No Need</span>
                                                        </td>
                                                    @endif


                                                    <td>
                                                        @if ($item->is_active == 0)
                                                            <div class="badge badge-success badge-shadow">Posted</div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">Cancled</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class=""
                                                            style="display: flex;align-items: center;justify-content: center;column-gap: 8px">

                                                            <button class="view text-info btn btn-secondary fa fa-eye"
                                                                data-patient-id="{{ $item->id }}"></button>

                                                            {{-- </form> --}}

                                                            @if ($item->is_active == 0)
                                                                <a href="{{ route('owner-job.status', ['id' => $item->id, $data->id]) }}"
                                                                    class="btn btn-success"><svg
                                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor"
                                                                        stroke-width="2"stroke-linecap="round"
                                                                        stroke-linejoin="round"class="feather feather-toggle-left">
                                                                        <rect x="1" y="5" width="22" height="14"
                                                                            rx="7" ry="7"></rect>
                                                                        <circle cx="16" cy="12" r="3">
                                                                        </circle>
                                                                    </svg></a>
                                                            @else
                                                                <a href="{{ route('owner-job.status', ['id' => $item->id, $data->id]) }}"
                                                                    class="btn btn-danger"><svg
                                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-toggle-right">
                                                                        <rect x="1" y="5" width="22" height="14"
                                                                            rx="7" ry="7"></rect>
                                                                        <circle cx="8" cy="12" r="3">
                                                                        </circle>
                                                                    </svg></a>
                                                            @endif
                                                            <a class="btn btn-info"
                                                                href="{{ route('owner-job.edit', $item->id) }}">Edit</a>
                                                            <form method="post"
                                                                action="{{ route('owner-job.destroy', $item->id) }}">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
                                                        </div>
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




        </section>
        {{-- @foreach ($data->job as $item)
        <div class="col-md-4 col-sm-4  profile_details">
            <div class="well profile_view">
                <div class="col-sm-12">
                    <h4 class="brief"><i>Patients Strategist</i></h4>
                    <div class="left col-md-7 col-sm-7">
                        <input type="text" placeholder="Pickup" value="{{$item->pickup}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Destination" value="{{$item->destination}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Date" value="{{$item->date}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Time" value="{{$item->time}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Duration" value="{{$item->duration}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Service Type" value="{{$item->service_type}}" class="form-control mb-2" readonly>
                        <input type="text" placeholder="Price" value="{{$item->price}}" class="form-control mb-2" readonly>
                        <textarea placeholder="Description" class="form-control mb-2" rows="3" readonly>{{$item->description}}</textarea>
                    </div>

                </div>

            </div>
        </div>
        @endforeach --}}
        <div class="modal fade" id="patientViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background: none; border: none; float: right; ">
                            <span style="color: red;">&#x274C;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="x_content">
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-3">
                                            <h2>Job Details</h2>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="pickup">Location</label>
                                            <input type="text" id="location" placeholder="location"
                                                class="form-control mb-2 location" readonly>

                                            <label for="destination">Date</label>
                                            <input type="text" id="date" placeholder="Destination"
                                                class="form-control mb-2 date" readonly>

                                            <label for="date">Time</label>
                                            <input type="text" id="time" placeholder="Date"
                                                class="form-control mb-2 time" readonly>

                                            <label for="time">Hours</label>
                                            <input type="text" id="hours" placeholder="Time"
                                                class="form-control mb-2 hours" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="duration">Days</label>
                                            <input type="text" id="days" placeholder="Duration"
                                                class="form-control mb-2 days" readonly>

                                            <label for="service_type">Price</label>
                                            <input type="text" id="price" placeholder="Service Type"
                                                class="form-control mb-2 price" readonly>


                                        </div>

                                        <div class="col-md-12">
                                            <label for="description">Description</label>
                                            <textarea id="description" placeholder="Description" class="form-control mb-4 description" style="height: 120px;"
                                                readonly></textarea>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <div class="modal-body">
                            <div class="mb-2">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mt-3">
                                    <div class="d-flex justify-content-center">
                                        <button >Approved</button>
                                        <button>Rejected</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>

@endsection

@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

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

    <script>
        $(document).ready(function() {
            $(document).on('click', '.view', function(e) {

                e.preventDefault();
                var id = $(this).data('patient-id');
                console.log("ids", id);




                $.ajax({
                    type: "GET",
                    url: "{{ URL::to('/admin/active-job') }}", // Assuming correct endpoint and using the patientId
                    data: {
                        id: id,
                    },

                    success: function(response) {
                        $('#patientViewModal').modal('show');
                        // console.log(response);
                        // Assuming response contains a 'status' key
                        if (response.status == 200) {
                            $('.location').val(response.jobs.location);
                            $('.date').val(response.jobs.date);
                            $('.time').val(response.jobs.time);
                            $('.hours').val(response.jobs.hours);
                            $('.days').val(response.jobs.days);
                            $('.price').val(response.jobs.price);
                            $('.description').val(response.jobs.description);
                            // $('.description').val(response.jobs.description);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error); // Log any errors for debugging
                    }
                });
            });
        });
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script> --}}

@endsection
