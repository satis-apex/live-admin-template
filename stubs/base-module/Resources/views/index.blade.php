@extends('layouts.app')

@section('content')
    <div class="card">
    <h1>{Module}</h1>

    <p><a href="{{ route('app.{module}.create') }}">Add {Model}</a> </p>

    <table>
        <tr>
            <td>Name</td>
            <td>Action</td>
        </tr>
        @foreach(${module} as ${model})
            <tr>
                <td>{{ ${model}->name }}</td>
                <td>
                    <a href="{{ route('app.{module}.edit', ${model}->id) }}">Edit</a>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">Delete</a>
                    <x-form id="delete-form" method="delete" action="{{ route('app.{module}.delete', ${model}->id) }}" />
                </td>
            </tr>
        @endforeach
    </table>
    </div>
@endsection
