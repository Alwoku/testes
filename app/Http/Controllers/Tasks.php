<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;

class Tasks extends Controller
{
    /**
     * Возвращает список всех задач
     */
    public function index()
    {
        // забираем все существующие задачи
        $tasks = Task::all();

        return response()->json([
            "tasks" => $tasks
        ]);
    }

    /**
     * Создание новой задачи
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'string',
            'status' => 'required|max:255',
        ]);

        try {

            // создаем объект класса
            $task = new Task();

            // заполняем поля
            $task->fill($request->all());

            // сохраняем 
            $task->save();

            return response()->json([
                "task" => $task,
                "message" => "Сохранено"
            ], 201);
        
        } catch (Exception $e){

            // обработка ошибки на случай если что то пойдет не так
            return response()->json([
                "message" => "Произошла ошибка во время создания задачи"
            ], 500);
        }
    }

    /**
     * Возвращает задачу для просмотра
     */
    public function show(Task $task)
    {
        return response()->json([
            "task" => $task
        ], 200);
    }

    /**
     * Редактирование существующей задачи
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'string',
            'status' => 'required|max:255',
        ]);

        try {

            // заполняем поля
            $task->fill($request->all());

            // сохраняем задачу
            $task->save();

            return response()->json([
                "task" => $task,
                "message" => "Сохранено"
            ], 200);
        } catch (Exception $e){

            // обработка ошибки если что то пойдет не так
            return response()->json([
                "message" => "Произошла ошибка во время обновления задачи"
            ], 500);
        }
        
    }

    /**
     * Удаление задачи
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            "message" => "Задача удалена"
        ]);
    }
}
