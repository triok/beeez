<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Jobs\Job;
use App\Observers\CommentObserver;
use App\Observers\JobObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SocialEvent' => [
            'App\Listeners\SocialEventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Job::observe(new JobObserver);
        Comment::observe(new CommentObserver);
    }
}
