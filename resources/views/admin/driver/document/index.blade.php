{{-- <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Documents</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ url()->previous() }}">Back</a>
                                <a class="btn btn-success mb-3" href="{{ route('document.create', $data->id) }}">Add
                                    Document</a>
                                <table class="table table-striped table-bordered text-center" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->document as $document)
                                            <tr>
                                                @if ($document)
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $document->name }}</td>
                                                    @if ($document->image)
                                                        <td>
                                                            <a href="{{ asset($document->image) }}" target="_blank">
                                                                <img src="{{ asset($document->image) }}" alt=""
                                                                    height="50" width="50" class="image">
                                                            </a>
                                                        </td>
                                                    @endif

                                                    <td>
                                                        @if ($document->is_active == 0)
                                                            <div class="badge  badge-shadow btn-warning text-black">Pending</div>
                                                        @elseif ($document->is_active == 1)
                                                            <div class="badge badge-success badge-shadow">Accepted</div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">Rejected</div>
                                                        @endif
                                                    </td>

                                                    <td
                                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if ($document->is_active == 0)

                                                        <a href="{{route('document.status',['id' => $document->id , 'key' => $data->id,'check'=>1])}}"
                                                        class="btn btn-success text-white">
                                                        &#x2713
                                                        </a>

                                                        <a href="{{route('document.status',['id' => $document->id , 'key' => $data->id,'check'=>2])}}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target=".exampleModal"
                                                        class="btn btn-danger">
                                                        &#10005
                                                        </a>

                                                    @elseif ($document->is_active == 1)

                                                        <a href="{{ route('document.status', ['id' => $document->id, $data->id , 'check'=>2]) }}"
                                                            class="btn btn-success" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="28"
                                                                height="23" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor"
                                                                stroke-width="2"stroke-linecap="round"
                                                                stroke-linejoin="round"class="feather feather-toggle-left">
                                                                <rect x="1" y="5" width="22" height="14"
                                                                    rx="7" ry="7"></rect>
                                                                <circle cx="16" cy="12" r="3">
                                                                </circle>
                                                            </svg></a>
                                                     @else
                                                         <a href="{{ route('document.status', ['id' => $document->id, $data->id , 'check'=>1]) }}"
                                                                class="btn btn-danger"><svg
                                                                    xmlns="http://www.w3.org/2000/svg" width="28"
                                                                    height="23" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-right">
                                                                    <rect x="1" y="5" width="22" height="14"
                                                                        rx="7" ry="7"></rect>
                                                                    <circle cx="8" cy="12" r="3">
                                                                    </circle>
                                                                </svg></a>
                                                        @endif

                                                        <a class="btn text-white btn-info"
                                                            href="{{ route('document.edit', $document->id) }}">Edit</a>
                                                        <form method="post"
                                                            action="{{ route('document.destroy', $document->id) }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
<!-- Button trigger modal -->


<!-- Modal -->
{{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            @if (isset($document) && isset($data))
                                <form
                                    action="{{ route('document.status', ['id' => $document->id, 'key' => $data->id]) }}">
                                    @csrf

                                    <input type="hidden" name="check" value="2">
                                    <h5>Reason</h5>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" required name="reason"></textarea>
                        </div>
                        <div class="text-end mt-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                    @else
                        <p>No records found</p>
                        @endif
                    </div>

                </div>
            </div>
        </div> --}}


{{-- modal --}}
{{-- <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-1 border-0">
                        <div class="modal-body">
                            <div class="mb-2">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mt-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('document.status', ['id' => 1 , 'key' => $data->id]) }}" type="submit" class="btn btn-success mx-2" data-bs-dismiss="modal" aria-label="Close">Approved</a>
                                        <a href="{{ route('document.status', ['id' => 2 , 'key' => $data->id]) }}" type="submit" class="btn btn-danger mx-2" data-bs-dismiss="modal" aria-label="Close">Rejected</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

{{-- @endsection --}}
{{--
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
@endsection --}}
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
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
                    <button type="button" class="btn btn-danger" onclick="createDocument()">Create</button>
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control name" name="name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" name="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" onclick="updateCategories()">Update</button>
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
                                <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createDocumentModal">
                                    Create Document
                                </a>
                                <table class="responsive table table-striped table-bordered example" >
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Image</th>
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
                    "url": "{{ route('document.get') }}",
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
                        "data": "name"
                    },
                    {
                        "data": "image",
                        "render": function(data, type, row) {
                            return '<img src="https://ranglerzwp.xyz/easyshop/' + data +
                                '" alt="Image" style="width: 50px; height: 50px;">';
                        },

                    }, {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-3 mr-3 text-white deleteSubadminBtn" data-id="' +
                                row.id + '"><i class="fas fa-trash-alt"></i></button>';
                        }
                    }
                ]
            });
            $('.example').on('click', '.editSubadminBtn', function() {
                var id = $(this).data('id');
                editDocumentModal(id);
            });
            $('.example').on('click', '.deleteSubadminBtn', function() {
                var id = $(this).data('id');
                deleteDocumentModal(id);
            });
        });
    </script>

    <script>
        // ##############Create Sub admin################
        function createDocument() {
            var formData = new FormData($('#createDocumentForm')[0]);
            var createButton = $('#createDocumentModal').find('.modal-footer').find('button');
            createButton.prop('disabled', true);
            var formDataObject = {};
            formData.forEach(function(value, key) {
                formDataObject[key] = value;
            });
            $.ajax({
                url: '{{ route('document.create') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Document Created Successfully!')
                    $('#createDocumentModal').modal('hide');
                    reloadDataTable();
                    $('#createDocumentForm')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                            value[
                                0]);
                    });
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
                    $('#editDocument .name').val(response.name);
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
                    toastr.success('Document Updated Successfully!')
                    $('#editDocumentModal').modal('hide');
                    reloadDataTable();
                    $('#editDocumentModal')[0].reset();
                },
                error: function(xhr, status, error) {

                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key).addClass('is-invalid').siblings('.invalid-feedback').html(
                            value[
                                0]);
                    });
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
                url: "{{ route('Document.delete', ['id' => ':id']) }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    toastr.success('Document Deleted Successfully!')
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
