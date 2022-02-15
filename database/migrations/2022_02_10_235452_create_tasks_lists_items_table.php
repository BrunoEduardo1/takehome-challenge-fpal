<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksListsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_lists_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_list_id');
            $table->foreign('task_list_id')->references('id')->on('tasks_lists')->onDelete('cascade');
            $table->string('title');
            $table->string('hash');
            $table->unique('hash');
            $table->char('done', 1)->default('F');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_lists_items');
    }
}
