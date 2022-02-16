<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskList;

class TaskListItem extends Model
{
    protected $table = 'tasks_lists_items';

    protected $fillable = ['task_list_id', 'title', 'hash', 'done', 'created_at'];

    public function task()
    {
        return $this->belongsTo(TaskList::class);
    }
}
