@extends('layouts.app')

@section('title', 'Passenger Dashboard')

@section('content')
<h3>My Bookings</h3>
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Bus</th>
            <th>Route</th>
            <th>Departure</th>
            <th>Seat</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($bookings as $booking)
        <tr>
            <td>{{ $booking->schedule->bus->bus_number }}</td>
            <td>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</td>
            <td>{{ $booking->schedule->departure_time }}</td>
            <td>{{ $booking->seat_number }}</td>
            <td>{{ ucfirst($booking->status) }}</td>
            <td>
                @if($booking->payment_status === 'paid')
                    <a href="{{ route('passenger.ticket', $booking->id) }}" class="btn btn-sm btn-success">View Ticket</a>
                    <a href="{{ route('passenger.ticket.download', $booking->id) }}" class="btn btn-sm btn-primary">Download PDF</a>
                @else
                    <span class="badge bg-secondary">Unpaid</span>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center">No bookings yet</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
