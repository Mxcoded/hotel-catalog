@extends('layouts.app')

@section('title', 'Add Room - Admin Panel')

@push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@section('content')
    <div class="container bg-white p-4 rounded shadow">
        <h1 class="text-center"><i class="fa fa-plus-circle"></i> Add Room</h1>
        <a href="{{ route('rooms.index') }}" class="btn btn-info mb-4">
            <i class="fas fa-chevron-left"></i> Back to Rooms
        </a>

        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Room Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-door-open"></i></span>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter room name" required>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter room description" required></textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                    <input type="number" class="form-control" name="price" id="price" step="0.01" placeholder="Enter room price" required>
                </div>
            </div>

            <!-- Features -->
            <div class="mb-3">
                <label for="features" class="form-label">Features (JSON format)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-list"></i></span>
                    <input type="text" class="form-control" name="features" id="features" placeholder='e.g. ["WiFi", "Air Conditioning"]'>
                </div>
                <small class="form-text text-muted">Please enter features in JSON format, e.g. <code>["WiFi", "Air Conditioning"]</code>.</small>
            </div>

            <!-- Availability -->
            <div class="form-group mb-3">
                <label for="available" class="form-label">Available</label>
                <select class="form-control" name="available" id="availabilitySelect">
                    <option value="1">Yes</option>
                    <option value="0">Not Available Until...</option>
                </select>
            </div>

            <!-- Date Picker for Availability Countdown -->
            <div id="availabilityCountdown" class="form-group mb-3 d-none">
                <label for="available_from" class="form-label">Available From</label>
                <input type="text" class="form-control" name="available_from" id="availableFromDate" placeholder="Select date">
            </div>

            <!-- Images -->
            <div class="mb-3">
                <label for="images" class="form-label">Room Images</label>
                <input type="file" class="form-control" name="images[]" id="images" multiple>
                <small class="form-text text-muted">Recommended 5 photos per room, total size under 20MB. 
                    <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="Up to 20MB."></i>
                </small>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Room</button>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#availableFromDate").datepicker({ dateFormat: "yy-mm-dd" });

            $("#availabilitySelect").change(function() {
                $("#availabilityCountdown").toggleClass('d-none', $(this).val() == '1');
            });

            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
