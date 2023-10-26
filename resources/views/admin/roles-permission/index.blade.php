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
                                    <h4>Roles&Permissions</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a> --}}

                                <button class="btn btn-success mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addRoleModal">Add
                                    Roles</button>
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Role</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                {{-- <a href="" class="btn btn-primary">Edit</a>
                                            <a href="" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this role?')">Delete</a> --}}
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#permissionModal{{ $role->id }}"
                                                    data-role-id="{{ $role->id }}">
                                                    Add Permissions
                                                </button>

                                                <button type="button" class="btn btn-primary"
                                                    data-role-id="{{ $role->id }}" data-toggle="modal"
                                                    data-target="#updatePermissionModal{{ $role->id }}">
                                                    Update Permissions
                                                </button>



                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- Add Role Modal --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Role</h1>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addRole') }}" method="post" id="addRoleForm">
                        @csrf
                        <div class="mb-3 row col-12">
                            <div class="col-sm-12">
                                <input type="text" name="role" placeholder="Enter Role">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- End Role Modal --}}
    {{-- Add Permission Model --}}
    @foreach ($roles as $role)
        <div class="modal fade" id="permissionModal{{ $role->id }}" tabindex="-1"
            aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="permissionModalLabel">Add Permissions to {{ $role->name }}
                        </h1>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('roles.assign.permissions', ['role' => $role->id]) }}">
                            @csrf
                            {{-- Add permissions selection checkboxes here --}}
                            <div class="form-group">
                                {{-- <label>Permissions</label> --}}
                                @foreach ($permissions as $permission)
                                    <div class="row col-6">
                                        <input type="checkbox" class="form-check col-md-3" name="permissions[]"
                                            value="{{ $permission->id }}">
                                        <label class="form-check col-md-3">{{ $permission->name }}</label>

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
    {{-- End Permssion Model --}}
    {{-- Update Permissions --}}
    @foreach ($roles as $role)
        <div class="modal fade" id="updatePermissionModal{{ $role->id }}" tabindex="-1"
            aria-labelledby="updatePermissionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal header and title -->
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updatePermissionModalLabel">Update Permissions for
                            {{ $role->name }}</h1>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button> --}}
                    </div>
                    <!-- Modal body with permissions checkboxes -->
                    <div class="modal-body">
                        <form method="post" action="{{ route('roles.update.permissions', ['role' => $role->id]) }}">
                            @csrf
                            @method('PUT') <!-- Use the PUT method to update permissions -->

                            <div class="form-group">
                                <label>Permissions</label>
                                @foreach ($permissions as $permission)
                                    <div class="row col-6">
                                        <input type="checkbox" class="form-check col-md-3" name="permissions[]"
                                            value="{{ $permission->id }}"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label col-md-3">{{ $permission->name }}</label>
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

    {{-- End Update Permsission --}}
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
    <!-- Add Role Ajax -->
    <script>
        $(document).ready(function() {
            $('#addRoleForm').on('submit', function(event) {
                event.preventDefault();

                var role = $('input[name="role"]').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('addRole') }}',
                    data: {
                        'role': role,
                        '_token': csrfToken
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#addRoleModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>

    <!-- Assign Permissions -->
    <script>
        $(document).ready(function() {
            $('form.permission-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serializeArray();
                var roleId = $(this).data('role-id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('roles.assign.permissions', ['role' => ':roleId']) }}'.replace(
                        ':roleId', roleId),
                    data: formData,
                    success: function(response) {
                        $('#permissionModal' + roleId).modal('hide');
                        // toastr.success(response.message);

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <!-- Update Permissions -->
    <script>
        $(document).ready(function() {
            $('form.update-permission-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serializeArray();
                var roleId = $(this).data('role-id');

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('roles.update.permissions', ['role' => ':roleId']) }}'.replace(
                        ':roleId', roleId),
                    data: formData,
                    success: function(data) {
                        $('#updatePermissionModal' + roleId).modal('hide');
                        // toastr.success(response.message);

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>


    {{-- End Role Ajax --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
@endsection
