<?php

namespace App\Transformers;

use App\Models\Organization;
use App\Models\Team;

class NotificationTransformer extends Transformer
{
    /**
     * @param $notification
     * @return array
     */
    public function transform($notification)
    {
        return [
            "id" => $notification['id'],
            "title" => $this->getTitle($notification),
            "date" => $notification['created_at'],
            "is_archived" => $notification['is_archived'],
            "message" => $this->getMessage($notification),
            "actions" => $this->getActions($notification)
        ];
    }

    protected function getTitle($notification)
    {
        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
            return 'Вас приняли в команду';
        }

        if ($notification['type'] == 'App\Notifications\OrganizationNotification') {
            return 'Новая организация';
        }

        return '';
    }

    protected function getMessage($notification)
    {
        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
            $team = Team::find($notification['data']['team_id']);

            return 'Вас приняли в команду "' . $team->name . '" на должность "' . $notification['data']['position'] . '".';
        }

        if ($notification['type'] == 'App\Notifications\OrganizationNotification') {
            $organization = Organization::find($notification['data']['id']);

            if($organization) {
                return 'Создана новая организация <a href="' . route('organizations.show', $organization) . '">' . $notification['data']['name'] . '</a>';
            }
        }

        return '';
    }

    protected function getActions($notification)
    {
        $actions = [];

        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
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

            return $actions;
        }

        if ($notification['type'] == 'App\Notifications\OrganizationNotification') {
            $organization = Organization::find($notification['data']['id']);

            if($organization) {
                $actions[] = [
                    'route' => route('organizations.approve', $organization),
                    'title' => 'Approve',
                    'class' => 'btn-success',
                ];

                $actions[] = [
                    'route' => route('organizations.reject', $organization),
                    'title' => 'Reject',
                    'class' => 'btn-danger',
                ];
            }

            return $actions;
        }

        $actions[] = [
            'route' => route('notifications.destroy'),
            'title' => 'Удалить',
            'class' => 'btn-default'
        ];

        return $actions;
    }
}