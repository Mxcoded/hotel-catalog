@extends('layouts.catalog-layout')

@section('title', 'Room List')

@section('content')
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Rooms List</h1>
                <p class="lead text-muted">Explore our range of beautifully designed rooms.</p>
                @auth
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary">Add Room</a>
                @endauth
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <a href="#" class="btn btn-info my-2 feedback">Submit A Feedback</a>
            </div>
        </div>
    </section>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @if ($rooms->isEmpty())
                    <div class="alert alert-warning">No rooms found.</div>
                @else
                    @foreach ($rooms as $room)
                        <div class="col">
                            <div class="card shadow-sm">
                                <div id="carouselRoom{{ $room->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <a href="{{ route('rooms.show', $room) }}">
                                        <div class="carousel-inner">
                                            @foreach ($room->images as $index => $image)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                                        class="d-block w-100" alt="Room Image">
                                                </div>
                                            @endforeach
                                        </div>
                                    </a>
                                    <a class="carousel-control-prev" href="#carouselRoom{{ $room->id }}" role="button"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselRoom{{ $room->id }}" role="button"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">{{ $room->name }}</h5>
                                    <p class="card-text">{{ $room->description }}</p>
                                    <p class="card-text"><strong>Price:</strong>
                                        &#8358;{{ number_format($room->price, 2, '.', ',') }} / Night</p>
                                    <p class="card-text"><strong>Features:</strong>
                                    <ul class="list-group list-group-flush">
                                        @foreach (json_decode($room->features, true) ?? [] as $index => $feature)
                                            <li class="list-group-item feature-item{{ $index >= 2 ? ' d-none' : '' }}"
                                                data-room-id="{{ $room->id }}">{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                    </p>
                                    @if (count(json_decode($room->features, true) ?? []) > 2)
                                        <a href="javascript:void(0);" class="read-more-link"
                                            data-room-id="{{ $room->id }}">Show All</a>
                                    @endif


                                    @auth
                                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endauth
                                    <small class="text-muted status-text">
                                        @if (!$room->available)
                                            <span class="status-unavailable">
                                                Not available until
                                                {{ \Carbon\Carbon::parse($room->available_from)->format('D M, Y') }}
                                                ({{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($room->available_from)->startOfDay()) }}
                                                days left)
                                            </span>
                                        @else
                                            <span class="status-available">
                                                Available now
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination Links -->
<div class="d-flex justify-content-center mt-4">
    {{ $rooms->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".read-more-link").forEach(link => {
                const roomId = link.getAttribute("data-room-id");
                const hiddenFeatures = document.querySelectorAll(
                    `.feature-item.d-none[data-room-id="${roomId}"]`);

                let isExpanded = false;

                link.addEventListener("click", function() {
                    isExpanded = !isExpanded; // Toggle the state
                    hiddenFeatures.forEach(feature => feature.classList.toggle("d-none", !
                        isExpanded));
                    link.textContent = isExpanded ? "Show Less" : "Show All"; // Update link text
                });
            });
        });
    </script>
@endsection
