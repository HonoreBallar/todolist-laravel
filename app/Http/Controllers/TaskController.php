<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        // Filter tasks based on the 'filter' query parameter
        $filter = request('filter');
        $tasks = match ($filter) {
            'done' => Task::where('completed', true)->get(),
            'todo' => Task::where('completed', false)->get(),
            default => Task::all(),
        };

        return view('todolist', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create([
            'title' => $request->title,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tâche ajoutée avec succès !');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('edit-task', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->title;
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Tâche mise à jour !');
    }

    

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->save();
        return redirect()->route('tasks.index');
    }


    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->route('tasks.index')->with('success', 'Tâche supprimée avec succès !');
    }
}
