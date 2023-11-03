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
                                                        <a class="btn btn-secondary text-info fa fa-eye"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal"
                                                        href="#">
                                                </a>
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
@endsection
