<?php
    use App\Http\Controllers\Api\TaskController;
    use Illuminate\Support\Facades\Route;

    Route::prefix('tasks')->controller(TaskController::class)->name('api.tasks.')->group(function () {
        Route::get('/', 'index')->name('index'); // Listar todas as tarefas
        Route::post('/', 'store')->name('store'); // Criar uma tarefa
        Route::put('/{id}', 'update')->name('update'); // Editar tarefa
        Route::delete('/{id}', 'destroy')->name('destroy'); // Deletar tarefa
        Route::patch('/{id}/mark', 'mark')->name('mark'); // Marcar como concluída/pendente
    });
