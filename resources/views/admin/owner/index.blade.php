@extends('admin.layout.app')
@section('title', 'BusniessOwners')
@section('content')
    {{-- Create BusniessOwner Model  --}}
    <div class="modal fade" id="createBusniessOwnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Busniess Owner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createBusniessOwnerForm" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control fname" name="fname" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Last Name</label>
                                <input type="text" class="form-control lname" name="lname" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number" class="form-control phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control image" name="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Company Name</label>
                                <input type="text" class="form-control company_name" name="company_name">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Company Info</label>
                                <textarea type="text" class="form-control company_info" name="company_info"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" onclick="createBusniessOwner()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit BusniessOwner Modal -->
    <div class="modal fade" id="editBusniessOwnerModal" tabindex="-1" role="dialog"
        aria-labelledby="editBusniessOwnerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBusniessOwnerModalLabel">Edit BusniessOwner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editBusniessOwnerForm" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number" class="form-control" id="phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Company Info</label>
                                <textarea type="text" class="form-control w-100" id="company_info" name="company_info"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" onclick="updateBusniessOwner()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete BusniessOwner Modal -->
    <div class="modal fade" id="deleteBusniessOwnerModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteBusniessOwnerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteBusniessOwnerModalLabel">Delete BusniessOwner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this BusniessOwner?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger col-md-4 col-sm-4 col-lg-4"
                        id="confirmDeleteBusniessOwner">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Block Reason Modal -->
    <div class="modal fade" id="blockReasonModal" tabindex="-1" role="dialog" aria-labelledby="blockReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockReasonModalLabel">Block Busniess Owner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="blockReason">Please provide a reason for blocking this user:</label>
                        <textarea class="form-control" name="block_reason" id="blockReason" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-12 text-center">
                        <button type="button" id="confirmBlock" class="btn btn-danger">Send</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4>BusniessOwners</h4>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                data-target="#createBusniessOwnerModal">
                                Create BusniessOwners
                            </a>
                            <table class="responsive table table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Company Name</th>
                                        <th>Company Info</th>
                                        <th>Image</th>
                                        <th>Block & Active</th>
                                        <th>Jobs</th>
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
                    "url": "{{ route('busniessOwner.get') }}",
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
                        "data": "company_name",
                        "render": function(data, type, row) {
                            if (data == null) {
                                return "No Company Name Found!";
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        "data": "company_info",
                        "render": function(data, type, row) {
                            if (data == null) {
                                return "No Company Info Found!";
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
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.is_active == '0' ? 'btn-danger' : 'btn-success';
                            var buttonText = row.is_active == '0' ? 'Blocked' : 'Active';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('owner-job.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-3 text-white"><i class="fas fa-broom"></i></a>';
                        },
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editBusniessOwnerBtn" data-id="' +
                                row.id + '"><i class="fa fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-3 mr-3 text-white deleteBusniessOwnerBtn" data-id="' +
                                row.id + '"><i class="fa fa-trash"></i></button>';
                        }
                    }
                ],

            });
            $('.example').on('click', '.editBusniessOwnerBtn', function() {
                var BusniessOwnerId = $(this).data('id');
                editBusniessOwner(BusniessOwnerId);
            });
            $('.example').on('click', '.deleteBusniessOwnerBtn', function() {
                var BusniessOwnerId = $(this).data('id');
                deleteBusniessOwnerModal(BusniessOwnerId);
            });
            $('.example').on('click', '.BusniessOwnerBankInfoModal', function() {
                var BusniessOwnerId = $(this).data('id');
                BusniessOwnerBankInfoModal(BusniessOwnerId);
            });
        });
    </script>

    <script>
        // ##############Create BusniessOwner################
        function createBusniessOwner() {
            var formData = new FormData($('#createBusniessOwnerForm')[0]);
            var createButton = $('#createBusniessOwnerModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            $.ajax({
                url: '{{ route('busniessOwner.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('BusniessOwner Created Successfully!')
                    $('#createBusniessOwnerModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value[
                            0]);
                    });
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        // Remove validation messages when BusniessOwner starts typing
        $('#createBusniessOwnerForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });
        // ######Get & Update BusniessOwner#########
        var BusniessOwnerShowRoute = '{{ route('busniessOwner.show', ':id') }}';
        var BusniessOwnerUpdateRoute = '{{ route('busniessOwner.update', ':id') }}';

        function editBusniessOwner(BusniessOwnerId) {
            $.ajax({
                url: BusniessOwnerShowRoute.replace(':id', BusniessOwnerId),
                type: 'GET',
                success: function(response) {
                    $('#editBusniessOwnerForm #fname').val(response.fname);
                    $('#editBusniessOwnerForm #lname').val(response.lname);
                    $('#editBusniessOwnerForm #email').val(response.email);
                    $('#editBusniessOwnerForm #phone').val(response.phone);
                    $('#editBusniessOwnerForm #company_name').val(response.company_name);
                    $('#editBusniessOwnerForm #company_info').val(response.company_info);
                    $('#editBusniessOwnerModal').modal('show');
                    $('#editBusniessOwnerModal').data('BusniessOwnerId', BusniessOwnerId);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update BusniessOwner#############
        function updateBusniessOwner() {
            var BusniessOwnerId = $('#editBusniessOwnerModal').data('BusniessOwnerId');
            var formData = new FormData($('#editBusniessOwnerForm')[0]);
            $.ajax({
                url: BusniessOwnerUpdateRoute.replace(':id', BusniessOwnerId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('BusniessOwner Updated Successfully!')
                    $('#editBusniessOwnerModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value[
                            0]);
                    });
                }
            });
        }
        // ############# Delete BusniessOwner###########
        function deleteBusniessOwnerModal(BusniessOwnerId) {
            $('#confirmDeleteBusniessOwner').data('data-id', BusniessOwnerId);
            $('#deleteBusniessOwnerModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteBusniessOwner').click(function() {
                var BusniessOwnerId = $(this).data('data-id');
                deleteBusniessOwner(BusniessOwnerId);
            });
        });

        function deleteBusniessOwner(BusniessOwnerId) {
            $.ajax({
                url: "{{ route('busniessOwner.delete', ['id' => ':BusniessOwnerId']) }}".replace(':BusniessOwnerId',
                    BusniessOwnerId),
                type: 'GET',
                success: function(response) {
                    toastr.success('BusniessOwner Deleted Successfully!')
                    $('#deleteBusniessOwnerModal').modal('hide');
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
            var newStatus = currentStatus === 'active' ? '0' : '1'; // Update newStatus based on current status

            if (newStatus == '0') { // Show the modal only if the new status is '0' (Blocked)
                $('#blockReasonModal').data('button', button).data('userId', userId).modal('show');
            } else {
                // If not blocking, directly update the status without the modal
                updateStatus(button, userId, '1', null); // No block reason needed when activating
            }
        });

        // Handle the block confirmation
        $('#confirmBlock').on('click', function() {
            var button = $('#blockReasonModal').data('button');
            var userId = $('#blockReasonModal').data('userId');
            var blockReason = $('#blockReason').val();

            if (blockReason === '') {
                toastr.error('Please provide a reason for blocking this user.');
                return;
            } else {
                updateStatus(button, userId, '0', blockReason);
            }
        });

        function updateStatus(button, userId, newStatus, blockReason) {
            button.prop('disabled', true);
            $.ajax({
                url: '{{ route('driversBlock.update', ['id' => ':userId']) }}'.replace(':userId', userId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: newStatus,
                    block_reason: blockReason
                },
                success: function(response) {
                    $('#blockReasonModal').modal('hide');
                    toastr.success(response.message);
                    // Update button text and class
                    var buttonText = newStatus == '1' ? 'Active' : 'Blocked';
                    var buttonClass = newStatus == '1' ? 'btn-success' : 'btn-danger';
                    button.text(buttonText).removeClass('btn-success btn-danger').addClass(buttonClass);
                    // Update status cell content
                    var statusCell = button.closest('tr').find('td:eq(6)');
                    var statusText, statusClass;
                    if (newStatus == '0') {
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
        }
    </script>

@endsection
