<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Exception;

class TaskController extends Controller
{
    protected $taskService;

    // Injeção de Dependência do Service (D do SOLID)
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * GET /api/tasks - Listar todas as tarefas
     */
    public function index(): JsonResponse
    {
        try {
            $tasks = $this->taskService->getAllTasks();
            return response()->json($tasks);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * POST /api/tasks - Criar uma tarefa
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return response()->json($task, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * PUT/PATCH /api/tasks/{task} - Atualizar tarefa (editar nome)
     */
    public function update(TaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->updateTask($id, $request->validated());
            return response()->json($task);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * DELETE /api/tasks/{task} - Deletar tarefa
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);
            return response()->json(null, 204);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * PATCH /api/tasks/{task}/mark - Marcar como concluída/pendente
     */
    public function mark(TaskRequest $request, int $id): JsonResponse
    {
        try {
            $isCompleted = $request->validated('is_completed');
            $task = $this->taskService->markTask($id, $isCompleted);
            return response()->json($task);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }
}
