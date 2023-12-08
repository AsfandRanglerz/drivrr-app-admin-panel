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
                                    <h4>Sub Admin</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (auth()->guard('web')->check() &&
                                        auth()->guard('web')->user()->can('create') &&
                                        auth()->guard('web')->user()->hasRole('subadmin'))
                                    <a class="btn btn-success mb-3" href="{{ route('subAdmin.create') }}">Add Sub Admin</a>
                                @elseif (auth()->guard('admin')->check())
                                    <a class="btn btn-success mb-3" href="{{ route('subAdmin.create') }}">Add Sub Admin</a>
                                @endif

                                <table class="table text-center" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Permssion</th>
                                            {{-- <th>Image</th> --}}
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $subAdmin)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $subAdmin->fname }}</td>
                                                <td>{{ $subAdmin->lname }}</td>
                                                <td>{{ $subAdmin->phone }}</td>
                                                <td>{{ $subAdmin->email }}</td>

                                                <td>
                                                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#permissionModal{{ $subAdmin->id }}"
                                                        data-role-id="{{ $subAdmin->id }}">
                                                        <span class=" fa fa-pen"></span>
                                                    </button> --}}
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#updatePermissionModal{{ $subAdmin->id }}"
                                                        data-user-id="{{ $subAdmin->id }}">
                                                        <span class=" fa fa-pen"></span>
                                                    </button>
                                                </td>

                                                <td>
                                                    @if ($subAdmin->is_active == 1)
                                                        <div class="badge badge-success badge-shadow">Accepted</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Rejected</div>
                                                    @endif
                                                </td>

                                                <td
                                                    style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if (auth()->guard('web')->check() &&
                                                            auth()->guard('web')->user()->can('status') &&
                                                            auth()->guard('web')->user()->hasRole('subadmin'))
                                                        @if ($subAdmin->is_active == 1)
                                                            <a href="{{ route('subAdmin.status', ['id' => $subAdmin->id]) }}"
                                                                class="btn btn-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('subAdmin.status', ['id' => $subAdmin->id]) }}"
                                                                class="btn btn-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    @elseif (auth()->guard('admin')->check())
                                                        @if ($subAdmin->is_active == 1)
                                                            <a href="{{ route('subAdmin.status', ['id' => $subAdmin->id]) }}"
                                                                class="btn btn-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('subAdmin.status', ['id' => $subAdmin->id]) }}"
                                                                class="btn btn-danger">
                                                                <svg xmlns="http://w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3">
                                                                    </circle>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    @endif




                                                    @if (auth()->guard('web')->check() &&
                                                            auth()->guard('web')->user()->can('edit') &&
                                                            auth()->guard('web')->user()->hasRole('subadmin'))
                                                        <a class="btn btn-info"
                                                            href="{{ route('subAdmin.edit', $subAdmin->id) }}">Edit</a>
                                                    @elseif (auth()->guard('admin')->check())
                                                        <a class="btn btn-info"
                                                            href="{{ route('subAdmin.edit', $subAdmin->id) }}">Edit</a>
                                                    @endif
                                                    @if (auth()->guard('web')->check() &&
                                                            auth()->guard('web')->user()->can('delete') &&
                                                            auth()->guard('web')->user()->hasRole('subadmin'))
                                                        <form method="post"
                                                            action="{{ route('subAdmin.destroy', $subAdmin->id) }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    @elseif (auth()->guard('admin')->check())
                                                        <form method="post"
                                                            action="{{ route('subAdmin.destroy', $subAdmin->id) }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    @endif

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
    {{-- Permissions Modal --}}
    @foreach ($data as $subAdmin)
        <div class="modal fade" id="permissionModal{{ $subAdmin->id }}" tabindex="-1"
            aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="permissionModalLabel">Add Permissions to {{ $subAdmin->email }}
                        </h1>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <form class="permissions-form"
                            action="{{ route('user.assign.permissions', ['user' => $subAdmin->id]) }}" method="POST">
                            @csrf
                            {{-- Add permissions selection checkboxes here --}}
                            <div class="form-group">
                                {{-- <label>Permissions</label> --}}
                                @foreach ($permissions as $permission)
                                    <div class="row col-8">
                                        <input type="checkbox" class="form-check col-md-4" name="permissions[]"
                                            value="{{ $permission->id }}">
                                        <label class="form-check col-md-4">{{ $permission->name }}</label>

                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
    {{-- UpdatePermission Modal --}}
    @foreach ($data as $subAdmin)
        <div class="modal fade" id="updatePermissionModal{{ $subAdmin->id }}" tabindex="-1"
            aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="permissionModalLabel">Update Permissions to
                            {{ $subAdmin->email }}
                        </h1>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('user.update.permissions', ['user' => $subAdmin->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Permissions</label>
                                @foreach ($permissions as $permission)
                                    <div class="row col-8">
                                        <input type="checkbox" class="form-check col-md-4" name="permissions[]"
                                            value="{{ $permission->id }}"
                                            {{ $subAdmin->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label col-md-4">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Modal footer with close button -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
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
        $(document).ready(function() {
            $('form.permissions-form').on('submit', function(e) {
                e.preventDefault();
                var $form = $(this);
                var url = $form.attr('action');
                var data = $form.serialize();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);
                        $form.closest('.modal').modal('hide');
                    },
                    error: function(error) {

                        console.error(error);
                    }
                });
            });
        });
    </script>
    {{-- Ajax Update Code  --}}
    <script>
        $(document).ready(function() {
            $('.open-modal').click(function() {
                var userId = $(this).data('user-id');

                $('#permissionModalForm input[type="checkbox"]').prop('checked', false);
                $.get('/user/' + userId + '/current-permissions', function(data) {
                    if (data.success) {
                        $.each(data.permissions, function(index, permission) {
                            $('#permission-' + permission).prop('checked', true);
                        });
                    }
                });
            });

            $('#permissionModalForm').submit(function(e) {
                e.preventDefault();
                var userId = $(this).data('user-id');
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '/update-permissions/' + userId,
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                        $('#updatePermissionModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>

    {{-- End Role Ajax --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endsection
