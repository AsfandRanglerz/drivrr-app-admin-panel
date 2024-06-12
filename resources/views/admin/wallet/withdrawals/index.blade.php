@extends('admin.layout.app')
@section('title', 'History')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>History</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive datatables-basic table border-top  table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Transaction proof</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paymentRequests as $paymentRequest)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>£ {{ $paymentRequest->withdrawal_amount }}
                                                </td>
                                                <td
                                                    class="{{ $paymentRequest->status == 1 ? 'text-success' : 'text-danger' }}">
                                                    {{ $paymentRequest->status == 1 ? 'Paid' : 'Pending' }}
                                                </td>
                                                <td>{{ $paymentRequest->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <div id="aniimated-thumbnials" class=" clearfix">
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                            <a href="{{ asset($paymentRequest->image) }}"
                                                                data-sub-html="Payment Proof Image">
                                                                <img src="{{ asset($paymentRequest->image) }}"
                                                                    class="img-responsive thumbnail"
                                                                    alt="Payment Proof Image"
                                                                    style="max-width: 100px; max-height: 100px;">
                                                            </a>
                                                        </div>
                                                    </div>
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
    {{-- Data Table --}}


@endsection
@endsection
