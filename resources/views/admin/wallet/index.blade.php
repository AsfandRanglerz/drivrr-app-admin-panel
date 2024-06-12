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
                                    <h4>Payment History</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                {{-- <a class="btn btn-success mb-3 text-white" data-toggle="modal"
                                    data-target="#createPaymentRequestModal">
                                    Create Payment Request
                                </a> --}}
                                <table class="responsive table table-striped table-bordered example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
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
