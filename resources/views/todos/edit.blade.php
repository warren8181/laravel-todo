@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Modification de la Todo <span class="badge badge-dark">#{{ $todo->id }}</span> </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('todos.update', $todo->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Titre</label>
                <input type="text" name="name" value="{{ $todo->name }}" class="form-control" id="name" aria-describedby="nameHelp">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" value="{{ $todo->description }}" class="form-control" id="description" aria-describedby="nameHelp">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="done" id="done" {{ $todo->done ? 'checked' : '' }} value=1>
                <label for="done" class="form-check-label">Done ?</label>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>

@endsection
