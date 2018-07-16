<?php

namespace App\Models;

use App\Http\Controllers\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Commentable;

    protected $guarded = ['id'];
    protected $with = ["childrens","author"];

    /**
     * Return the model name
     * @return string
     */
    public function commentableModel()
    {
        return Comment::class;
    }

    /**
     * Return childrens
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function childrens()
    {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->morphTo('author');
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $author
     *
     * @return static
     */

    public function createComment(Model $commentable, $data, Model $author)
    {
        return $commentable->comments()->create(array_merge($data, ['author_id' => $author->getAuthIdentifier(), 'author_type' => get_class($author),]));
    }
    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateComment($id, $data) {
        $obj = static::find($id);

        if(auth()->check() && ($obj->author->id != auth()->user()->id)) {
            // The current logged user was not the author !
            return false;
        }
        return (bool)$obj->update($data);
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteComment($id) {
        $obj = static::find($id);
        if(auth()->check() && ($obj->author->id != auth()->user()->id)) {
            // The current logged user was not the author!
            return false;
        }
        return (bool)$obj->delete();
    }


}
