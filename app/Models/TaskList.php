<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskListItem;

class TaskList extends Model
{
    protected $table = 'tasks_lists';

    protected $fillable = ['title', 'description', 'hash', 'created_by'];

    /**
     * List items
     */
    public function items()
    {
        return $this->hasMany(TaskListItem::class);
    }
}
