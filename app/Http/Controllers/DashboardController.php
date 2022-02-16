<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TaskList;
use App\Models\TaskListItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getData()
    {
        $data = [
            'users_count' => User::count(),
            'tasks_lists_count' => TaskList::count(),
            'pending_tasks_count' => TaskListItem::where('done', 'F')->count(),
            'finished_tasks_count' => TaskListItem::where('done', 'F')->count(),
            'tasks_lists' => [],
        ];

        $data['tasks_lists'] = TaskList::select(DB::raw('id, title, description, hash, created_at,
            DATE_FORMAT(created_at, "%d\/%m\/%Y") as formatted_created_at'))
            ->take(2)
            ->where('created_by', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->with('items')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'ok',
            'items' => $data,
        ], 200);

    }
}
