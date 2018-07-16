<?php

namespace App\Http\Controllers;

use App\Events\CommentAddedEvent;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Jobs\Job;
use App\User;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreCommentRequest $request)
    {
        /** @var Comment $comment */
        Comment::query()->create([
            'commentable_id'   => $request->parent ?? $request->job,
            'commentable_type' => $request->parent ? Comment::class : Job::class,
            'body'             => $request->body,
            'author_id'        => auth()->id(),
            'author_type'      => User::class,
        ]);

        flash()->success('Comment added successfully!');

        return redirect()->back();
    }
}
