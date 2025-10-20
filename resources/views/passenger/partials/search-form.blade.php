<h3 class="text-center">{{ $title }}</h3>
@php
    // Build a map of origins to their valid destinations for JS use.
    $tripMap = [];
    foreach ($trips as $trip) {
        $tripMap[$trip->origin][] = $trip->destination;
    }

    // Build a nested map: [origin][destination] = [array of available dates]
    $scheduleMap = [];
    foreach ($schedules as $schedule) {
        $origin = $schedule->route->origin;
        $destination = $schedule->route->destination;
        $date = \Carbon\Carbon::parse($schedule->departure_time)->toDateString();
        $scheduleMap[$origin][$destination][] = $date;
    }
@endphp

<!-- first check if origins and destinations are available -->
@if($origins->isEmpty() || $destinations->isEmpty())
    <div class="alert alert-warning text-center mt-3">
        No available trips at the moment. Please check back later.
    </div>
    @php return; @endphp
@endif
<form method="POST" action="{{ route('passenger.search') }}" class="row g-3 mt-3">
    @csrf
    <div class="col-md-4">
        <label for="origin" class="form-label">Origin</label>
        <select class="form-control" id="origin" name="origin" required>
            <option value="">Select Origin</option>
            @foreach($origins as $origin)
                <option value="{{ $origin }}">{{ $origin }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="destination" class="form-label">Destination</label>
        <select class="form-control" id="destination" name="destination" required>
            <option value="">Select Destination</option>
            @foreach($destinations as $destination)
                <option value="{{ $destination }}">{{ $destination }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="date" class="form-label">Departure Date</label>
        <select class="form-control" id="date" name="date" required>
            <option value="">Select Date</option>
            {{-- Options will be populated by JS --}}
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </div>
</form>

@push('scripts')
<script>
    // Pass the PHP trip map to JavaScript for use in the browser.
    const tripMap = @json($tripMap);

    document.addEventListener('DOMContentLoaded', function () {
        const originSelect = document.getElementById('origin');
        const destinationSelect = document.getElementById('destination');

        // When the user selects an origin, update the destination dropdown.
        originSelect.addEventListener('change', function () {
            const selectedOrigin = this.value;

            // Clear current destination options.
            destinationSelect.innerHTML = '<option value="">Select Destination</option>';

            // If the selected origin has valid destinations, add them as options.
            if (tripMap[selectedOrigin]) { // Check if the selected origin exists as a key in the tripMap object.
                // tripMap is a JavaScript object where each key is an origin, and its value is an array of valid destinations.
                // Example: { "Lusaka": ["Ndola", "Kitwe"], "Ndola": ["Lusaka"] }

                tripMap[selectedOrigin].forEach(function (dest) { // Loop through each destination for the selected origin.
                    // forEach will execute the function for every destination in the array.

                    const option = document.createElement('option'); // Create a new <option> element for the dropdown.

                    option.value = dest; // Set the value attribute of the option to the destination name.
                    option.textContent = dest; // Set the visible text of the option to the destination name.

                    destinationSelect.appendChild(option); // Add the new option to the destination <select> dropdown.
                });
            }
        });

        // Optionally, trigger change on page load to pre-populate destinations
        // if an origin is already selected (e.g., after a validation error).
        originSelect.dispatchEvent(new Event('change'));
    });
</script>

<script>
    // Convert the PHP schedule map to a JavaScript object
    const scheduleMap = @json($scheduleMap);

    document.addEventListener('DOMContentLoaded', function () {
        const originSelect = document.getElementById('origin');
        const destinationSelect = document.getElementById('destination');
        const dateSelect = document.getElementById('date');

        // When the destination changes, update the date dropdown
        destinationSelect.addEventListener('change', function () {
            const selectedOrigin = originSelect.value;
            const selectedDestination = destinationSelect.value;

            // Clear current date options
            dateSelect.innerHTML = '<option value="">Select Date</option>';

            // If there are available dates for the selected origin and destination, add them
            if (
                scheduleMap[selectedOrigin] &&
                scheduleMap[selectedOrigin][selectedDestination]
            ) {
                // Use a Set to avoid duplicate dates
                const uniqueDates = [...new Set(scheduleMap[selectedOrigin][selectedDestination])];
                uniqueDates.forEach(function (date) {
                    const option = document.createElement('option');
                    option.value = date;
                    option.textContent = date;
                    dateSelect.appendChild(option);
                });
            }
        });

        // Optionally, trigger change on page load if destination is pre-selected
        destinationSelect.dispatchEvent(new Event('change'));
    });
</script>
@endpush