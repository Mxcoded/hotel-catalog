<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            margin-top: 30px;
            padding: 15px;
        }

        .report-title {
            text-align: center;
            margin-bottom: 30px;
            color: #5a5a5a;
            font-weight: bold;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .negative-feedback {
            background-color: #ffe6e6 !important;
        }

        @media (max-width: 768px) {
            .card-body h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Title -->
        <h2 class="report-title">Feedback Report</h2>
        <div class="mb-3 d-flex justify-content-center flex-wrap">
            <a href="{{ route('rooms.index') }}" class="btn btn-info mx-2 mb-2">View Rooms</a>
            <a href="{{ route('feedback.create') }}" class="btn btn-info mx-2 mb-2">Submit Feedback</a>
        </div>

        <!-- Summary Section -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Total Feedback</div>
                    <div class="card-body">
                        <h3>{{ $totalFeedback }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Positive Feedback</div>
                    <div class="card-body">
                        <h3>{{ $positiveFeedback }}</h3>
                        <p class="text-success">{{ round(($positiveFeedback / $totalFeedback) * 100, 2) }}% Positive</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">Negative Feedback</div>
                    <div class="card-body">
                        <h3>{{ $negativeFeedback }}</h3>
                        <p class="text-danger">{{ round(($negativeFeedback / $totalFeedback) * 100, 2) }}% Negative</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="table-summary mt-4">
            <h4>Feedback Details</h4>
            <div class="table-responsive">
                <table id="feedbackTable" class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Apartment Number</th>
                            <th>Guest Name</th>
                            <th>Feedback Date</th>
                            <th>Reservation</th>
                            <th>Atmosphere</th>
                            <th>Housekeeping</th>
                            <th>Food</th>
                            <th>Recreations</th>
                            <th>Internet</th>
                            <th>Location</th>
                            <th>Meeting</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $index => $feedback)
                            @php
                                $averageRating = collect([
                                    $feedback->reservation,
                                    $feedback->atmosphere,
                                    $feedback->housekeeping,
                                    $feedback->food,
                                    $feedback->recreations,
                                    $feedback->internet,
                                    $feedback->location,
                                    $feedback->meeting,
                                ])->average();
                                $rowClass = $averageRating < 3 ? 'negative-feedback' : '';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $feedback->guest_room_no }}</td>
                                <td>{{ $feedback->guest_full_name }}</td>
                                <td>{{ $feedback->created_at->format('d M Y') }}</td>
                                <td>{{ $feedback->reservation }}</td>
                                <td>{{ $feedback->atmosphere }}</td>
                                <td>{{ $feedback->housekeeping }}</td>
                                <td>{{ $feedback->food }}</td>
                                <td>{{ $feedback->recreations }}</td>
                                <td>{{ $feedback->internet }}</td>
                                <td>{{ $feedback->location }}</td>
                                <td>{{ $feedback->meeting }}</td>
                                <td>
                                    @if ($feedback->comments)
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#commentModal{{ $index }}">
                                            View
                                        </button>

                                        <!-- Comment Modal -->
                                        <div class="modal fade" id="commentModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel{{ $index }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="commentModalLabel{{ $index }}">Guest Comment</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $feedback->comments }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No Comments</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#feedbackTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                lengthChange: true,
                order: [[3, 'desc']], // Order by Feedback Date
            });
        });
    </script>
</body>

</html>
