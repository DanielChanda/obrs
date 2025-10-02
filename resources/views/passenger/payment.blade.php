@extends('passenger.layouts.app')

@section('title', 'Payment')

@section('content')
<h3>Payment for Booking #{{ $booking->id }}</h3>

<div class="card mt-3">
  <div class="card-body">
    <p><strong>Bus:</strong> {{ $booking->schedule->bus->bus_number }}</p>
    <p><strong>Route:</strong> {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
    <p><strong>Departure:</strong> {{ $booking->schedule->departure_time }}</p>
    <p><strong>Fare:</strong> ${{ $booking->schedule->fare }}</p>
  </div>
</div>

<form method="POST" action="{{ route('passenger.processPayment', $booking->id) }}" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-success">Pay Now (Mock)</button>
</form>
@endsection
