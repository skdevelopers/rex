@extends('layouts.vertical', ['title' => 'Assign Role', 'sub_title' => 'Roles'])


@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Edit Roles for {{ $user->name }}</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update-roles', $user->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Roles</label>
            @foreach($roles as $role)
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->roles->contains($role) ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    <span class="ml-2 text-gray-700">{{ $role->name }}</span>
                </div>
            @endforeach
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Roles</button>
            <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Back</a>
        </div>
    </form>
</div>
@endsection