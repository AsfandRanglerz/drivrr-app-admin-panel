@extends('admin.layout.app')
@section('title', 'Notifications')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Notification</h4>
                                </div>
                            </div>
                            <div class="card-body  table-responsive">
                                <button class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#notificationModal">Add Notification</button>
                                <table class="table table-striped table-bordered text-center" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>UserName</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>

                                        </tr>


                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- Notifications Modal --}}
    <div class="modal" id="notificationModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Notification</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('notifications.store') }}">
                        @csrf
                        <div class="row mx-0 px-4">
                            <div class="col-sm-12 pl-sm-0 pr-sm-3 col-md-12 col-lg-12">
                                <div class="form-group mb-2">
                                    <label>Title</label>
                                    <input type="text" placeholder="Title" name="title" id="title"
                                        value="{{ old('title') }}" class="form-control">
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 pl-sm-0 pr-sm-3 col-md-12 col-lg-12">
                                <div class="form-group mb-2">
                                    <label>Send To</label>
                                    <select name="user_name[]" id="user_name" class="form-control selectric" multiple="">
                                        <option value="">Select Option</option>
                                        <option value="1">Subadmin</option>
                                        <option value="2">Business Owner</option>
                                        <option value="3">Driver</option>
                                    </select>
                                    @error('user_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0 px-4">
                            <div class="col-sm-12 pl-sm-0 pr-sm-3 col-md-12 col-lg-12">
                                <div class="form-group mb-2">
                                    <label>Description</label>
                                    <textarea type="text" placeholder="Description" name="description" id="description" value="{{ old('description') }}"
                                        class="form-control"></textarea>
                                    @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-center row">
                            <div class="col">
                                <button type="submit" name="submit" class="btn btn-success mr-1 btn-bg"
                                    id="submit">Add</button>
                            </div>
                        </div>
                    </form>
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
        //######### AJAX CODE ############
        $(document).ready(function() {

            // Submit the form using AJAX
            $('form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        $('#notificationModal').modal('hide');
                    },
                    error: function(error) {
                        // Handle the error response
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
