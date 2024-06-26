<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Task::query();

            // Filtrar por status si está presente en la solicitud
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filtrar por due_date si está presente en la solicitud
            if ($request->has('due_date')) {
                $query->whereDate('due_date', $request->input('due_date'));
            }

            // Obtener las tareas filtradas
            $tasks = $query->get();

            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las tareas.'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:pending,in_progress,completed',
                'due_date' => 'required|date',
            ]);

            $task = Task::create($request->all());
            return response()->json($task, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la tarea.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tarea no encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo recuperar la tarea.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => 'string|max:255',
                'description' => 'string',
                'status' => 'in:pending,in_progress,completed',
                'due_date' => 'date',
            ]);

            $task = Task::findOrFail($id);
            $task->update($request->all());

            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tarea no encontrada.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la tarea.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(['message' => 'Tarea eliminada correctamente.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tarea no encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la tarea.'], 500);
        }
    }
}
