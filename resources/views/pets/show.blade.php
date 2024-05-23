@extends('layouts.default')

@section('content')

<div class="container">
    <h1>Pet Details</h1>
    @if (!$pet)
    <p>No pet found.</p>
    @else
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Pet Information</h2>
            <p><strong>ID:</strong> {{ $pet['id'] }}</p>
            <p><strong>Name:</strong> {{ $pet['name'] }}</p>
            <p><strong>Status:</strong> {{ $pet['status'] }}</p>
            <p><strong>Category:</strong> {{ $pet['category']['name'] }}</p>
            <p><strong>Photo URLs:</strong></p>
            <ul>
                @foreach ($pet['photoUrls'] as $photoUrl)
                <li>{{ $photoUrl }}</li>
                @endforeach
            </ul>
            <p><strong>Tags:</strong></p>
            <ul>
                @foreach ($pet['tags'] as $tag)
                <li>{{ $tag['name'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

@endsection