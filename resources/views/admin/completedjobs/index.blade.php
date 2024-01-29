@extends('admin.layout.app')
@section('title', 'index')
@section('content')
    <!DOCTYPE html>
    <html lang="en">
    <!-- datatables.html  21 Nov 2019 03:55:21 GMT -->

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    </head>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Completed Job</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Business Owner</th>
                                                        <th>Owner Email</th>
                                                        <th>Job Location</th>
                                                        <th>Job Date</th>
                                                        <th>Job Time</th>
                                                        <th>Job Days</th>
                                                        <th>Payment</th>
                                                        <th>Driver</th>
                                                        <th>Driver Email</th>
                                                        <th>Vehicle</th>
                                                        <th>Job Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paymentRequests as $payments)
                                                        @if ($payments->status === 'Completed')
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $payments->owner->fname . ' ' . $payments->owner->lname }}
                                                                </td>
                                                                <td>{{ $payments->owner->email }}</td>
                                                                <td>{{ $payments->job->location }}</td>
                                                                <td>{{ $payments->job->date }}</td>
                                                                <td>{{ $payments->job->time }}</td>
                                                                <td>{{ $payments->job->days }}</td>

                                                                <td>
                                                                    @if (isset($payments->payment_amount) && $payments->payment_amount !== null)
                                                                        ${{ $payments->payment_amount }}
                                                                    @elseif(isset($payments->counter_offer) && $payments->counter_offer !== null)
                                                                        ${{ $payments->counter_offer }}
                                                                    @else
                                                                        <div class="badge badge-danger badge-shadow">No
                                                                            Payment
                                                                        </div>
                                                                    @endif
                                                                </td>

                                                                <td>{{ $payments->driver->fname . ' ' . $payments->driver->lname }}
                                                                </td>
                                                                <td>{{ $payments->driver->email }}</td>
                                                                <td>{{ $payments->job->vehicle->name }}</td>
                                                                <td>
                                                                    @if ($payments->status == 'Completed')
                                                                        <div
                                                                            class="badge p-2 badge-shadow btn-success text-white">
                                                                            Completed</div>

                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- datatables.html  21 Nov 2019 03:55:25 GMT -->

    </html>

    <script>
        new DataTable('#example');
    </script>


@endsection
