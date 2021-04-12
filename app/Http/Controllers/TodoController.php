<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use App\Notifications\TodoAffected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public $users;

    public function __construct()
    {
        $this->users = User::getAllUsers();
    }

    public function affectedto(Todo $todo, User $user)
    {
        $todo->affectedTo_id = $user->id;
        $todo->affectedBy_id = Auth::user()->id;
        $todo->update();

        $user->notify(new TodoAffected($todo));

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $datas = Todo::where('affectedTo_id', $userId)->orderBy('id', 'desc')->paginate(10);

        $users = $this->users;

        return view('todos.index', compact('datas', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new Todo();

        $todo->creator_id = Auth::user()->id;
        $todo->affectedTo_id = Auth::user()->id;
        $todo->name = $request->name;
        $todo->description = $request->description;

        $todo->save();

        notify()->success("La Todo <span class='badge badge-dark'>#$todo->id</span> vient d'être créee.");

        return redirect()->route('todos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        if(!isset($request->done))
        {
            $request['done'] = 0;
        }
        $todo->update($request->all());
        notify()->success("La Todo <span class='badge badge-dark'>#$todo->id</span> a bien été mise à jour.");

        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        notify()->error("La Todo <span class='badge badge-dark'>#$todo->id</span> a bien été supprimée.");

        return back();
    }

    /**
     *
     * display list of done's todos
     */
    public function done()
    {
        $datas = Todo::where('done', 1)->paginate(10);
        $users = $this->users;

        return view('todos.index', compact('datas', 'users'));

    }

    /**
     * display list of undone's todos
     */
    public function undone()
    {
        $datas = Todo::where('done', 0)->paginate(10);
        $users = $this->users;

        return view('todos.index', compact('datas', 'users'));
    }

    /**
     * Set undone's todo as done's todo
     * @param Todo $todo
     * @return void
     */
    public function makedone(Todo $todo)
    {
        $todo->done = 1;
        $todo->update();

        notify()->success("La Todo <span class='badge badge-dark'>#$todo->id</span> a bien été terminée.");

        return back();
    }

    /**
     * Set done's todo as undone's todo
     * @param Todo $todo
     * @return void
     */
    public function makeundone(Todo $todo)
    {
        $todo->done = 0;
        $todo->update();

        notify()->success("La Todo <span class='badge badge-dark'>#$todo->id</span> est à nouveau ouverte.");

        return back();
    }

}
