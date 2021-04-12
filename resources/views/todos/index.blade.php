@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-xs">
                <a href="{{ route('todos.create') }}"><button type="button" class="btn btn-primary m-2">Ajouter une Todo</button></a>
            </div>

            <div class="col-xs">
                @if(Route::CurrentRouteName() === 'todos.index')
                    <a href="{{ route('todos.undone') }}"><button type="button" class="btn btn-warning m-2">Voir les Todos ouvertes</button></a>
            </div>

            <div class="col-xs">
                    <a href="{{ route('todos.done') }}"><button type="button" class="btn btn-success m-2">Voir les Todos terminées</button></a>
                @elseif(Route::CurrentRouteName() === 'todos.done')
                    <a href="{{ route('todos.index') }}"><button type="button" class="btn btn-dark m-2">Voir toutes les Todos</button></a>
            </div>

            <div class="col-xs">
                    <a href="{{ route('todos.undone') }}"><button type="button" class="btn btn-warning m-2">Voir les Todos ouvertes</button></a>
                @elseif(Route::CurrentRouteName() === 'todos.undone')
                    <a href="{{ route('todos.index') }}"><button type="button" class="btn btn-dark m-2">Voir toutes les Todos</button></a>
            </div>

            <div class="col-xs">
                    <a href="{{ route('todos.done') }}"><button type="button" class="btn btn-success m-2">Voir les Todos terminées</button></a>
                @endif
            </div>

        </div>
    </div>

    @foreach ($datas as $data)
        <div class="alert alert-{{ $data->done ? 'success' : 'warning' }}" role="alert">
            <div class="row">
                <div class="col-sm">
                    <p class="my-0">
                        <strong>
                            <span class="badge badge-dark">#{{ $data->id }}</span>
                        </strong>
                        <small>
                            créee {{ $data->created_at->from() }} par :

                            {{-- {{ Auth::user()->id == $data->user->id ? 'moi' : $data->user->name }} --}}

                            @if ($data->todoAffectedTo && $data->todoAffectedTo->id == Auth::user()->id)
                                Affectée à moi
                            @elseif ($data->todoAffectedTo)
                                {{ $data->todoAffectedTo ? ', affectéé à' . $data->todoAffectedTo->name : '' }}
                            @endif

                            @if ($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id == Auth::user()->id)
                                par moi-même :D
                            @elseif($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id !== Auth::user()->id)
                                {{ $data->todoAffectedBy->name }}
                            @endif

                            @if ($data->done)
                                <small>
                                    <p>
                                        Terminée
                                        {{ $data->updated_at->from() }} - Terminée en
                                        {{ $data->updated_at->diffForHumans($data->created_at, 1) }}
                                    </p>
                                </small>
                            @endif

                        </small>
                    </p>
                    <details>
                        <summary>
                            <strong>
                                {{ $data->name }}
                                @if($data->done)
                                <span class="badge badge-success">done</span>
                                @endif
                            </strong>
                        </summary>
                        <p>{{ $data->description }}</p>
                    </details>
                    
                </div>
                <div class="col-sm form-inline justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Affecter à
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($users as $user)
                                <a href="/todos/{{ $data->id }}/affectedTo/{{ $user->id }}" class="dropdown-item">{{ $user->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @if($data->done === 0)
                        <form action="{{ route('todos.makedone', $data->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success mx-1" style="min-width: 80px;">Done</button>
                        </form>
                    @else
                        <form action="{{ route('todos.makeundone', $data->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning mx-1" style="min-width: 80px;">Undone</button>
                        </form>
                    @endif

                    @can('edit', $data)
                        <a href="{{ route('todos.edit', $data->id) }}"><button type="button" class="btn btn-info mx-1">Editer</button></a>
                    @elsecannot('edit', $data) 
                        <a href="{{ route('todos.edit', $data->id) }}"><button type="button" class="btn btn-info mx-1" disabled>Editer</button></a>
                    @endcan
                    
                    @can('delete', $data)
                        <form action="{{ route('todos.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1">Effacer</button>
                        </form>
                    @elsecannot('delete', $data)
                        <form action="{{ route('todos.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-1" disabled>Effacer</button>
                        </form>
                    @endcan
                   

                </div>
            </div>
        </div>
    @endforeach

    {{ $datas->links('pagination::bootstrap-4') }}

@endsection
