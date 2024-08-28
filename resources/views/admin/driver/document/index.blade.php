@extends('admin.layout.app')
@section('title', 'Document')
@section('content')
    {{-- Create Document Model  --}}
    <div class="modal fade" id="createDocumentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createDocumentForm" enctype="multipart/form-data">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="is_active" class="form-control" id="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Pending</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="createDocument()">Create</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Document Modal -->
    <div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDocumentModalLabel">Edit Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editDocument" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control image" name="image">
                                <label for="imagePreview">Pervious Image:</label>
                                <img id="imagePreview" src="" alt="Image Preview"
                                    style="display: none; max-width: 100px; margin-top: 10px;">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="status">Active Status</label>
                                <select name="is_active" class="form-control is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Pending</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" onclick="updateCategories()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Document Modal -->
    <div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDocumentModalLabel">Delete Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Document?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" id="confirmDeleteSubadmin">Delete</button>
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
                                    <h4>Document</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-dark mb-3 text-white" data-toggle="modal"
                                    data-target="#createDocumentModal">
                                    Create Document
                                </a>
                                <table class="responsive table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Image</th>
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
        // ######### Data Table ##############
        function reloadDataTable() {
            var dataTable = $('#example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('#example').DataTable({
                "ajax": {
                    "url": "{{ route('document.get', ['id' => $id]) }}",
                    "type": "GET",
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
                        "data": "image",
                        "render": function(data, type, row) {
                            if (data) {
                                return '<img src="https://ranglerzwp.xyz/drivrrapp/' + data +
                                    '" alt="Image" style="width: 50px; height: 50px;">';
                            } else {
                                return '<img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/images/users/admin.png" alt="Image" style="width: 50px; height: 50px;">';
                            }
                        }
                    },
                    {
                        "data": "is_active",
                        "render": function(data, type, row) {
                            if (data == '1') {
                                return "<span class='text-success'>Approved</span>";
                            } else if (data == '0') {
                                return "<span class='text-warning'>pending</span>";
                            } else {
                                return "<span class='text-danger'>Rejected</span>";
                            }
                        },

                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success  mr-2 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger  mr-2 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('#example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editDocumentModal(id);
            });
            $('#example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteDocumentModal(id);
            });
        });

        // ##############Create Sub admin################
        $(document).ready(function() {
            $('#createDocumentForm input, #createDocumentForm select, #createDocumentForm textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function createDocument() {
            var formData = new FormData($('#createDocumentForm')[0]);
            var createButton = $('#createDocumentModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: "{{ route('document.create', ['id' => $id]) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Document Created Successfully!');
                    $('#createDocumentModal').modal('hide');
                    reloadDataTable();
                    $('#createDocumentModal form')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log("data", xhr);
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                                value[
                                    0]);
                        });
                    } else {
                        console.log("Error:", xhr);
                    }
                },
                complete: function() {
                    createButton.prop('disabled', false);
                }
            });
        }
        $('#createDocumentForm input').keyup(function() {
            $(this).removeClass('is-invalid').siblings('.invalid-feedback').html('');
        });

        // ######Get & Update Document#########

        function editDocumentModal(id) {
            var showDocument = '{{ route('document.show', ':id') }}';
            $.ajax({
                url: showDocument.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editDocument .is_active').val(response.is_active);
                    var imageUrl = response.image;
                    var baseUrl = 'https://ranglerzwp.xyz/drivrrapp/';
                    var responseImage = baseUrl + response.image;
                    if (imageUrl) {
                        $('#imagePreview').attr('src', responseImage).show();
                    } else {
                        $('#imagePreview').hide();
                    }
                    $('#editDocumentModal').modal('show');
                    $('#editDocumentModal').data('id', id);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(xhr.responseText);
                }
            });
        }
        // #############Update subAdmin#############
        $(document).ready(function() {
            $('#editDocument input, #editDocument select, #editDocument textarea').on(
                'input change',
                function() {
                    $(this).siblings('.invalid-feedback').text('');
                    $(this).removeClass('is-invalid');
                });
        });

        function updateCategories() {
            var updateDocument = '{{ route('document.update', ':id') }}';
            var id = $('#editDocumentModal').data('id');
            var formData = new FormData($('#editDocument')[0]);
            // console.log('formData', formData);
            $.ajax({
                url: updateDocument.replace(':id', id),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Document Updated Successfully!');
                    reloadDataTable();
                    $('#editDocumentModal').modal('hide');
                    $('#editDocument form')[0].reset();

                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) { // If validation errors
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                                value[
                                    0]);
                        });
                    } else {
                        console.log("Error:", xhr);
                    }
                }
            });
        }
        // ############# Delete Document Data###########
        function deleteDocumentModal(id) {
            $('#confirmDeleteSubadmin').data('subadmin-id', id);
            $('#deleteDocumentModal').modal('show');
        }
        $(document).ready(function() {
            $('#confirmDeleteSubadmin').click(function() {
                var id = $(this).data('subadmin-id');
                deleteDocument(id)
            });
        });

        function deleteDocument(id) {
            $.ajax({
                url: "{{ route('document.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Document Deleted Successfully!');
                    $('#deleteDocumentModal').modal('hide');
                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

    </script>

@endsection
