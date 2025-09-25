@extends('layouts.app')

@section('title', 'Schedules')

@section('content')
<h3>My Schedules</h3>
<a href="{{ route('schedules.create') }}" class="btn btn-primary mb-2">Add Schedule</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Bus</th>
            <th>Route</th>
            <th>Departure</th>
            <th>Fare</th>
        </tr>
    </thead>
    <tbody>
    @foreach($schedules as $schedule)
        <tr>
            <td>{{ $schedule->bus->bus_number }}</td>
            <td>{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</td>
            <td>{{ $schedule->departure_time }}</td>
            <td>{{ $schedule->fare }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
