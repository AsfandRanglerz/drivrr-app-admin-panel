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
                                    <h4>Driver</h4>
                                </div>
                            </div>
                            {{--driver--}}
                    {{-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> --}}
                        <div class="card-body  table-responsive">
                            <table class="table table-striped table-bordered" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Earnings</th>
                                        <th>Withdrawals</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach ($data as $drivers)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> <!-- Parent loop index for owner -->
                                        <td>{{ $drivers->fname . ' ' . $drivers->lname }}</td>
                                        <td>{{ $drivers->email }}
                                        {{-- <td>{{ $drivers->driverWallet->total_earning }}</td> --}}
                                        <td>
                                            @if ($drivers->driverWallet)
                                                {{ $drivers->driverWallet->total_earning }}
                                            @else
                                             <span>0</span>
                                            @endif
                                        </td>
                                        <td style="justify-content: center">
                                            {{-- {{$drivers->id}} --}}
                                            <a href="{{route('show-withdrawals-receipts',$drivers->id)}}">View</a>
                                        </td>
                                        {{-- <td>{{ $question->details }}</td> --}}
                                        <td class="text-center">
                                            {{-- @dd($drivers->id) --}}
                                            {{-- <button id={{ $drivers->id }} type="button" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                                <span class=" fa fa-pen"></span>
                                            </button> --}}
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
