@extends('passenger.layouts.app')

@section('title', 'Search Bus')

@section('content')

@include('passenger.partials.search-form', ['title' => 'Search for a Trip'])

@endsection
