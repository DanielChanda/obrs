@extends('passenger.layouts.app')

@section('title', 'Search Results')

@section('content')
<h3>Available Schedules</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Bus</th>
            <th>Route</th>
            <th>Departure</th>
            <th>Fare</th>
            <th>Seats</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->bus->bus_number }}</td>
            <td>{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
            <td>{{ $schedule->departure_time }}</td>
            <td>{{ $schedule->fare }}</td>
            <td>{{ $schedule->available_seats }}</td>
            <td>
                <form method="POST" action="{{ route('passenger.book', $schedule->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">Book</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
