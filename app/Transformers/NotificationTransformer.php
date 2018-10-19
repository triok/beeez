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
            "id" => $notification['id'],
            "title" => $this->getTitle($notification),
            "date" => $notification['created_at'],
            "is_archived" => $notification['is_archived'],
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
                'route' => route('notifications.approve'),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.reject'),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];
        } else {
            $actions[] = [
                'route' => route('notifications.destroy'),
                'title' => 'Удалить',
                'class' => 'btn-default'
            ];
        }

        return $actions;
    }
}