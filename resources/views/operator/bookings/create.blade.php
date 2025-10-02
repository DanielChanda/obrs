@extends('operator.layouts.app')

@section('title', 'Manual Booking')
@section('page-title', 'Manual Booking')
@section('page-subtitle', 'Book passengers at the station')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('operator.bookings.storeManual') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Schedule</label>
                <select name="schedule_id" id="schedule_id" class="form-select" required>
                    <option value="">-- select --</option>
                    @foreach($schedules as $s)
                        <option value="{{ $s->id }}" {{ $selectedSchedule==$s->id?'selected':'' }}>
                            {{ $s->route->origin }} → {{ $s->route->destination }}
                            | Bus {{ $s->bus->bus_number }}
                            | {{ $s->departure_time }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($schedule)
                <div class="mb-3">
                    <label>Seat Map</label>
                    <div class="d-flex flex-wrap gap-2">
                        @for($i=1; $i <= $schedule->bus->capacity; $i++)
                            <button type="button" class="btn btn-sm {{ in_array($i,$occupiedSeats)?'btn-secondary':'btn-outline-primary' }}"
                                    onclick="document.getElementById('seat_number').value={{ $i }}" 
                                    {{ in_array($i,$occupiedSeats)?'disabled':'' }}>
                                {{ $i }}
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="seat_number" id="seat_number" required>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 mb-3"><input type="text" name="passenger_name" class="form-control" placeholder="Name" required></div>
                <div class="col-md-4 mb-3"><input type="email" name="passenger_email" class="form-control" placeholder="Email" required></div>
                <div class="col-md-4 mb-3"><input type="text" name="passenger_phone" class="form-control" placeholder="Phone" required></div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3"><input type="number" name="fare" class="form-control" placeholder="Fare" required></div>
                <div class="col-md-4 mb-3">
                    <select name="payment_status" class="form-select">
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary">Save Booking</button>
        </form>
    </div>
</div>
@endsection
