<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskListCreateRequest;
use App\Http\Requests\TaskListUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\TaskList;

class TasksListsController extends Controller
{
    public function create(TaskListCreateRequest $request)
    {
        $request = $request->validated();

        $data = [
            'title' => $request['title'],
            'description' => !empty($request['description']) ? $request['description'] : null,
            'created_by' => auth()->user()->id,
            'hash' => md5(Hash::make($request['title'])),
        ];

        $errMsg = null;

        try {

            $createdItem = TaskList::create($data);

            if ($createdItem->exists) {
                return response()->json([
                    'success' => true,
                    'message' => trans('success'),
                    'item_id' => $createdItem->id,
                ], 201);
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $errMsg = $e->getMessage();

        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
            // 'debug' => $errMsg,
        ], 200);

    }

    public function getById(int $id)
    {
        $item = TaskList::with('items')->find($id);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'ok',
                'item' => $item
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('not_found'),
        ], 404);
    }

    public function getByHash(string $hash)
    {
        $item = TaskList::with('items')->where('hash', $hash)->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'ok',
                'item' => $item
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('not_found'),
        ], 404);
    }

    public function all()
    {
        $items = TaskList::select(DB::raw('id, title, description, hash, created_at, DATE_FORMAT(created_at, "%d\/%m\/%Y") as formatted_created_at'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'items' => $items,
        ], 200);

    }

    public function update(TaskListUpdateRequest $request, string $hash)
    {
        $data = $request->validated();

        $updatedTaskList = (int) TaskList::where('hash', $hash)->update($data);

        if ($updatedTaskList) {
            return response()->json([
                'success' => true,
                'message' => trans('success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
        ], 422);
    }

    public function delete(string $hash)
    {
        $deletedTaskList = (int) TaskList::where('hash', $hash)->delete();

        if ($deletedTaskList) {
            return response()->json([
                'success' => true,
                'message' => trans('success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('error'),
        ], 404);

    }
}
