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
                                    <h4>Jobs</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                                {{-- <a class="btn btn-success mb-3" href="{{ route('driver-vehicle.create',$data->id) }}">Add Vehicle</a> --}}
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Vehicle</th>
                                            <th>Pickup</th>
                                            <th>Destination</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Service_type</th>
                                            <th>Price</th>
                                            <th>Description</th>
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
                                                    <td> {!! strlen($item->pickup) > 10 ? substr($item->pickup, 0, 10) . '...' : $item->pickup !!}</td>
                                                    <td> {!! strlen($item->destination) > 10 ? substr($item->destination, 0, 10) . '...' : $item->destination !!}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->time }}</td>
                                                    <td>{{ $item->duration }}</td>
                                                    <td>{{ $item->service_type }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td> {!! strlen($item->description) > 10 ? substr($item->description, 0, 10) . '...' : $item->description !!}</td>


                                                    <td>
                                                        @if ($item->is_active == 0)
                                                            <div class="badge badge-success badge-shadow">Posted</div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">Cancled</div>
                                                        @endif
                                                    </td>
                                                    <td
                                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    {{-- <a class="btn btn-secondary text-info fa fa-eye"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#permissionModal"
                                                        href="{{route('owner-job.show',$item->id)}}">
                                                </a> --}}
                                                {{-- <form action="" method="GET" id="jobForm"> --}}
                                                    {{-- @csrf --}}
                                                    <button type="submit" id="patientViewModal" data-patient-id="{{ $item['id'] }}" class="btn btn-secondary text-info fa fa-eye"></button>
                                                {{-- </form> --}}

                                                        @if ($item->is_active == 0)
                                                            <a href="{{ route('owner-job.status', ['id' => $item->id,$data->id]) }}"
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
                                                            <a href="{{ route('owner-job.status', ['id' => $item->id,$data->id]) }}"
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
                                                            href="{{ route('owner-job.edit', $item->id,) }}">Edit</a>
                                                        <form method="post"
                                                            action="{{ route('owner-job.destroy', $item->id) }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
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
                        <li><a class="close-link" data-bs-dismiss="modal"><i class="fa fa-close"></i></a>
                        </li>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 ">
                                <div class="x_content">
                                    <div class="col-md-6 col-sm-6  profile_left">

                                        <br />
                                    </div>
                                    <div class="col-md-6 col-sm-6 ">
                                        <div class="profile_title">
                                            <div class="col-md-12 text-center">
                                                <h2><span class="pickupv"></span> Job Details</h2>
                                            </div>
                                        </div>
                                        <br>
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
    <script>
        //=======================================AJAX Code Of View patient Profile==========================
        $(document).on('click', '.view', function(e) {
            e.preventDefault();
            var patientId = $(this).data('patient-id');
            // alert(patientId);
            $('#patientViewModal').modal('show');
            $.ajax({
                type: "GET",
                url: "/active-job/" + patientId,
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        $('#viewpatient_id').val(patientId);
                        $('.pickup').text(response.jobs.pickup);
                        $('.destination').text(response.jobs.destination);
                        $('.date').text(response.jobs.date);
                        $('.time').text(response.jobs.time);
                        $('.duration').text(response.jobs.duration);
                        $('.service_type').text(response.jobs.service_type);
                        $('.price').text(response.jobs.price);
                        $('.description').text(response.jobs.description);
                    }
                }
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

@endsection
