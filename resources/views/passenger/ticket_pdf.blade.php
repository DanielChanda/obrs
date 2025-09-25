<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Ticket #{{ $booking->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .ticket {
            border: 2px dashed #333;
            padding: 20px;
            width: 500px;
            margin: auto;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin: 10px 0; }
        .qr { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h2>Online Bus Reservation System</h2>
            <h4>E-Ticket</h4>
        </div>

        <div class="details">
            <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
            <p><strong>Passenger:</strong> {{ $booking->user->name }}</p>
            <p><strong>Bus:</strong> {{ $booking->schedule->bus->bus_number }}</p>
            <p><strong>Route:</strong> {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
            <p><strong>Departure:</strong> {{ $booking->schedule->departure_time }}</p>
            <p><strong>Seat:</strong> {{ $booking->seat_number }}</p>
            <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
        </div>

        <div class="qr">
            <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code">
        </div>
    </div>
</body>
</html>
