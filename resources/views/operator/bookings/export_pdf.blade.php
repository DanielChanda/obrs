<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bookings Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <h2>Passenger Bookings Report</h2>
    <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Passenger</th>
                <th>Email</th>
                <th>Route</th>
                <th>Bus</th>
                <th>Seat</th>
                <th>Fare</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Booked On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
                <tr>
                    <td>#{{ $b->id }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->user->email }}</td>
                    <td>{{ $b->schedule->route->origin }} → {{ $b->schedule->route->destination }}</td>
                    <td>{{ $b->schedule->bus->bus_number }}</td>
                    <td>{{ $b->seat_number }}</td>
                    <td>${{ number_format($b->schedule->fare, 2) }}</td>
                    <td>{{ ucfirst($b->status) }}</td>
                    <td>{{ ucfirst($b->payment_status) }}</td>
                    <td>{{ $b->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
