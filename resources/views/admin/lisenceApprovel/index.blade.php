@extends('admin.layout.app')
@section('title', 'LisenceApprovel')
@section('content')
    <style>
        /* Define a custom color class */
        .loader-custom-color {
            color: #ff0000;
            /* Red color */
        }
    </style>
    {{-- Reason of rejection modal  --}}
    <div class="modal fade" id="rejectionReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectionReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionReasonModalLabel">Rejection Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea id="rejectionReason" class="form-control" rows="3" placeholder="Enter the reason for rejection"></textarea>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" id="saveRejectionReasonBtn">Save Reason</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit lisenceApprovel Modal -->
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="editProductLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabel">License Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="accepted" id="acceptedCheckbox">
                        <label class="form-check-label" for="acceptedCheckbox">Accepted</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="rejected" id="rejectedCheckbox">
                        <label class="form-check-label" for="rejectedCheckbox">Rejected</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark" id="updateStatusBtn">Update</button>
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
                                    <h4>Lisence Approvel Requests</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive table table-striped table-bordered example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Lisence Image</th>
                                            <th>Status</th>
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
                    "url": "{{ route('lisenceApprovel.get') }}",
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
                        "data": null,
                        "render": function(data, type, row) {
                            return data.user.fname + ' ' + data.user.lname;
                        }
                    },
                    {
                        "data": 'user.email',

                    },
                    {
                        "data": 'image',
                        "render": function(data, type, row, meta) {
                            return `
                    <div id="animated-thumbnails" class="clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a href="http://localhost/drivrr-app/${data}" data-sub-html="Lisence Image">
                                <img src="http://localhost/drivrr-app/${data}" class="img-responsive thumbnail" alt="Lisence Image" style="width: 50px; height: 50px;">
                            </a>
                        </div>
                    </div>
                `;
                        },

                    },
                    {
                        "data": "is_active",
                        "render": function(data, type, row) {
                            if (data == 0) {
                                return '<span class="text-danger">Pending</span>';
                            } else if (data == 2) {
                                return '<span class="text-danger">Rejected</span>';
                            } else {
                                return '<span class="text-success">Accepted</span>';

                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success btn-sm mb-3 mr-1 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>';
                        }
                    },

                ]
            });

        });
    </script>

    <script>
        var orderId; // Define orderId in the global scope

        $(document).ready(function() {
            // Event listener for edit button
            $('.example').on('click', '.editSubadminBtn', function() {
                orderId = $(this).data('id'); // Set the orderId when the button is clicked
                $('#editProduct').modal('show');
                $('#acceptedCheckbox').prop('checked', false);
                $('#rejectedCheckbox').prop('checked', false);

                // Fetch current status
                $.ajax({
                    url: "{{ route('lisenceApprovel.status', ['id' => ':id']) }}".replace(':id',
                        orderId),
                    type: 'GET',
                    data: {
                        id: orderId
                    },
                    success: function(response) {
                        if (response.is_active == 1) {
                            $('#acceptedCheckbox').prop('checked', true);
                        } else if (response.is_active == 2) {
                            $('#rejectedCheckbox').prop('checked', true);
                        }
                    },
                    error: function(jqXHR) {
                        var response = jqXHR.responseJSON;
                        Toast.fire({
                            icon: response.alert,
                            title: response.message
                        });
                    }
                });
            });

            // Event listener for checkboxes
            $('#acceptedCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#rejectedCheckbox').prop('checked', false);
                }
            });

            $('#rejectedCheckbox').change(function() {
                if ($(this).prop('checked')) {
                    $('#acceptedCheckbox').prop('checked', false);
                    $('#rejectionReasonModal').modal('show');
                    $('#editProduct').modal('hide');                }
            });

            // Event handler for updating status
            $('#updateStatusBtn').click(function() {
                var is_active;
                if ($('#acceptedCheckbox').prop('checked')) {
                    is_active = 1; // Adjusted from 'completed' to 1
                    updateStatus(is_active, null); // No reason needed for acceptance
                } else if ($('#rejectedCheckbox').prop('checked')) {
                    $('#rejectionReasonModal').modal('show');
                }
            });

            // Function to update status with or without rejection reason
            function updateStatus(is_active, rejectionReason) {
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('orders.update-is_active', ['id' => ':id']) }}".replace(':id', orderId),
                    type: 'POST',
                    data: {
                        id: orderId,
                        is_active: is_active,
                        rejection_reason: rejectionReason, // Include the rejection reason
                        _token: token
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#editProduct').modal('hide');
                        $('#rejectionReasonModal').modal('hide'); // Hide the rejection reason modal
                        reloadDataTable();
                    },
                    error: function(jqXHR) {
                        var response = jqXHR.responseJSON;
                        Toast.fire({
                            icon: response.alert,
                            title: response.message
                        });
                    }
                });
            }

            // Event handler for saving rejection reason
            $('#saveRejectionReasonBtn').click(function() {
                var rejectionReason = $('#rejectionReason').val(); // Get the rejection reason
                updateStatus(2, rejectionReason); // Update status with rejection reason
            });
        });
    </script>






@endsection
