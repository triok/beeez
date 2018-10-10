<?php

namespace App\Transformers;

use App\Models\Team;

class NotificationTransformer extends Transformer
{
    /**
     * @param $notification
     * @return array
     */
    public function transform($notification)
    {
        $team = Team::find($notification['data']['team_id']);

        return [
            "title" => $this->getTitle($notification),
            "date" => $notification['created_at'],
            "message" => $this->getMessage($notification, $team),
            "actions" => $this->getActions($notification, $team)
        ];
    }

    protected function getTitle($notification)
    {
        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
            return 'Вас приняли в команду';
        }

        return '';
    }

    protected function getMessage($notification, $team)
    {
        if ($notification['type'] == 'App\Notifications\TeamUserNotification' && $team) {
            return 'Вас приняли в команду "' . $team->name . '" на должность "' . $notification['data']['position'] . '".';
        }

        return '';
    }

    protected function getActions($notification, $team)
    {
        $actions = [];

        if ($notification['type'] == 'App\Notifications\TeamUserNotification' && $team) {
            $actions[] = [
                'route' => route('notifications.approve', $notification['id']),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.reject', $notification['id']),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];
        }

        $actions[] = [
            'route' => route('notifications.destroy', $notification['id']),
            'title' => 'Удалить',
            'class' => 'btn-default'
        ];

        return $actions;
    }
}