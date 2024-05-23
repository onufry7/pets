@extends('layouts.default')

@section('content')

@if (session('status'))
<p class="py-8 text-center">{{ session('status') }}</p>
@endif

@if ($errors->any())
<div class="py-8">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pets.index') }}" method="GET">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="available" {{ $status=='available' ? 'selected' : '' }}>Available</option>
        <option value="pending" {{ $status=='pending' ? 'selected' : '' }}>Pending</option>
        <option value="sold" {{ $status=='sold' ? 'selected' : '' }}>Sold</option>
    </select>
    <button type="submit">Apply Filter</button>
</form>

<h1>List of Pets - {{ $status }}</h1>

@if (!$pets)
<p>No pets found.</p>
@else
<ul>
    @foreach ($pets as $pet)
    <li>
        <strong>Name:</strong> {{ $pet['name'] }}<br>
        <a href="{{ route('pets.show', $pet['id']) }}">Show</a> |
        <a href="{{ route('pets.edit', $pet['id']) }}">Update</a> |
        <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</button>
        </form>
    </li>
    @endforeach
</ul>
@endif

@endsection