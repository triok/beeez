<?php

namespace App\Transformers;

class TaskTransformer extends Transformer
{
    /**
     * @param $task
     * @return array
     */
    public function transform($task)
    {
        return [
            "id" => $task->id,
            "name" => $task->name,
            "comment" => $task->comment,
            "completed" => (boolean)$task->completed,
            "do_date" => $task->do_date ? date('d.m.Y', strtotime($task->do_date)) : null,
        ];
    }
}