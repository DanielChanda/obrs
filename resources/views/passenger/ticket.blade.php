@extends('layouts.app')

@section('title', 'E-Ticket')

@section('content')
<h3>E-Ticket</h3>

<div class="card mt-3">
  <div class="card-body">
    <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
    <p><strong>Passenger:</strong> {{ $booking->user->name }}</p>
    <p><strong>Bus:</strong> {{ $booking->schedule->bus->bus_number }}</p>
    <p><strong>Route:</strong> {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
    <p><strong>Departure:</strong> {{ $booking->schedule->departure_time }}</p>
    <p><strong>Seat:</strong> {{ $booking->seat_number }}</p>
    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>

    <div class="mt-3 text-center">
        <h5>QR Code</h5>
        {!! QrCode::encoding('UTF-8')->size(200)->generate($qrData) !!}
    </div>
  </div>
</div>

<a href="{{ route('passenger.dashboard') }}" class="btn btn-secondary mt-3">Back to Dashboard</a>
@endsection
