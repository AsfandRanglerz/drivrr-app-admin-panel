@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

    <head>
        <!-- Bootstrap 5.1.3 CDN-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap 5.1.3 CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    </head>
    <style>
        .toggle[data-toggle="toggle"] {
            width: 98.3855px !important;
            height: 35.7986px !important;
        }
    </style>
    <!-- Main Content -->

    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h4>Users</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Business Owners</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Drivers</a>
                    </li>
                </ul>
                {{--owner--}}
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body table-striped table-bordered table-responsive">
                            <table class="table" id="table_id_1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th scope="col" class="text-center">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($data['owner'] as $owners)
                                            @foreach ($owners->question as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Parent loop index for owner -->
                                        <td>{{ $owners->fname . ' ' . $owners->lname }}</td>
                                        <td>{{ $owners->email }}
                                        <td>{{ $question->title }}</td>
                                        <td>{{ $question->details }}</td>
                                        <td class="text-center">
                                            {{-- <button id={{ $owners->id }} type="button" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                <span class=" fa fa-pen"></span>
                                            </button> --}}
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1-{{ $owners->id }}">
                                                <span class="fa fa-pen"></span>
                                            </button>

                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{--driver--}}
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body table-striped table-bordered table-responsive">
                            <table class="table" id="table_id_2">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th scope="col" class="text-center">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($data['driver'] as $drivers)
                                            @foreach ($drivers->question as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Parent loop index for owner -->
                                        <td>{{ $drivers->fname . ' ' . $drivers->lname }}</td>
                                        <td>{{ $drivers->email }}
                                        <td>{{ $question->title }}</td>
                                        <td>{{ $question->details }}</td>
                                        <td class="text-center">
                                            {{-- @dd($drivers->id) --}}
                                            {{-- <button id={{ $drivers->id }} type="button" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                                <span class=" fa fa-pen"></span>
                                            </button> --}}
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2-{{ $drivers->id }}">
                                                <span class="fa fa-pen"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Owner Modal  -->
@foreach ($data['owner'] as $owners)
    <div class="modal fade" id="exampleModal1-{{ $owners->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-1 border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('send-response.send', $owners->id) }}">
                        @csrf
                        <input type="text" value="{{ $owners->id }}">
                        <div class="mb-2">
                            <h5>Message</h5>
                            <textarea class="form-control" id="text_area1" rows="3" name="message" required></textarea>
                        </div>
                        <div class="text-end mt-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
            {{-- <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('send-response.send', $owners->id) }}">
                            @csrf
                            <input type="text" value="{{ $owners->id }}">
                            <div class="mb-2">
                                <h5>Message</h5>
                                <textarea class="form-control" id="text_area1" rows="3" name="message" required></textarea>
                            </div>
                            <div class="text-end mt-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}
        <!--  Driver Modal  -->
 @foreach ($data['driver'] as $drivers)
            <div class="modal fade" id="exampleModal2-{{ $drivers->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header pb-1 border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('send-response.send', $drivers->id) }}">
                                @csrf
                                <input type="text" value="{{ $drivers->id }}">
                                <div class="mb-2">
                                    <h5>Message</h5>
                                    <textarea class="form-control" id="text_area1" rows="3" name="message" required></textarea>
                                </div>
                                <div class="text-end mt-1">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endforeach

        {{-- <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('send-response.send',$drivers->id)}}">
                            @csrf
                            <input type="text" name="" value="{{ $drivers->id }}">
                            <div class="mb-2">
                                <h5>Message</h5>
                                <textarea class="form-control" id="text_area2" rows="3" name="message"></textarea>
                            </div>
                            <div class="text-end mt-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

@endsection



@section('scripts')

    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif


    <script>
        $(document).ready(function() {
            $('#table_id_1').DataTable();
            $('#table_id_2').DataTable();
            $('#table_id_3').DataTable();
        });
    </script>
@section('js')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

{{-- <script type="text/javascript">
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
    </script> --}}

{{-- <script>
        $('.query-modal').click(function() {
            alert('fff');
            var id = $(this).attr('id');
            alert(id)
;
            $.ajax({
                type: "GET",
                dataType: "json",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}', // Corrected csrf_token() usage
                },
                url: "{{ url('user/document-modal') }}",
                data: {
                    'id': id,
                },
                success: function(response) {
                    $("#mymodal").html(response);
                }
            });
        });
    </script> --}}
{{-- <script>
        $(document).ready(function() {
            $('.open-modal').click(function() {
                var userId = $(this).data('user-id');

                // Send an Ajax request to get user information by ID
                $.ajax({
                    type: 'GET',
                    url: '/get-user-info/' + userId, // Replace with your route
                    success: function(response) {
                        $('.modal-body').html(response);
                        $('#myModal').modal('show');
                    },
                    error: function() {
                        alert('Error loading user information.');
                    }
                });
            });
        });
    </script> --}}


@endsection
