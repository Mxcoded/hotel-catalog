<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->name }} - Room Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Fancybox CSS for interactive image slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <style>
        .room-images img {
            width: 100%; /* Make images responsive */
            height: auto;
            object-fit: cover; /* Ensure images maintain aspect ratio and cover area */
            margin-bottom: 10px;
            cursor: pointer;
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
        .read-less-btn {
            cursor: pointer;
            color: red;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">{{ $room->name }} - Room Details</h1>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary mb-4">Back to Rooms</a>

        <div class="row">
            <!-- Room Images -->
            <div class="col-md-6">
                <div class="room-images">
                    <!-- Fancybox Image Slider -->
                    @foreach($room->images as $index => $image)
                        <a href="{{ asset('storage/' . $image->image_path) }}" data-fancybox="gallery" data-caption="{{ $room->name }} Image {{ $index + 1 }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $room->name }} image" class="img-fluid">
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Room Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ $room->name }}</h3>
                        <p class="card-text">{{ $room->description }}</p>
                        <p class="card-text"><strong>Price:</strong> ${{ $room->price }} per night</p>
                        <p class="card-text"><strong>Availability:</strong> 
                            @if(!$room->available)
                            <span class="btn btn-danger">Not available until {{ \Carbon\Carbon::parse($room->available_from)->format('D M, Y') }} ({{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($room->available_from)->startOfDay()) }}
                                days left)</span>
                            @else
                               <span class="btn btn-success"> Available now</span>
                            @endif
                        </p>


                        <!-- Room Features -->
                        <h5>Features:</h5>
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
                            <span class="read-less-btn" style="display: none;">Read less</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery, Bootstrap JS, and Fancybox JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Fancybox JS for image gallery slider -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script>
        // Initialize Fancybox
        Fancybox.bind("[data-fancybox='gallery']", {
            // Optional settings for navigation and display
            closeButton: "outside", // Close button outside the image
            animated: true,
            dragToClose: true, // Drag to close functionality
        });

        // "Read More" and "Read Less" functionality for room features
        document.querySelectorAll('.read-more-btn').forEach(button => {
            button.addEventListener('click', function () {
                const hiddenFeatures = this.previousElementSibling.querySelectorAll('.read-more');
                hiddenFeatures.forEach(feature => feature.style.display = 'list-item');
                this.style.display = 'none'; // Hide the "Read more" button
                this.nextElementSibling.style.display = 'inline'; // Show "Read less" button
            });
        });

        document.querySelectorAll('.read-less-btn').forEach(button => {
            button.addEventListener('click', function () {
                const hiddenFeatures = this.previousElementSibling.previousElementSibling.querySelectorAll('.read-more');
                hiddenFeatures.forEach(feature => feature.style.display = 'none');
                this.style.display = 'none'; // Hide the "Read less" button
                this.previousElementSibling.style.display = 'inline'; // Show "Read more" button
            });
        });
    </script>
</body>
</html>
