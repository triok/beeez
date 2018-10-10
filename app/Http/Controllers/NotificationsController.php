<?php

namespace App\Http\Controllers;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    protected $transformer;

    function __construct()
    {
        $this->middleware('auth');

        $this->transformer = new NotificationTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        $notifications = auth()->user()->notifications;

        $notifications = $this->transformer->transformCollection(
            $notifications->toArray()
        );

        return view('notifications.index', compact('notifications'));
    }
}
