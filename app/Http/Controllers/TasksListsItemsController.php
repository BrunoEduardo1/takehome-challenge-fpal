<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskListItemCreateRequest;
use App\Http\Requests\TaskListItemUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\TaskListItem;

class TasksListsItemsController extends Controller
{
    public function create(TaskListItemCreateRequest $request)
    {
        $request = $request->validated();

        $data = [
            'task_list_id' => $request['task_list_id'],
            'title' => $request['title'],
            'hash' => md5(Hash::make($request['title'])),
        ];

        $errMsg = null;

        try {

            $createdItem = TaskListItem::create($data);

            if ($createdItem->exists) {
                return response()->json([
                    'success' => true,
                    'message' => trans('messages.success'),
                    'item_id' => $createdItem->id,
                ], 201);
            }

        } catch (\Illuminate\Database\QueryException $e) {

            $errMsg = $e->getMessage();

        }

        return response()->json([
            'success' => false,
            'message' => trans('messages.error'),
            'debug' => $errMsg,
        ], 200);

    }

    public function getById(int $id)
    {
        $item = TaskListItem::with('task')->find($id);

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
        $item = TaskListItem::with('task')->where('hash', $hash)->first();

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
        $items = TaskListItem::select(DB::raw('id, title, done, hash, created_at, DATE_FORMAT(created_at, "%d\/%m\/%Y") as formatted_created_at'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'items' => $items,
        ], 200);

    }

    public function update(TaskListItemUpdateRequest $request, string $hash)
    {
        $data = $request->validated();

        $updatedTaskListItem = (int) TaskListItem::where('hash', $hash)->update($data);

        if ($updatedTaskListItem) {
            return response()->json([
                'success' => true,
                'message' => trans('messages.success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('messages.error'),
        ], 422);
    }

    public function delete(string $hash)
    {
        $deletedTaskListItem = (int) TaskListItem::where('hash', $hash)->delete();

        if ($deletedTaskListItem) {
            return response()->json([
                'success' => true,
                'message' => trans('messages.success'),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => trans('messages.error'),
        ], 404);

    }
}
