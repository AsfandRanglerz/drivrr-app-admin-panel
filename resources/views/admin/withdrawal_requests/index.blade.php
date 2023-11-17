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
                                    <h4>Requests</h4>
                                </div>
                            </div>
                            {{-- driver --}}
                            {{-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> --}}
                            <div class="card-body table-striped table-bordered table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Driver Name</th>
                                            <th>Bank Name</th>
                                            <th>Account Number</th>
                                            <th>Account Holder</th>
                                            <th>Withdrawal Amount</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($withdraw_requests as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->user->fname . ' ' . $data->user->lname }}</td>
                                                <td>{{ $data->bankAccount->bank_name }}</td>
                                                <td>{{ $data->bankAccount->account_number }}</td>
                                                <td>{{ $data->bankAccount->holder_name }}</td>
                                                <td>{{ $data->withdrawal_amount }}</td>
                                                <td>
                                                    @if($data->image)
                                                    <a href="{{ asset($data->image) }}" target="_blank">
                                                        <img src="{{ asset($data->image) }}" alt=""
                                                            height="50" width="50" class="image">
                                                    </a>
                                                    @else
                                                        Null
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($data->status == 0)
                                                        <div class="badge  badge-shadow btn-warning text-black">Pending
                                                        </div>
                                                    @elseif ($data->status == 1)
                                                        <div class="badge badge-success badge-shadow">Approved</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Rejected</div>
                                                    @endif
                                                </td>
                                                <td style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    @if ($data->status == 0)
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#basicModal-{{ $data->id }}">
                                                        <span class="fa fa-pen"></span>
                                                    </button>
                                                    @endif
                                                    <form method="post"
                                                    action="{{ route('delete-approve-request', $data->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                        data-toggle="tooltip">Delete</button>
                                                </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </section>
        <!-- basic modal -->
        @foreach ($withdraw_requests as $data)
            <div class="modal fade" id="basicModal-{{ $data->id }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Send Money</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('action-on-request', ['id' => $data->id, 'amount' => $data->withdrawal_amount]) }}" method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="modal-body">

                                <input type="file" name="image">
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="submit" class="btn btn-primary">Send</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        @endforeach
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
