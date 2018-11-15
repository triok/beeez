<?php

namespace App\Transformers;

use App\Models\Cv;
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
        $titles = [
            'App\Notifications\TeamUserNotification' => 'Вас приняли в команду',
            'App\Notifications\OrganizationNotification' => 'Новая организация',
            'App\Notifications\CvUserNotification' => 'Вы отклинулись на вакансию',
            'App\Notifications\CvAdminNotification' => 'Пользователь откликнулся на вакансию',
            'App\Notifications\CvApprovedNotification' => 'Работодатель принял Ваш отклик',
            'App\Notifications\CvDeclinedNotification' => 'Соискатель отклонил Ваше предложение',
        ];

        return isset($titles[$notification['type']]) ? $titles[$notification['type']] : '';
    }

    protected function getMessage($notification)
    {
        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
            $team = Team::find($notification['data']['team_id']);

            if($team) {
                return 'Вас приняли в команду "' . $team->name . '" на должность "' . $notification['data']['position'] . '".';
            } else {
                return 'Вас приняли в команду.';
            }
        }

        if ($notification['type'] == 'App\Notifications\OrganizationNotification') {
            $organization = Organization::find($notification['data']['id']);

            if ($organization) {
                return 'Создана новая организация <a href="' . route('organizations.show', $organization) . '">' . $notification['data']['name'] . '</a>';
            }
        }

        if ($notification['type'] == 'App\Notifications\CvUserNotification') {
            $cv = Cv::find($notification['data']['cv_id']);

            if ($cv) {
                return 'Вы отклинулись на вакансию "<a href="' . route('vacancies.show', $cv->vacancy) . '">' . $cv->vacancy->name . '</a>", ожидайте решение работодателя.';
            }
        }

        if ($notification['type'] == 'App\Notifications\CvAdminNotification') {
            $cv = Cv::find($notification['data']['cv_id']);

            if ($cv) {
                $str = 'Пользователь <a href="' . route('peoples.show', $cv->user) . '">' . $cv->user->name . '</a>
                    откликнулся на вакансию "<a href="' . route('vacancies.show', $cv->vacancy) . '">' . $cv->vacancy->name . '</a>".
                    <br><br>
                    
                    <b>ФИО:</b> ' . $cv->name . '<br>
                    <b>Email:</b> ' . $cv->email . '<br>
                    <b>Телефон:</b> ' . $cv->phone . '<br>
                    <b>Расскажите о себе:</b> ' . $cv->about . '<br>';

                if ($file = $cv->files()->first()) {
                    $str .= '<b>Файл для скачивания:</b> <a href="' . $file->link() . '">' . $file->title . '</a>';
                }

                return $str;
            }
        }

        if ($notification['type'] == 'App\Notifications\CvApprovedNotification') {
            $cv = Cv::find($notification['data']['cv_id']);

            if ($cv) {
                $str = 'Работодатель принял Ваш отклик и отправил Вам контактные данные:
                    <br><br>
                    <b>ФИО:</b> ' . $notification['data']['request']['name'] . '<br>
                    <b>Email:</b> ' . $notification['data']['request']['email'] . '<br>
                    <b>Телефон:</b> ' . $notification['data']['request']['phone'] . '<br>
                    <b>Комментарий:</b> ' . $notification['data']['request']['comment'] . '<br>';

                return $str;
            }
        }

        if ($notification['type'] == 'App\Notifications\CvDeclinedNotification') {
            $cv = Cv::find($notification['data']['cv_id']);

            if ($cv) {
                $str = 'Соискатель отклонил Ваше предложение по вакансии "<a href="' . route('vacancies.show', $cv->vacancy) . '">' . $cv->vacancy->name . '</a>"';

                if ($notification['data']['request']['comment']) {
                    $str .= '<br><b>Причина: </b> ' . $notification['data']['request']['comment'];
                }

                return $str;
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

            if ($organization) {
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

        if ($notification['type'] == 'App\Notifications\CvAdminNotification') {
            $cv = Cv::find($notification['data']['cv_id']);

            if ($cv && $cv->status == 'pending') {
                $actions[] = [
                    'route' => route('vacancies.cvs.approve', [$cv->vacancy, $cv]),
                    'title' => 'Принять',
                    'class' => 'btn-success',
                ];

                $actions[] = [
                    'route' => route('vacancies.cvs.reject', [$cv->vacancy, $cv]),
                    'title' => 'Отклонить',
                    'class' => 'btn-danger',
                ];
            }

            return $actions;
        }

//        $actions[] = [
//            'route' => route('notifications.destroy'),
//            'title' => 'Удалить',
//            'class' => 'btn-default'
//        ];

        return $actions;
    }
}