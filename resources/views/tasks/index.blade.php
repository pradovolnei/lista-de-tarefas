<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('To-Do List (API + Blade)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="todoList()">

                    <form @submit.prevent="createTask" class="mb-8 p-4 border rounded-lg bg-gray-50">
                        <h3 class="text-lg font-bold mb-4">Criar Nova Tarefa</h3>
                        <input x-model="newTask.title" placeholder="Título (obrigatório)" class="block w-full rounded-md border-gray-300 mb-2 p-2">
                        <textarea x-model="newTask.description" placeholder="Descrição (opcional)" class="block w-full rounded-md border-gray-300 mb-4 p-2"></textarea>
                        <x-primary-button>Adicionar Tarefa</x-primary-button>
                        <p x-text="errorMessage" class="text-red-500 mt-2"></p>
                    </form>

                    <h3 class="text-lg font-bold mb-4">Minhas Tarefas</h3>
                    <div x-show="tasks.length === 0" class="text-gray-500">Nenhuma tarefa encontrada.</div>

                    <ul class="space-y-3">
                        <template x-for="task in tasks" :key="task.id">
                            <li :class="{'bg-green-100 opacity-60 line-through': task.is_completed, 'bg-white': !task.is_completed}" class="p-4 rounded-lg shadow flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" :checked="task.is_completed" @change="toggleCompletion(task.id, $event.target.checked)" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">

                                        <div x-show="!task.isEditing">
                                            <span class="font-semibold text-lg" x-text="task.title"></span>
                                        </div>
                                        <div x-show="task.isEditing" class="flex-1">
                                            <input x-model="task.title_edit" type="text" @keydown.enter.prevent="saveEdit(task)" class="w-full rounded-md border-gray-300 p-1">
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1" x-text="task.description"></p>
                                    <p class="text-xs text-gray-400 mt-1">Criada em: <span x-text="new Date(task.created_at).toLocaleDateString()"></span></p>
                                </div>

                                <div class="space-x-2 flex items-center ml-4">
                                    <button x-show="!task.isEditing" @click="startEdit(task)" class="text-blue-500 hover:text-blue-700">Editar</button>
                                    <button x-show="task.isEditing" @click="saveEdit(task)" class="text-green-500 hover:text-green-700">Salvar</button>
                                    <button x-show="task.isEditing" @click="cancelEdit(task)" class="text-gray-500 hover:text-gray-700">Cancelar</button>
                                    <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700">Excluir</button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function todoList() {
            return {
                tasks: [],
                newTask: { title: '', description: '' },
                errorMessage: '',

                init() {
                    // Inicializa o Axios se não estiver no escopo global
                    if (typeof axios === 'undefined') {
                        console.error('Axios não carregado. Certifique-se de que ele está incluído no seu bundle JS.');
                        return;
                    }
                    this.fetchTasks();
                },

                // Lógica de Obtenção (Listar todas as tarefas)
                fetchTasks() {
                    axios.get('/api/tasks')
                        .then(response => {
                            // Adiciona propriedades reativas para edição
                            this.tasks = response.data.map(task => ({
                                ...task,
                                isEditing: false,
                                title_edit: task.title
                            }));
                        })
                        .catch(error => {
                            this.errorMessage = 'Erro ao carregar tarefas: ' + (error.response?.data?.message || error.message);
                        });
                },

                // Lógica de Criação
                createTask() {
                    this.errorMessage = '';
                    if (!this.newTask.title) {
                        this.errorMessage = 'O título da tarefa é obrigatório.';
                        return;
                    }
                    axios.post('/api/tasks', this.newTask)
                        .then(response => {
                            // Adiciona a nova tarefa ao topo da lista, com status de edição desativado
                            this.tasks.unshift({
                                ...response.data,
                                isEditing: false,
                                title_edit: response.data.title
                            });
                            this.newTask = { title: '', description: '' }; // Limpa o formulário
                        })
                        .catch(error => {
                            const validationErrors = error.response.data.errors;
                            this.errorMessage = validationErrors ? validationErrors.title?.[0] : (error.response?.data?.message || 'Erro ao criar a tarefa.');
                        });
                },

                // Lógica de Marcar/Desmarcar (Atualizar status)
                toggleCompletion(taskId, isCompleted) {
                    const taskIndex = this.tasks.findIndex(t => t.id === taskId);
                    if (taskIndex === -1) return;

                    // Atualiza o estado local para uma resposta imediata (otimista)
                    this.tasks[taskIndex].is_completed = isCompleted;

                    axios.patch(`/api/tasks/${taskId}/mark`, { is_completed: isCompleted })
                        .catch(error => {
                            // Reverte o estado local em caso de falha
                            this.tasks[taskIndex].is_completed = !isCompleted;
                            alert('Erro ao atualizar status: ' + (error.response?.data?.message || error.message));
                        });
                },

                // Lógica de Edição (Inicializar)
                startEdit(task) {
                    task.isEditing = true;
                },

                // Lógica de Edição (Cancelar)
                cancelEdit(task) {
                    task.title_edit = task.title; // Restaura o valor original
                    task.isEditing = false;
                },

                // Lógica de Edição (Salvar - Atualizar nome)
                saveEdit(task) {
                    if (task.title_edit === task.title) {
                        task.isEditing = false;
                        return;
                    }
                    if (!task.title_edit) {
                        alert('O título não pode ser vazio.');
                        return;
                    }

                    axios.put(`/api/tasks/${task.id}`, { title: task.title_edit, description: task.description, is_completed: task.is_completed })
                        .then(response => {
                            task.title = response.data.title;
                            task.description = response.data.description;
                            task.isEditing = false;
                        })
                        .catch(error => {
                            task.title_edit = task.title; // Restaura o valor original em caso de erro
                            const validationErrors = error.response.data.errors;
                            alert('Erro ao salvar edição: ' + (validationErrors ? validationErrors.title?.[0] : (error.response?.data?.message || error.message)));
                        });
                },

                // Lógica de Exclusão
                deleteTask(taskId) {
                    if (!confirm('Tem certeza que deseja excluir esta tarefa?')) return;

                    axios.delete(`/api/tasks/${taskId}`)
                        .then(() => {
                            this.tasks = this.tasks.filter(t => t.id !== taskId);
                        })
                        .catch(error => {
                            alert('Erro ao excluir tarefa: ' + (error.response?.data?.message || error.message));
                        });
                }
            }
        }
    </script>
</x-app-layout>
