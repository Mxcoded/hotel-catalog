@extends('layouts.catalog-layout')

@section('title', $room->name)

@section('content')
<div class="container">
    <h1 class="text-center">{{ $room->name }} - Room Details</h1>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary mb-4">Back to Rooms</a>

    <div class="row">
        <!-- Room Images -->
        <div class="col-md-6">
            <div class="room-images">
                @foreach($room->images as $index => $image)
                    <a href="{{ asset('storage/' . $image->image_path) }}" data-fancybox="gallery" data-caption="{{ $room->name }} Image {{ $index + 1 }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $room->name }} image" class="img-fluid mb-2">
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
                    <p class="card-text"><strong>Price:</strong> &#8358;{{ number_format($room->price, 2, '.', ',') }} per night</p>
                    <p class="card-text"><strong>Availability:</strong>
                        @if(!$room->available)
                            <span class="status-unavailable">Not available until {{ \Carbon\Carbon::parse($room->available_from)->format('D M, Y') }} ({{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($room->available_from)->startOfDay()) }} days left)</span>
                        @else
                            <span class="status-available">Available now</span>
                        @endif
                    </p>

                    <!-- Room Features -->
                    <h5>Features:</h5>
                    <ul class="room-features list-group list-group-flush">
                        @php
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

<script>
    // "Read More" and "Read Less" functionality
    document.querySelectorAll('.read-more-btn').forEach(button => {
        button.addEventListener('click', function () {
            this.previousElementSibling.querySelectorAll('.read-more').forEach(feature => feature.style.display = 'list-item');
            this.style.display = 'none';
            this.nextElementSibling.style.display = 'inline';
        });
    });

    document.querySelectorAll('.read-less-btn').forEach(button => {
        button.addEventListener('click', function () {
            this.previousElementSibling.previousElementSibling.querySelectorAll('.read-more').forEach(feature => feature.style.display = 'none');
            this.style.display = 'none';
            this.previousElementSibling.style.display = 'inline';
        });
    });
</script>
@endsection