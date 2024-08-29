@extends('admin.layout.app')
@section('title', 'Report')
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
                                    <h4>Reports</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive table table-striped table-bordered reportsData">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Bunsniess Owner Name</th>
                                            <th>Owner Email</th>
                                            <th>Driver Name</th>
                                            <th>Driver Email</th>
                                            <th>Job Type</th>
                                            <th>Price Per Hour</th>
                                            <th>Single Job Price</th>
                                            <th>Drop off location</th>
                                            <th>Pick up location</th>
                                            <th>Date</th>
                                            <th>Total Days</th>
                                            <th>Remaining Days</th>
                                            <th>Last Completion Date</th>
                                            <th>Status</th>
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
    <script>
        function reloadDataTable() {
            var dataTable = $('.example').DataTable();
            dataTable.ajax.reload();
        }
        $(document).ready(function() {
            // Initialize DataTable with options
            var dataTable = $('.example').DataTable({
                "ajax": {
                    "url": "{{ route('drivers.get') }}",
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
                        "data": "fname"
                    },
                    {
                        "data": "lname"
                    },
                    {
                        "data": "email",
                        "render": function(data, type, full, meta) {
                            if (type === 'display') {
                                return '<a href="mailto:' + data + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        "data": "phone",
                        "render": function(data, type, row) {
                            if (data == null) {
                                return "No Number";
                            } else {
                                return data;
                            }
                        },
                    },
                    {
                        "data": "image",
                        "render": function(data, type, row) {
                            if (data) {
                                if (data.startsWith("http")) {
                                    return '<img src="' + data +
                                        '" alt="Image" style="width: 50px; height: 50px;">';
                                } else {
                                    return '<img src="https://ranglerzwp.xyz/drivrrapp/' + data +
                                        '" alt="Image" style="width: 50px; height: 50px;">';
                                }
                            } else {
                                return '<img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/images/approve/owner.jpg" alt="Image" style="width: 50px; height: 50px;">';
                            }
                        }
                    },

                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var buttonClass = row.is_active == '0' ? 'btn-danger' : 'btn-success';
                            var buttonText = row.is_active == '0' ? 'Blocked' : 'Active';
                            return '<button id="update-status" class="btn ' + buttonClass +
                                '" data-userid="' + row
                                .id + '">' + buttonText + '</button>';
                        },

                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('document.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-3 text-white"><i class="fas fa-file-alt"></i></a>';
                        },
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="' +
                                "{{ route('driver-vehicle.index', ['id' => ':id']) }}"
                                .replace(':id', row.id) +
                                '" class="btn btn-primary mb-3 text-white"><i class="fas fa-car"></i></a>';
                        },
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editDriverBtn" data-id="' +
                                row.id + '"><i class="fa fa-edit"></i></button>' +
                                '<button class="btn btn-danger mb-3 mr-3 text-white deleteDriverBtn" data-id="' +
                                row.id + '"><i class="fa fa-trash"></i></button>';
                        }
                    }
                ],

            });
            $('.example').on('click', '.editDriverBtn', function() {
                var driverId = $(this).data('id');
                editDriver(driverId);
            });
            $('.example').on('click', '.deleteDriverBtn', function() {
                var driverId = $(this).data('id');
                deleteDriverModal(driverId);
            });
            $('.example').on('click', '.DriverBankInfoModal', function() {
                var driverId = $(this).data('id');
                DriverBankInfoModal(driverId);
            });
        });
    </script>
@endsection
