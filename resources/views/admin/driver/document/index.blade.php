@extends('admin.layout.app')
@section('title', 'Documents')
@section('content')

    <head>
        <!-- Bootstrap 5.1.3 CDN-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap 5.1.3 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    </head>
    <style>
        .btn-danger {
            background-color: #fc544b;
            border-color: #fc544b;
        }

        .badge {
            vertical-align: middle;
            padding: 7px 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border-radius: 30px;
            font-size: 12px;
        }
    </style>
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Documents</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                                <a class="btn btn-success mb-3" href="{{ route('document.create', $data->id) }}">Add
                                    Document</a>
                                <table class="table table-striped table-bordered text-center" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->document as $document)
                                            <tr>
                                                @if ($document)
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $document->name }}</td>
                                                    @if ($document->image)
                                                        <td>
                                                            <a href="{{ asset($document->image) }}" target="_blank">
                                                                <img src="{{ asset($document->image) }}" alt=""
                                                                    height="50" width="50" class="image">
                                                            </a>
                                                        </td>
                                                    @endif

                                                    <td>
                                                        @if ($document->is_active == 0)
                                                            <div class="badge  badge-shadow btn-warning text-black">Pending
                                                            </div>
                                                        @elseif ($document->is_active == 1)
                                                            <div class="badge badge-success badge-shadow">Accepted</div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">Rejected</div>
                                                        @endif
                                                    </td>

                                                    <td
                                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                        @if ($document->is_active == 0)
                                                            <a href="{{ route('document.status', ['id' => $document->id, 'key' => $data->id, 'check' => 1]) }}"
                                                                class="btn btn-success text-white">
                                                                &#x2713
                                                            </a>

                                                            <a href="{{ route('document.status', ['id' => $document->id, 'key' => $data->id, 'check' => 2]) }}"
                                                                data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                                class="btn btn-danger">
                                                                &#10005
                                                            </a>
                                                        @elseif ($document->is_active == 1)
                                                            <a href="{{ route('document.status', ['id' => $document->id, $data->id, 'check' => 2]) }}"
                                                                class="btn btn-success" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="28"
                                                                    height="23" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"stroke-linecap="round"
                                                                    stroke-linejoin="round"class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3">
                                                                    </circle>
                                                                </svg></a>
                                                        @else
                                                            <a href="{{ route('document.status', ['id' => $document->id, $data->id, 'check' => 1]) }}"
                                                                class="btn btn-danger"><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="28"
                                                                    height="23" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3">
                                                                    </circle>
                                                                </svg></a>
                                                        @endif
                                                        <a class="btn text-white btn-info"
                                                            href="{{ route('document.edit', $document->id) }}">Edit</a>
                                                        <form method="post"
                                                            action="{{ route('document.destroy', $document->id) }}">
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
            </div>
        </section>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            @if (isset($document) && isset($data))
                                <form action="{{ route('document.status', ['id' => $document->id, 'key' => $data->id]) }}">
                                    @csrf

                                    <input type="hidden" name="check" value="2">
                                    <h5>Reason</h5>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" required name="reason"></textarea>
                        </div>
                        <div class="text-end mt-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                    @else
                        <p>No records found</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        {{-- modal --}}
        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <div class="modal-body">
                            <div class="mb-2">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="text-center mt-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('document.status', ['id' => 1, 'key' => $data->id]) }}"
                                            type="submit" class="btn btn-success mx-2" data-bs-dismiss="modal"
                                            aria-label="Close">Approved</a>
                                        <a href="{{ route('document.status', ['id' => 2, 'key' => $data->id]) }}"
                                            type="submit" class="btn btn-danger mx-2" data-bs-dismiss="modal"
                                            aria-label="Close">Rejected</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
