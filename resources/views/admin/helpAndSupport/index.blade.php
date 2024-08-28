@extends('admin.layout.app')
@section('title', 'Help & Support')
@section('content')

    <div class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="answerModalLabel">Feedback</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="answer">Please provide your feedback:</label>
                        <textarea class="form-control" name="answer" id="answer" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-12 text-center">
                        <button type="button" id="confirmanswer" class="btn btn-dark">Send</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Users</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-12 mb-3">
                                    <button id="ownerButton" class="btn btn-dark mr-2 mb-2 mb-md-0"
                                        onclick="loadData('Owner', this)">
                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i> Business Owner
                                    </button>
                                    <button id="weeklyButton" class="btn btn-dark mr-2 mb-2 mb-md-0"
                                        onclick="loadData('Driver', this)">
                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i> Driver
                                    </button>
                                </div>
                                <div class="tab-content" id="myTabContent">
                                    <!-- Business Owners Tab -->
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="card-body  table-responsive">
                                            <table class="table table-striped userData">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Title</th>
                                                        <th>Details</th>
                                                        <th class="text-center">Feedback</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be loaded here via AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
        $(document).ready(function() {
            var period = 'Owner';
            var dataTable = $('.userData').DataTable({
                "ajax": {
                    "url": "{{ route('help.and.support.data', ['type' => ':type']) }}".replace(':type',
                        period),
                    "type": "GET",
                    "dataSrc": "data",
                    "error": function(xhr, error, thrown) {
                        console.error('AJAX Error:', thrown);
                    }
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": function(row) {
                            return row.fname + ' ' + row.lname;
                        }
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "title",
                        "defaultContent": ""
                    },
                    {
                        "data": "details",
                        "defaultContent": ""
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success mb-3 mr-3 text-white editDriverBtn" data-id="' +
                                row.id + '"><i class="fa fa-edit"></i></button>';
                        }
                    }
                ]
            });

            $('.userData').on('click', '.editDriverBtn', function() {
                var driverId = $(this).data('id');
                $('#answerModal').data('id', driverId).modal('show');
            });
            $('#confirmanswer').on('click', function() {
                var answer = $('#answer').val();
                var id = $('#answerModal').data('id');

                if (answer.trim() === '') {
                    toastr.error('Please provide your feedback.');
                    return;
                }

                $.ajax({
                    url: "{{ url('admin/send-feedback/') }}/" + id,
                    type: 'POST',
                    data: {
                        answer: answer,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#answerModal').modal('hide');
                        dataTable.ajax.reload();
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred while sending feedback.');
                    }
                });
            });
            window.loadData = function(newPeriod, button) {
                period = newPeriod;
                disableButton(button);

                // Update the DataTable URL and reload the data
                dataTable.ajax.url("{{ route('help.and.support.data', ['type' => ':type']) }}".replace(':type',
                    period)).load(function() {
                    enableButton(button);
                    updateActiveButton(button);
                });
            };

            function disableButton(button) {
                $(button).attr('disabled', true);
                $(button).find('.fa-spinner').show();
            }

            function enableButton(button) {
                $(button).attr('disabled', false);
                $(button).find('.fa-spinner').hide();
            }

            function updateActiveButton(button) {
                $('.btn').removeClass('active-button');
                $(button).addClass('active-button');
            }

            // Set the initial active button
            $('#ownerButton').addClass('active-button');
        });
    </script>
@endsection
