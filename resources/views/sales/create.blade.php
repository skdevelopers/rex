{{-- resources/views/sales/create.blade.php --}}
@extends('layouts.vertical', ['title' => 'Create Sale', 'sub_title' => 'Sales'])

@section('content')
    <div>
        <h1>Create Sale</h1>
        <form method="post" class="valid-form grid lg:grid-cols-3 gap-6" action="{{ route('sales.store') }}">
            @csrf
            @include('sales._form')

            <div class="form-group col-span-3">
                <button type="submit" class="btn bg-primary text-white">Create Sale</button>
            </div>
        </form>
    </div>
@endsection
