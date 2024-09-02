@extends('layouts.vertical', ['title' => 'Create Permission', 'sub_title' => 'Permission'])

@section('content')
    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Create Permission</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('permissions.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="model" class="block text-gray-700 text-sm font-bold mb-2">Select Model</label>
                <select name="model" id="model"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach ($models as $model)
                        <option value="{{ $model }}">{{ $model }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Operations</label>
                @foreach ($operations as $operation)
                    <div>
                        <input type="checkbox" name="operations[]" value="{{ $operation }}"
                            id="operation_{{ $operation }}">
                        <label for="operation_{{ $operation }}">{{ ucfirst($operation) }}</label>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
                <a href="{{ route('permissions.index') }}"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Back</a>
            </div>
        </form>
    </div>
@endsection
