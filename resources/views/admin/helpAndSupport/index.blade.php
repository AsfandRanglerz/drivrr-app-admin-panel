@extends('admin.layout.app')
@section('title', 'Help&Support')
@section('content')
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
                {{-- owner --}}
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body table-striped table-bordered table-responsive">
                            <table class="table" class="table-1">
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
                                        <td>{{ $owners->email }}</td>
                                        <td>{{ $question->title }}</td>
                                        <td>{{ $question->details }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal1-{{ $owners->id }}-{{ $question->id }}">
                                                <span class="fa fa-pen"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- Modal for each question --}}
                                    <div class="modal fade" id="exampleModal1-{{ $owners->id }}-{{ $question->id }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header pb-1 border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('send-response.send', ['id' => $owners->id, 'q_id' => $question->id]) }}">
                                                        @csrf
                                                        {{-- <input type="text" value="{{ $drivers->id }}"> --}}
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
                                    @endforeach

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{-- driver --}}
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body table-striped table-bordered table-responsive">
                            <table class="table" class="table-1">
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
                                        <td>{{ $drivers->email }}</td>
                                        <td>{{ $question->title }}</td>
                                        <td>{{ $question->details }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal2-{{ $drivers->id }}-{{ $question->id }}">
                                                <span class="fa fa-pen"></span>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal for each question --}}
                                    <div class="modal fade" id="exampleModal2-{{ $drivers->id }}-{{ $question->id }}"
                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header pb-1 border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('send-response.send', ['id' => $drivers->id, 'q_id' => $question->id]) }}">
                                                        @csrf
                                                        {{-- <input type="text" value="{{ $drivers->id }}"> --}}
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
                                    @endforeach
                                    </tr>

                                </tbody>
                            </table>
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
