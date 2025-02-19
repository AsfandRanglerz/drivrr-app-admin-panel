@extends('admin.layout.app')
@section('title', 'PaymentHistory')
@section('content')
    {{-- #############Main Content Body#################  --}}
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Drivers Wallet  History</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="responsive table table-striped table-bordered example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Total Amount</th>
                                            <th>Payment History</th>
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
                    "url": "{{ route('paymentHistory.get') }}",
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
                            return row.fname + ' ' + row.lname;
                        }
                    },
                    {
                        "data": 'email',

                    },
                    {
                        "data": 'driver_wallet.total_earning',
                        "render": function(data, type, row) {
                            var amount = parseFloat(data);
                            if (amount % 1 === 0) {
                                return '£ ' + amount.toFixed(0);
                            } else {
                                return '£ ' + amount.toFixed(
                                    2);
                            }
                        }

                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('userPaymentHistory.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-dark mb-3 text-white"><i class="fas fa-file-invoice-dollar"></i></a>';
                        },
                    },
                ]
            });
        });
    </script>

@endsection
