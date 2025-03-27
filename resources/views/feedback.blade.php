<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="icon" href="{{ asset('bpicon.jpg') }}" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: azure;
            font-family: "Gotham Light", sans-serif;
        }

        .logo img {
            display: absolute;
            margin: 0 auto;
            
        }
        h2{
            border: 2px solid;
            border-radius: 5px;
            background: #000;
            color: #fff;
            text-transform: uppercase;
        }
        .back:hover:before{
             content:"Back to ";
            font-family: Gotham;
            font-style: italic;

        }

        .table td,
        .table th {
            text-align: center;
            vertical-align: middle;
        }

        .table td:first-child {
            text-align: left;
        }

        .form-check-inline label {
            margin-right: 20px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="logo mb-4">
            <img src="{{ asset('storage/bplogo.png') }}" alt="Brickspoint Logo" width="200">
        </div>
        <h2 class="text-center mb-4">Feedback Form</h2>
        <a href="{{ route('rooms.index') }}" class="back btn btn-info mb-3">View Rooms</a>
        <a href="{{ route('feedback.report') }}" class="btn btn-info mb-3">View Report</a>

        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="guest_full_name"
                    placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="tel">Tel No:</label>
                <input type="tel" class="form-control" id="tel" name="guest_phone_number"
                    placeholder="Enter your telephone number">
            </div>
            <div class="form-group">
                <label for="room">Room No:</label>
                <input type="text" class="form-control" id="room" name="guest_room_no"
                    placeholder="Enter your room number">
            </div>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Service Area Experience</th>
                        <th>😞</th>
                        <th>😐</th>
                        <th>🙂</th>
                        <th>😄</th>
                        <th>😃</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $key => $service)
                        <tr>
                            <td>{{ $loop->iteration }}. {{ $service }}</td>
                            @for ($i = 0; $i < 5; $i++)
                                <td>
                                    <input class="form-check-input" type="radio" name="{{ $key }}"
                                        value="{{ $i }}">
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="form-group">
                <label>Would you recommend us to your family, friends, and business partners?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="recommend" id="recommendYes" value="yes">
                    <label for="recommendYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="recommend" id="recommendNo" value="no">
                    <label for="recommendNo">No</label>
                </div>
            </div>

            <div class="form-group">
                <label>Would you share a lead for us to contact and promote our services? If yes:</label>
                <input type="text" class="form-control mb-2" name="lead_name" placeholder="Name">
                <input type="text" class="form-control mb-2" name="company_name" placeholder="Company Name">
                <input type="text" class="form-control" name="lead_contact" placeholder="Contact Information">
            </div>

            <div class="form-group">
                <label for="comments">Recommendation and Comments for Improvement:</label>
                <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Enter your comments"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="thankYouModalLabel">Thank You!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Thank you for your feedback! We appreciate your time and effort in helping us improve.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            @if (session('feedback_saved'))
                $('#thankYouModal').modal('show');
            @endif
        });
    </script>
</body>

</html>
