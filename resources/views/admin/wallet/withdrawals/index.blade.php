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
                                    <h4>Withdrawals</h4>
                                </div>
                            </div>
                            {{--driver--}}
                    {{-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> --}}
                        <div class="card-body table-striped table-bordered table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Image</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($driver as $withdraw)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Parent loop index for owner -->
                                        <td>
                                            <img src="{{ asset($withdraw->image) }}" alt="" height="50"
                                                width="50" class="image">
                                        </td>
                                        {{-- <td>{{ $question->details }}</td> --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary">
                                                <span class="fa fa-pen"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                        </div>
                    {{-- </div> --}}
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
