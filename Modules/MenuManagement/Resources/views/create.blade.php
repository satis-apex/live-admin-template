@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Add {Model}</h1>

        <x-form action="{{ route('app.{module}.create') }}">
            <x-form.input name="name" />
            <x-form.button>Submit</x-form.button>
        </x-form>
    </div>
@endsection
