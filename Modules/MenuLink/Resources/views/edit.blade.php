@extends('layouts.app')

@section('content')
    <div class="card">
        <h1>Edit {Model}</h1>

        <x-form action="{{ route('app.{module}.update', ${model}->id) }}" method="patch">
            <x-form.input name="name">{{ ${model}->name }}</x-form.input>
            <x-form.button>Update</x-form.button>
        </x-form>
    </div>
@endsection
