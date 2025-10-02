<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>E-Ticket #{{ $booking->id }}</title>
    <style>
        /* General font */
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        /* Ticket container */
        .ticket {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 2px solid #333;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Header */
        .ticket-header {
            background-color: #2a9df4;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .ticket-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .ticket-header h4 {
            margin: 5px 0 0;
            font-weight: normal;
        }

        /* Details section */
        .ticket-details {
            padding: 20px;
        }

        .ticket-details p {
            margin: 8px 0;
            font-size: 14px;
        }

        .ticket-details p span {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }

        /* QR code section */
        .ticket-qr {
            text-align: center;
            padding: 20px;
            border-top: 1px dashed #ccc;
        }

        .ticket-qr img {
            width: 150px;
            height: 150px;
        }

        /* Footer */
        .ticket-footer {
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
            font-size: 12px;
            color: #555;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <h2>Online Bus Reservation</h2>
            <h4>E-Ticket</h4>
        </div>

        <!-- Details -->
        <div class="ticket-details">
            <p><span>Booking ID:</span> {{ $booking->id }}</p>
            <p><span>Passenger:</span> {{ $booking->user->name }}</p>
            <p><span>Bus:</span> {{ $booking->schedule->bus->bus_number }}</p>
            <p><span>Route:</span> {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
            <p><span>Departure:</span> {{ $booking->schedule->departure_time }}</p>
            <p><span>Seat:</span> {{ $booking->seat_number }}</p>
            <p><span>Status:</span> {{ ucfirst($booking->status) }}</p>
        </div>

        <!-- QR Code -->
        <div class="ticket-qr">
            <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code">
            <p>Scan for verification</p>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            Thank you for booking with Online Bus Reservation. Safe travels!
        </div>
    </div>
</body>
</html>
