<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-search me-2 text-primary"></i>Search for Trips
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('passenger.search') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label for="origin" class="form-label fw-semibold">From</label>
                <input type="text" class="form-control" id="origin" name="origin" 
                       placeholder="Enter origin city" required value="{{ old('origin') }}">
            </div>
            <div class="col-md-4">
                <label for="destination" class="form-label fw-semibold">To</label>
                <input type="text" class="form-control" id="destination" name="destination" 
                       placeholder="Enter destination city" required value="{{ old('destination') }}">
            </div>
            <div class="col-md-4">
                <label for="date" class="form-label fw-semibold">Departure Date</label>
                <input type="date" class="form-control" id="date" name="date" 
                       min="{{ date('Y-m-d') }}" required value="{{ old('date') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-search me-2"></i>Search Buses
                </button>
            </div>
        </form>
    </div>
</div>