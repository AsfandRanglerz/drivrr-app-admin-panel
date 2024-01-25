@extends('admin.layout.app')
@section('title', 'Notifications')
@section('content')
<div class="main-content" style="min-height: 562px;">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4>Driver Ranking</h4>
                            </div>
                        </div>
                        <div class="card-body  table-responsive">
                            <table class="table table-striped table-bordered text-center" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Driver Name </th>
                                        <th>Driver Email</th>
                                        <th>Review Ranking</th>
                                        <th>Average Reviews</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($driverReviews as $driverId => $driverInfo)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $driverInfo['driverName'] }}</td>
                                        <td>{{ $driverInfo['driverEmail'] }}</td>
                                        <td>
                                            @php
                                            $width = $driverInfo['averageRating'] * 20;
                                            // Determine the color based on the average rating
                                            $color = 'rgb(' . (255 - round($width * 2.55)) . ', ' . round($width * 2.55) . ', 0)';
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++) @if ($i <=$driverInfo['averageRating']) <span class="fa fa-star checked" style="color: gold;"></span>
                                                @else
                                                <span class="fa fa-star" style="color: gray;"></span>
                                                @endif
                                                @endfor
                                        </td>
                                        <td>
                                            {{ $driverInfo['averageRating'] }}
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

@endsection

@section('js')
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
