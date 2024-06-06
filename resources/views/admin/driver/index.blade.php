@extends('admin.layout.app')
@section('title', 'Drivers')
@section('content')
    {{-- Create Driver Model  --}}
    <div class="modal fade" id="createDriverModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createDriverForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="number" class="form-control" id="phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirmpassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                                        required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" onclick="createDriver()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Driver Modal -->
    <div class="modal fade" id="editDriverModal" tabindex="-1" role="dialog" aria-labelledby="editDriverModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDriverModalLabel">Edit Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editDriverForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="number" class="form-control" id="phone" name="phone">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" onclick="updateDriver()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Driver Modal -->
    <div class="modal fade" id="deleteDriverModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteDriverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDriverModalLabel">Delete Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Driver?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteDriver">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Drivers</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createDriverModal">
                                    Create Drivers
                                </a>
                                <table class="responsive table table-striped table-bordered example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Image</th>
                                            <th>Activation status </th>
                                            <th>Block & Active</th>
                                            <th>Documents</th>
                                            <th>Vehicles</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
    {{-- Data Table --}}
    <script>
        function reloadDataTable() {
            var dataTable = $('.example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('.example').DataTable({
                "ajax": {
                    "url": "{{ route('drivers.get') }}",
                    "type": "POST",
                    "data": {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "fname"
                    },
                    {
                        "data": "lname"
                    },
                    {
                        "data": "email",
                        "render": function(data, type, full, meta) {
                            if (type === 'display') {
                                return '<a href="mailto:' + data + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        "data": "phone",
                        "render": function(data, type, row) {
                            if (data == null) {
                                return "No Number";
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        "data": "image",
                        "render": function(data, type, row) {
                            if (data) {
                                if (data.startsWith("http")) {
                                    return '<img src="' + data +
                                        '" alt="Image" style="width: 50px; height: 50px;">';
                                } else {
                                    return '<img src="https://ranglerzwp.xyz/drivrrapp/' + data +
                                        '" alt="Image" style="width: 50px; height: 50px;">';
                                }
                            } else {
                                return '<img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/images/approve/owner.jpg" alt="Image" style="width: 50px; height: 50px;">';
                            }
                        }
                    },
                    {
                        "data": "is_active",
                        "render": function(data) {
                            var statusText, statusClass;
                            if (data == '1') {
                                statusText = "Active";
                                statusClass = "text-success";
                            } else if (data == '0') {
                                statusText = "Blocked";
                                statusClass = "text-danger";
                            }
                            return '<span class="' + statusClass + '">' + statusText +
                                '</span>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.is_active == '1' ? 'btn-danger' : 'btn-success';
                            var buttonText = row.is_active == '1' ? 'Block' : 'Active';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('document.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-3 text-white"><i class="fas fa-file-alt"></i></a>';
                        },
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('driver-vehicle.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-3 text-white"><i class="fas fa-car"></i></a>';
                        },
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editDriverBtn" data-id="' +
                                row.id + '"><i class="fa fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-3 mr-3 text-white deleteDriverBtn" data-id="' +
                                row.id + '"><i class="fa fa-trash"></i></button>';
                        }
                    }
                ],

            });
            $('.example').on('click', '.editDriverBtn', function() {
                var driverId = $(this).data('id');
                editDriver(driverId);
            });
            $('.example').on('click', '.deleteDriverBtn', function() {
                var driverId = $(this).data('id');
                deleteDriverModal(driverId);
            });
            $('.example').on('click', '.DriverBankInfoModal', function() {
                var driverId = $(this).data('id');
                DriverBankInfoModal(driverId);
            });
        });
    </script>

    <script>
        // ##############Create Driver################
        function createDriver() {
            var formData = new FormData($('#createDriverForm')[0]);
            var createButton = $('#createDriverModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('drivers.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Driver Created Successfully!')
                    $('#createDriverModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value[
                            0]);
                    });
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        // Remove validation messages when Driver starts typing
        $('#createDriverForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        // ######Get & Update Driver#########
        var DriverShowRoute = '{{ route('drivers.show', ':id') }}';
        var DriverUpdateRoute = '{{ route('drivers.update', ':id') }}';

        function editDriver(driverId) {
            $.ajax({
                url: DriverShowRoute.replace(':id', driverId),
                type: 'GET',
                success: function(response) {
                    $('#editDriverForm #fname').val(response.fname);
                    $('#editDriverForm #lname').val(response.lname);
                    $('#editDriverForm #email').val(response.email);
                    $('#editDriverForm #phone').val(response.phone);
                    $('#editDriverModal').modal('show');
                    $('#editDriverModal').data('driverId', driverId);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update Driver#############
        function updateDriver() {
            var driverId = $('#editDriverModal').data('driverId');
            var formData = new FormData($('#editDriverForm')[0]);
            $.ajax({
                url: DriverUpdateRoute.replace(':id', driverId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Driver Updated Successfully!')
                    $('#editDriverModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value[
                            0]);
                    });
                    $('#editDriverModal').modal('hide');
                }
            });
        }
        // ############# Delete Driver###########
        function deleteDriverModal(driverId) {
            $('#confirmDeleteDriver').data('data-id', driverId);
            $('#deleteDriverModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteDriver').click(function() {
                var driverId = $(this).data('data-id');
                deleteDriver(driverId);
            });
        });

        function deleteDriver(driverId) {
            $.ajax({
                url: "{{ route('drivers.delete', ['id' => ':driverId']) }}".replace(':driverId', driverId),
                type: 'GET',
                success: function(response) {
                    toastr.success('Driver Created Successfully!')
                    $('#deleteDriverModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        // ########## Blocked Status Code ############
        $('.example').on('click', '#update-status', function() {
            var button = $(this);
            var userId = button.data('userid');
            var currentStatus = button.text().trim().toLowerCase();
            var newStatus = currentStatus === 'Blocked' ? '0' : '1';
            button.prop('disabled', true);

            $.ajax({
                url: '{{ route('driversBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: newStatus
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus == '1' ? 'Active' : 'Blocked';
                    var buttonClass = newStatus == '1' ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
                    if (newStatus == 0) {
                        statusText = "Blocked";
                        statusClass = "text-danger";
                    } else {
                        statusText = "Active";
                        statusClass = "text-success";
                    }
                    statusCell.html('<span class="' + statusClass + '">' + statusText + '</span>');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                },
                complete: function() {
                    // Enable the button again
                    button.prop('disabled', false);
                }
            });
        });
    </script>

@endsection
