<!-- resources/views/rooms/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Admin Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            margin-top: 50px;
            max-width: 700px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
     

.tooltiptext {
  visibility: hidden;
  font-style: italic;

}

.info:hover {
  .tooltiptext {
    visibility: visible;
    opacity: 1;
    }
}
    </style>
</head>
<body>

    <div class="container">
        <h1><i class="fa fa-plus-circle"></i> Add Room</h1>
        <a href="{{ route('rooms.index') }}" class="btn btn-info btn-lg">
            <span class="glyphicon glyphicon-chevron-left"></span> Back to Rooms
          </a>
        <!-- Form -->
        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" >
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
                    <span class="input-group-text"><i class="fa fa-naira-sign"></i></span>
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

            <div class="form-group">
                <label for="available">Available</label>
                <select class="form-control" name="available" id="availabilitySelect">
                    <option value="1" >Yes</option>
                    <option value="0">Not Available Until...</option>
                </select>
            </div>

            <!-- Date Picker for Availability Countdown -->
            <div id="availabilityCountdown" class="form-group hidden">
                <label for="available_from">Available From</label>
                <input type="text" class="form-control" name="available_from" id="availableFromDate" placeholder="Select date">
            </div>

            <!-- Images -->
            <div class="mb-3">
                <label for="images" class="form-label">Room Images</label>
                <input type="file" class="form-control" name="images[]" id="images" multiple>
                <small class="form-text text-muted">You can upload multiple images. <span class="info"><span class="fa fa-info-circle"></span><span class="tooltiptext">Recommended 5 Photos per Room not more than 20MB in total.</span></div>
                </span></small>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Save Room</button>
            </div>

        </form>

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