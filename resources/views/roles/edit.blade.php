@extends('layouts.vertical', ['title' => 'Edit Role', 'sub_title' => 'Roles'])

@section('content')

    <h1>Assign Roles to {{ $user->name }}</h1>
    <form action="{{ route('users.roles.update', $user->id) }}" method="POST">
        @csrf
        @foreach($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
            {{ $role->name }}<br>
        @endforeach
        <button type="submit">Update Roles</button>
    </form>
@endsection
