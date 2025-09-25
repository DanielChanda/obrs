@extends('layouts.app')

@section('title', 'My Buses')

@section('content')
<h3>My Buses</h3>
<a href="{{ route('buses.create') }}" class="btn btn-success mb-2">Add Bus</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Bus Number</th>
            <th>Type</th>
            <th>Capacity</th>
        </tr>
    </thead>
    <tbody>
    @foreach($buses as $bus)
        <tr>
            <td>{{ $bus->bus_number }}</td>
            <td>{{ $bus->bus_type }}</td>
            <td>{{ $bus->capacity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
