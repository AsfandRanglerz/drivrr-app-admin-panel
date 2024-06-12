@extends('admin.layout.app')
@section('title', 'AccountDetail')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Account Details</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="table table-striped table-bordered" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Account Title</th>
                                            <th>Bank Name</th>
                                            <th>Account Number</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bankInfos as $bankInfos)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $bankInfos->holder_name }}</td>
                                                <td>{{ $bankInfos->bank_name }}
                                                </td>
                                                <td>{{ $bankInfos->account_number }}</td>
                                                <td
                                                    class="{{ $bankInfos->status == 'Active' ? 'text-success' : 'text-danger' }}">
                                                    {{ $bankInfos->status }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@section('js')


@endsection
@endsection
