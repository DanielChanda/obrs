@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('page-title', 'Booking Management')
@section('page-subtitle', 'List of all bookings in the system')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">All Bookings</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
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
                        <th>Booked On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->schedule->route->origin }} -> {{ $booking->schedule->route->destination }}</td>
                            <td>{{ $booking->schedule->bus->bus_number }}</td>
                            <td>{{ $booking->seat_number }}</td>
                            <td>{{ $booking->schedule->fare }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($booking->status) }}</span></td>
                            <td><span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($booking->payment_status) }}</span></td>
                            <td>{{ $booking->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $booking->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $booking->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $booking->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel-{{ $booking->id }}">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to permanently delete booking #<strong>{{ $booking->id }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete Booking</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection