<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms List</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .carousel-item img {
            height: 200px; /* Set a fixed height */
            object-fit: cover; /* Ensure image maintains aspect ratio */
        }
        .room-card {
            min-height: 100%; /* Ensure all cards have the same height */
        }
        .room-features {
            list-style: none;
            padding-left: 0;
        }
        .room-features li {
            margin-bottom: 5px;
        }
        .read-more {
            display: none;
        }
        .read-more-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4 text-center">Rooms List</h1>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary mb-4">Add Room</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @foreach ($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <!-- Room Images Slider -->
                    <div id="carouselRoom{{ $room->id }}" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($room->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="Room Image">
                            </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselRoom{{ $room->id }}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselRoom{{ $room->id }}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                    <!-- Room Details -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $room->name }}</h5>
                        <p class="card-text">{{ $room->description }}</p>
                        <p class="card-text"><strong>Price:</strong> &#8358;{{ number_format($room->price, 0) }} / Night</p>
                        <p class="card-text"><strong>Availability:</strong> 
                            @if(!$room->available)
                            <span class="btn btn-danger">Not available until {{ \Carbon\Carbon::parse($room->available_from)->format('D M, Y') }} ({{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($room->available_from)->startOfDay()) }}
                                days left)</span>
                            @else
                               <span class="btn btn-success"> Available now</span>
                            @endif
                        </p>

                        <!-- Room Features -->
                        <h6>Features:</h6>
                        <ul class="room-features list-group list-group-flush">
                            @php
                                // Check if features are in JSON format, an array, or a string
                                $features = is_array($room->features) ? $room->features : (json_decode($room->features, true) ?? explode(',', $room->features));
                            @endphp
                            @foreach ($features as $index => $feature)
                                <li class="list-group-item {{ $index >= 2 ? 'read-more' : '' }}">{{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                        @if (count($features) > 2)
                            <span class="read-more-btn">Read more</span>
                        @endif

                    </div>

                    <!-- Room Actions -->
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-info">View</a>
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Include Bootstrap JS (with Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // "Read More" and "Read Less" functionality for room features
        document.querySelectorAll('.read-more-btn').forEach(button => {
            button.addEventListener('click', function () {
                const hiddenFeatures = this.previousElementSibling.querySelectorAll('.read-more');
                const isHidden = hiddenFeatures[0].style.display === 'none' || hiddenFeatures[0].style.display === '';
                
                if (isHidden) {
                    // Show hidden features
                    hiddenFeatures.forEach(feature => feature.style.display = 'list-item');
                    this.textContent = 'Read less';
                } else {
                    // Hide features and revert button text
                    hiddenFeatures.forEach(feature => feature.style.display = 'none');
                    this.textContent = 'Read more';
                }
            });
        });
    </script>
</body>
</html>
