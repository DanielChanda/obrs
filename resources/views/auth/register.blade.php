@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h3 class="mb-3">Register</h3>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</div>
@endsection
