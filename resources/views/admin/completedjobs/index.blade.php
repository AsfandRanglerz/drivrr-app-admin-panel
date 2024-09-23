@extends('admin.layout.app')
@section('title', 'Completed Jobs')
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
                                    <h4>Completed Jobs</h4>
                                </div>
                            </div>
                            <div class="card-body table-responsive">

                                <table class="responsive table table-striped table-bordered example">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Bunsniess Owner Name</th>
                                            <th>Owner Email</th>
                                            <th>Driver Name</th>
                                            <th>Driver Email</th>
                                            <th>Job Type</th>
                                            <th>Date</th>
                                            <th>Drop off location</th>
                                            <th>Pick up location</th>
                                            <th>Price Per Hour</th>
                                            <th>Total Days</th>
                                            <th>Hours</th>
                                            <th>Total Price Per Hour</th>
                                            <th>Total Single Job Price</th>
                                            <th>Long Term Job Remaining Days</th>
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
                    "url": "{{ route('completedjobsIndex.get') }}",
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
                        "data": null,
                        "render": function(data, type, row) {
                            return data.owner.fname + ' ' + data.owner.lname;
                        }
                    },
                    {
                        "data": "owner.email",
                        "render": function(data, type) {
                            if (type === 'display') {
                                return '<a href="mailto:' + data + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return data.driver.fname + ' ' + data.driver.lname;
                        }
                    },
                    {
                        "data": "driver.email",
                        "render": function(data, type) {
                            if (type === 'display') {
                                return '<a href="mailto:' + data + '">' + data + '</a>';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        "data": "job.job_type",
                        "render": function(data, type) {
                            return data ? data : 'No Data Found!';
                        }

                    },
                    {
                        "data": "job.date",
                        "render": function(data, type) {
                            return data ? data : 'No Data Found!';
                        }
                    },
                    {
                        "data": "job.drop_off_location"
                    },
                    {
                        "data": "job.pick_up_location"
                    },
                    {
                        "data": "job.price_per_hour",
                        "render": function(data, type) {
                            return data ? '£' + data : '£0';
                        }
                    },

                    {
                        "data": "job.days",
                        "render": function(data, type) {
                            return data ? data + 'days' : '£0';
                        }
                    },
                    {
                        "data": "job.hours",
                        "render": function(data, type) {
                            return data ? data + 'hours' : '£0';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            if (data.job.job_type == 'Long Term') {
                                let pricePerHour = data.job.price_per_hour ? data.job
                                    .price_per_hour : 0;
                                let hours = data.job.hours ? data.job.hours : 0;
                                let days = data.job.days ? data.job.days : 0;
                                let totalPrice = pricePerHour * hours * days;

                                // Display the formula in text form
                                return `(${pricePerHour} x ${hours}) x ${days} = £${totalPrice}`;
                            } else {
                                return 'No Data Found!';
                            }
                        }
                    },
                    {
                        "data": "job.job_price",
                        "render": function(data, type) {
                            return data ? '£' + data : '£0';
                        }
                    },
                    {
                        "data": "job.remaining_day",
                        "render": function(data, type) {
                            return data ? data : 'No Data Found!';
                        }
                    },
                    {
                        "data": "job.last_completion_date",
                        "render": function(data, type) {
                            return data ? new Date(data).toLocaleDateString() : 'No Data Found!';
                        }
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (data == "Accepted") {
                                return '<span class="text-danger">In Process</span>';
                            } else {
                                return '<span class="text-success">Completed</span>';
                            }
                        },
                    }
                ]
            });
        });
    </script>
@endsection
