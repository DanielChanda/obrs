@extends('layouts.app')

@section('title', 'Search Bus')

@section('content')
<h3>Search for a Trip</h3>
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
        <input type="date" class="form-control" id="date" name="date" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </div>
</form>
@endsection
