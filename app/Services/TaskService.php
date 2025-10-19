<?php

namespace App\Services;

use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Lista todas as tarefas.
     * @return Collection|array
     */
    public function getAllTasks(): Collection|array
    {
        try {
            return Task::all();
        } catch (Exception $e) {
            // Logar o erro para debug (opcional, mas boa prática)
            // \Log::error("Erro ao listar tarefas: " . $e->getMessage());
            throw new Exception("Falha ao listar tarefas. Tente novamente mais tarde.");
        }
    }

    /**
     * Cria uma nova tarefa.
     * @param array $data
     * @return Task
     * @throws Exception
     */
    public function createTask(array $data): Task
    {
        try {
            return Task::create($data);
        } catch (Exception $e) {
            // \Log::error("Erro ao criar tarefa: " . $e->getMessage());
            throw new Exception("Falha ao criar a tarefa.");
        }
    }

    /**
     * Encontra uma tarefa pelo ID.
     * @param int $id
     * @return Task
     * @throws Exception
     */
    public function findTask(int $id): Task
    {
        $task = Task::find($id);
        if (!$task) {
            throw new Exception("Tarefa não encontrada.", 404);
        }
        return $task;
    }

    /**
     * Atualiza uma tarefa existente.
     * @param int $id
     * @param array $data
     * @return Task
     * @throws Exception
     */
    public function updateTask(int $id, array $data): Task
    {
        try {
            $task = $this->findTask($id);
            $task->update($data);
            return $task;
        } catch (Exception $e) {
            if ($e->getCode() === 404) {
                 throw $e; // Re-lança a exceção "Tarefa não encontrada"
            }
            // \Log::error("Erro ao atualizar tarefa #{$id}: " . $e->getMessage());
            throw new Exception("Falha ao atualizar a tarefa.");
        }
    }

    /**
     * Marca uma tarefa como concluída ou pendente.
     * @param int $id
     * @param bool $isCompleted
     * @return Task
     * @throws Exception
     */
    public function markTask(int $id, bool $isCompleted): Task
    {
        return $this->updateTask($id, ['is_completed' => $isCompleted]);
    }


    /**
     * Exclui uma tarefa.
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteTask(int $id): bool
    {
        try {
            $task = $this->findTask($id);
            return $task->delete();
        } catch (Exception $e) {
             if ($e->getCode() === 404) {
                 throw $e;
            }
            // \Log::error("Erro ao excluir tarefa #{$id}: " . $e->getMessage());
            throw new Exception("Falha ao excluir a tarefa.");
        }
    }
}
