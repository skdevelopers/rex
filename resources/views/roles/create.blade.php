@extends('layouts.vertical', ['title' => 'Create Role', 'sub_title' => 'Role'])

@section('content')
<h1>Create Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Role Name">
        <button type="submit">Create</button>
    </form>
@endsection