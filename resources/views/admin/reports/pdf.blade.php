<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>System Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Passenger</th>
                <th>Route</th>
                <th>Bus</th>
                <th>Seat</th>
                <th>Fare</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td>#{{ $b->id }}</td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->schedule->route->origin }} → {{ $b->schedule->route->destination }}</td>
                <td>{{ $b->schedule->bus->bus_number }}</td>
                <td>{{ $b->seat_number }}</td>
                <td>${{ number_format($b->schedule->fare, 2) }}</td>
                <td>{{ ucfirst($b->status) }}</td>
                <td>{{ ucfirst($b->payment_status) }}</td>
                <td>{{ $b->created_at->format('M j, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
