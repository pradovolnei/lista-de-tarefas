<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TaskViewController extends Controller
{
    /**
     * Retorna a view principal do To-Do List.
     */
    public function index(): View
    {
        return view('tasks.index');
    }
}
