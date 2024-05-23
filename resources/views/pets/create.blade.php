@extends('layouts.default')

@section('content')

<h1 class="py-8 text-center">Add Pet</h1>

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

<div class="py-16">
    <h2>Add New Pet</h2>
    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Name" required>
        </div>
        <div>
            <label for="category_name">Category</label>
            <input type="text" name="category_name" id="category_name" placeholder="Category" required>
        </div>
        <div>
            <label for="tag_name">Tag</label>
            <input type="text" name="tag_name" id="tag_name" placeholder="Tag" required>
        </div>
        <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="available">Available</option>
                <option value="pending">Pending</option>
                <option value="sold">Sold</option>
            </select>
        </div>
        <div>
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo" required>
        </div>
        <div>
            <button type="submit">Add Pet</button>
        </div>
    </form>
</div>

@endsection