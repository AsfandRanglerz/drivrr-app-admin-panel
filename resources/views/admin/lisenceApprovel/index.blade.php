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
    <!-- Edit lisenceApprovel Modal -->
    <div class="modal fade" id="editlisenceApprovelModal" tabindex="-1" role="dialog"
        aria-labelledby="editlisenceApprovelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editlisenceApprovelModalLabel">Send Transaction Screen Short</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editlisenceApprovel" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control name text-center" name="name" required
                                        disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mt-4">
                            <div class="form-group">
                                <label for="image">Upload Transaction Screen Short</label>
                                <input name="image" type="file" class="form-control image" id="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="updatelisenceApprovels()">Send</button>
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
                                {{-- <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createlisenceApprovelModal">
                                    Create Payment Request
                                </a> --}}
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

@section('script')
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
                    <div id="animated-thumbnails-${meta.row}" class="clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a href="${data}" data-sub-html="Lisence Image">
                                <img src="${data}" class="img-responsive thumbnail" alt="Payment Proof Image" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </div>
                    </div>
                `;
                        },

                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data == 0) {
                                return '<span class="text-danger">Pending</span>';
                            } else {
                                return '<span class="text-success">Paid</span>';
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>';
                        }
                    }
                ]
            });
            $('.example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editlisenceApprovelModal(id);
            });
        });
    </script>

    <script>
        // ######Get & Update lisenceApprovel#########
        var showlisenceApprovel = '{{ route('lisenceApprovel.show', ':id') }}';
        var updatelisenceApprovel = '{{ route('lisenceApprovel.update', ':id') }}';

        function editlisenceApprovelModal(id) {
            $.ajax({
                url: showlisenceApprovel.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    var fullName = response.users.fname + ' ' + response.users.lname;
                    $('#editlisenceApprovel .name').val(fullName);
                    $('#editlisenceApprovelModal').modal('show');
                    $('#editlisenceApprovelModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        function updatelisenceApprovels() {
            var id = $('#editlisenceApprovelModal').data('id');
            var formData = new FormData($('#editlisenceApprovel')[0]);

            // Disable the button
            $('#editlisenceApprovelModal button[type="button"]').prop('disabled', true);


            // Show loader with custom color
            var loaderHtml =
                '<div class="spinner-border loader-custom-color" role="status"><span class="sr-only">Loading...</span></div>';

            $('#editlisenceApprovelModal .modal-footer').html(loaderHtml);

            $.ajax({
                url: updatelisenceApprovel.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toast.fire({
                        icon: response.alert,
                        title: response.message
                    });
                    $('#editlisenceApprovelModal').modal('hide');
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
                    // Enable the button
                    $('#editlisenceApprovelModal button[type="button"]').prop('disabled', false);

                    // Restore button content
                    var buttonHtml =
                        '<button type="button" class="btn btn-danger" onclick="updatelisenceApprovels()">Update</button>';
                    $('#editlisenceApprovelModal .modal-footer').html(buttonHtml);
                }
            });
        }
    </script>


@endsection
