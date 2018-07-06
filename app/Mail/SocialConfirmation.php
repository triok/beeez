<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class SocialConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Request $request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        $social = auth()->user()->socials()->where('slug', $this->request->attr)->first();

        auth()->user()->socials()->updateExistingPivot($social->id, ['status' => config('tags.statuses.verified.value')]);

        return $this->from(auth()->user()->email)
            ->markdown('emails.user-confirm-social', ['request' => $this->request]);
    }
}
