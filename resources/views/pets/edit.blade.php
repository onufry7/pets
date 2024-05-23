@extends('layouts.default')

@section('content')

<h1>Edit Pet</h1>

@if (session('status'))
<p>{{ session('status') }}</p>
@endif

@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pets.update', $pet['id']) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label for="name">Name:</label>
    <input type="text" name="name" value="{{ $pet['name'] }}" required>

    <label for="status">Status:</label>
    <select name="status" required>
        <option value="available" {{ $pet['status']=='available' ? 'selected' : '' }}>Available</option>
        <option value="pending" {{ $pet['status']=='pending' ? 'selected' : '' }}>Pending</option>
        <option value="sold" {{ $pet['status']=='sold' ? 'selected' : '' }}>Sold</option>
    </select>

    <label for="category_name">Category Name:</label>
    <input type="text" name="category_name" value="{{ $pet['category']['name'] }}" required>

    <label for="tag_name">Tag Name:</label>
    <input type="text" name="tag_name" value="{{ $pet['tags'][0]['name'] }}" required>

    <label for="photo">Upload Photo:</label>
    <input type="file" name="photo">

    <button type="submit">Update Pet</button>
</form>

@endsection