@extends('layouts.vertical', ['title' => 'Role', 'sub_title' => 'Roles'])

@section('content')
    <h1>Roles</h1>
    <a href="{{ route('roles.create') }}">Create New Role</a>
    <ul>
        @foreach($roles as $role)
            <li>{{ $role->name }} 
                <a href="{{ route('roles.edit', $role->id) }}">Edit</a>
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection

