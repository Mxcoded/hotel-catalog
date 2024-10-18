<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <!-- Bootstrap CSS for better UI -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        /* Custom styling for the form */
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        h1 {
            color: #343a40;
            margin-bottom: 30px;
        }
        .form-group label {
            font-weight: bold;
        }
        .room-images img {
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .btn-warning, .btn-danger {
            margin-top: 10px;
        }
        .room-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .room-images div {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .alert-danger ul {
            list-style-type: none;
            padding-left: 0;
        }
    
        /* Additional styling */
        .hidden {
            display: none;
        }
        /* body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .room-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .room-images img {
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        } */

    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Room</h1>
    
        <a href="{{ route('rooms.index') }}" class="btn btn-info btn-lg">
            <span class="glyphicon glyphicon-chevron-left"></span> Back to Rooms
          </a>
        <!-- Update Room Form -->
        <form action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Room Name</label>
                <input type="text" class="form-control" name="name" value="{{ $room->name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" rows="4" required>{{ $room->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (Naira)</label>
                <input type="number" class="form-control" name="price" step="0.01" value="{{ $room->price }}" required>
            </div>

            <div class="form-group">
                <label for="features">Features (comma separated)</label>
                <input type="text" class="form-control" name="features" value="{{ is_array(json_decode($room->features, true)) ? implode(', ', json_decode($room->features, true)) : $room->features }}">
            </div>

            <div class="form-group">
                <label for="available">Available</label>
                <select class="form-control" name="available" id="availabilitySelect">
                    <option value="1" {{ $room->available ? 'selected' : '' }}>Yes</option>
                    <option value="0"{{ !$room->available ? 'selected' : '' }}>Not Available Until...</option>
                </select>
            </div>

            <!-- Date Picker for Availability Countdown -->
            <div id="availabilityCountdown" class="form-group hidden">
                <label for="available_from">Available From</label>
                <input type="text" class="form-control" name="available_from" id="availableFromDate" placeholder="Select date">
            </div>

            <div class="form-group">
                <label for="images">Room Images</label>
                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
            </div>

            <button type="submit" class="btn btn-warning btn-block">Update Room</button>
        </form>

        <h4 class="mt-5">Current Images</h4>
        <div class="room-images">
            @foreach($room->images as $image)
                <div>
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Room Image" style="width: 150px;">
                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="image_id" value="{{ $image->id }}">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Displaying validation errors -->
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     <!-- jQuery UI DatePicker and custom JS to handle the countdown logic -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
     <script>
         $(document).ready(function() {
             // Datepicker for selecting the availability date
             $("#availableFromDate").datepicker({
                 dateFormat: "yy-mm-dd"
             });
 
             // Toggle the availability countdown field based on the selection
             $("#availabilitySelect").change(function() {
                 if ($(this).val() == '0') {
                     $("#availabilityCountdown").removeClass('hidden');
                 } else {
                     $("#availabilityCountdown").addClass('hidden');
                 }
             });
         });
     </script>
</body>
</html>