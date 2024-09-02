@extends('layouts.vertical', ['title' => '', 'sub_title' => ''])


@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">User Details</h1>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Roles:</strong> {{ implode(', ', $user->roles->pluck('name')->toArray()) }}</p>
        <p><strong>Permissions:</strong> {{ implode(', ', $user->permissions->pluck('name')->toArray()) }}</p>
    </div>

    <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back to Users</a>
</div>
@endsection
