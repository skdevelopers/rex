@extends('layouts.vertical', ['title' => 'Errors', 'sub_title' => 'Errors'])

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Access Denied!</strong>
        <span class="block sm:inline">{{ $message }}</span>
    </div>
    <a href="{{ url()->previous() }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Go Back</a>
</div>
@endsection
