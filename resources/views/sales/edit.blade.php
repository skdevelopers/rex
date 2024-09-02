{{-- resources/views/sales/edit.blade.php --}}
@extends('layouts.vertical', ['title' => 'Edit Sale', 'sub_title' => 'Sales'])

@section('content')
    <div>
        <h1>Edit Sale</h1>
        <form method="post" class="valid-form grid lg:grid-cols-3 gap-6" action="{{ route('sales.update', $sale->id) }}">
            @csrf
            @method('PUT')
            @include('sales._form')

            <div class="form-group col-span-3">
                <button type="submit" class="btn bg-primary text-white">Update Sale</button>
            </div>
        </form>
    </div>
@endsection
