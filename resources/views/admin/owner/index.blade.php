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
                                    <h4>Business Owner</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('businessOwner.create') }}">Add Business
                                    Owner</a>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Image</th>
                                            <th>Jobs</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $owner)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $owner->fname }}</td>
                                                <td>{{ $owner->lname }}</td>
                                                <td>{{ $owner->phone }}</td>
                                                <td>{{ $owner->email }}</td>
                                                <td>
                                                    @if (file_exists(public_path($owner->image)))
                                                    <img src="{{ asset($owner->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                @else
                                                    <img src="{{ asset('public/admin/assets/images/users/owner.jpg') }}"
                                                        alt="" height="50" width="50" class="image">
                                                @endif
                                                </td>


                                                <td>
                                                    <a href="{{ route('owner-job.index', $owner->id) }}">View</a>
                                                </td>

                                                {{-- <td>{{ $owner->designation }}</td> --}}
                                                <td>
                                                    @if ($owner->is_active == 1)
                                                        <div class="badge badge-success badge-shadow">Active</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">DeActive</div>
                                                    @endif
                                                </td>

                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if ($owner->is_active == 1)
                                                        <a href="{{ route('owner.status', ['id' => $owner->id]) }}"
                                                            class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor"
                                                                stroke-width="2"stroke-linecap="round"
                                                                stroke-linejoin="round"class="feather feather-toggle-left">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="16" cy="12" r="3">
                                                                </circle>
                                                            </svg></a>
                                                    @else
                                                        <a href="{{ route('owner.status', ['id' => $owner->id]) }}"
                                                            class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-right">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="8" cy="12" r="3">
                                                                </circle>
                                                            </svg></a>
                                                    @endif
                                                    <a class="btn btn-info"
                                                        href="{{ route('businessOwner.edit', $owner->id) }}">Edit</a>
                                                    <form method="post"
                                                        action="{{ route('businessOwner.destroy', $owner->id) }}">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
                                                    </form>
                                                </td>
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
