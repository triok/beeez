<?php

namespace App\Listeners;

use App\Events\SocialEvent;
use App\Mail\SocialConfirmation;
use App\Models\Social;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SocialEventListener
{
    public function handle(SocialEvent $event)
    {
        $request = $event->getRequest();
        /** @var User $user */
        $user = auth()->user();


        if ($request->action == 'save') {

            /** @var Builder $query */
            $query = $user->socials()->where('slug', $request->attr);

            if(!$query->exists()) {

                $user->socials()->attach(Social::query()->where('slug', $request->attr)->first()->id, [
                    'link'      => $request->value,
                    'status'    => config('tags.statuses.init.value')
                ]);
            } else {

                $user->socials()->updateExistingPivot($query->first()->id, ['link' => $request->value]);
            }

            $event->setResponse('The field "' . $request->attr . '" was successfully updated.');
        }

        if ($request->action == 'verified') {

            $event->setResponse($this->send($request));
        }
        if ($request->action == 'confirmed') {
            $userConf = User::find($request->user);
            $social = $userConf->socials()->where('slug', $request->attr)->first();

            $userConf->socials()->updateExistingPivot($social->id, ['status' => config('tags.statuses.confirmed.value')]);

            $event->setResponse('The field "' . $social->slug . '" was successfully confirmed.');
        }
    }

    private function send(Request $request)
    {
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SocialConfirmation($request));

        return 'Link successfully sent for confirmation';
    }
}
