<?php

namespace App\Http\Controllers\Traits;

use App\Models\Comment;

trait Commentable
{
    public function commentableModel()
    {
        return Comment::class;
    }
    public function comments()
    {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }
    public function createComment($data, $author)
    {
        $commentableModel = $this->commentableModel();
        $comment = (new $commentableModel())->createComment($this, $data, $author);
        return $comment;
    }
    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateComment($id, $data)
    {
        $commentableModel = $this->commentableModel();
        $comment = (new $commentableModel())->updateComment($id, $data);
        return $comment;
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteComment($id)
    {
        $commentableModel = $this->commentableModel();
        return (bool) (new $commentableModel())->deleteComment($id);
    }
    /**
     * @return mixed
     */
    public function commentCount()
    {
        return $this->comments->count();
    }
}