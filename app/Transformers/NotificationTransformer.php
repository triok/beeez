<?php

namespace App\Transformers;

use App\Models\Cv;
use App\Models\Jobs\Job;
use App\Models\Organization;
use App\Models\Structure;
use App\Models\Team;
use App\Models\UserExperience;
use App\User;
use Illuminate\Support\Facades\Storage;

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
            'App\Notifications\OrganizationUserNotification' => 'Вас приняли в организацию',
            'App\Notifications\OrganizationUserApproveNotification' => 'Пользователь вступил в организацию',
            'App\Notifications\OrganizationUserRejectNotification' => 'Пользователь отклонил Ваше предложение',
            'App\Notifications\StructureUserNotification' => 'Вас приняли в отдел',
            'App\Notifications\StructureUserApproveNotification' => 'Пользователь вступил в организацию',
            'App\Notifications\StructureUserRejectNotification' => 'Пользователь отклонил Ваше предложение',
            'App\Notifications\OrganizationNotification' => 'Новая организация',
            'App\Notifications\CvUserNotification' => 'Вы отклинулись на вакансию',
            'App\Notifications\CvAdminNotification' => 'Пользователь откликнулся на вакансию',
            'App\Notifications\CvApprovedNotification' => 'Работодатель принял Ваш отклик',
            'App\Notifications\CvDeclinedNotification' => 'Соискатель отклонил Ваше предложение',
            'App\Notifications\JobReportNotification' => 'Запрос на отчет',
            'App\Notifications\JobOwnerReportNotification' => 'Новый отчет',
            'App\Notifications\AccountApproveNotification' => 'Подтверждение аккаунта',
            'App\Notifications\AccountIsApprovedNotification' => 'Подтверждение аккаунта',
            'App\Notifications\AccountIsNotApprovedNotification' => 'Подтверждение аккаунта',
            'App\Notifications\ExperienceApproveNotification' => 'Подтверждение стажа',
            'App\Notifications\ExperienceIsApprovedNotification' => 'Подтверждение стажа',
            'App\Notifications\ExperienceIsNotApprovedNotification' => 'Подтверждение стажа',
            'App\Notifications\ProposalAppliedNotification' => 'Вас выбрали в качестве исполнителя',
            'App\Notifications\JobDecliningNotification' => 'Отмена сделки',
            'App\Notifications\JobDeclinedNotification' => 'Отмена сделки',
            'App\Notifications\JobNotDeclinedNotification' => 'Отмена сделки',
        ];

        return isset($titles[$notification['type']]) ? $titles[$notification['type']] : '';
    }

    protected function getMessage($notification)
    {
        if ($notification['type'] == 'App\Notifications\JobDeclinedNotification') {
            $job = Job::find($notification['data']['job_id']);

            $message = '<p>Ваш запрос по заданию <a href="' . route('jobs.show', $job->id) . '">"' . $job->name . '"</a> подтвержден.<p></p>';
            $message .= '<p>Деньги будут отправлены в ближайшее время.</p>';

            return $message;
        }

        if ($notification['type'] == 'App\Notifications\JobNotDeclinedNotification') {
            $job = Job::find($notification['data']['job_id']);

            $message = '<p>Ваш запрос по заданию <a href="' . route('jobs.show', $job->id) . '">"' . $job->name . '"</a> отклонен.<p></p>';
            $message .= '<p>Деньги отправлены исполнителю.</p>';

            return $message;
        }

        if ($notification['type'] == 'App\Notifications\JobDecliningNotification') {
            $job = Job::find($notification['data']['job_id']);

            $message = '<p>По заданию <a href="' . route('jobs.show', $job->id) . '">"' . $job->name . '"</a> отправлен запрос на отмену сделки и возврат денег заказчику.<p></p>';
            $message .= '<p>Комментарий к запросу:</p>';
            $message .= '<p>' . $notification['data']['message'] . '</p>';

            return $message;
        }

        if ($notification['type'] == 'App\Notifications\ProposalAppliedNotification') {
            $job = Job::find($notification['data']['job_id']);

            return 'Вас выбрали в качестве исполнителя по заданию "' . $job->name . '".';
        }

        if ($notification['type'] == 'App\Notifications\TeamUserNotification') {
            $team = Team::find($notification['data']['team_id']);

            if($team) {
                return 'Вас приняли в команду "' . $team->name . '" на должность "' . $notification['data']['position'] . '".';
            } else {
                return 'Вас приняли в команду.';
            }
        }

        if ($notification['type'] == 'App\Notifications\AccountApproveNotification') {
            $user = User::find($notification['data']['user_id']);

            if($user) {
                $message = 'Подтверждение аккаунта от пользователя "' . $user->username . '"';
            } else {
                return 'Подтверждение аккаунта.';
            }

            if(isset($notification['data']['files'])) {
                $message .= '<ul>';
                foreach ($notification['data']['files'] as $file) {
                    $message .= '<li><a href="' . Storage::url($file['file']) . '">' . $file['original_name'] . '</a></li>';
                }
                $message .= '</ul>';
            }

            return $message;
        }

        if ($notification['type'] == 'App\Notifications\AccountIsApprovedNotification') {
            return 'Верификация аккаунта пройдена успешно. Статус Вашего аккаунта - подтвержден.';
        }

        if ($notification['type'] == 'App\Notifications\AccountIsNotApprovedNotification') {
            return 'Верификация Вашего аккаунта не пройдена.';
        }

        if ($notification['type'] == 'App\Notifications\ExperienceApproveNotification') {
            $user = User::find($notification['data']['user_id']);
            $experience = UserExperience::find($notification['data']['experience_id']);

            if($user) {
                $message = '<p>Подтверждение стажа от пользователя: ' . $user->username . '</p>';
            } else {
                return '<p>Подтверждение стажа.</p>';
            }

            $message .= '<p>Название компании: ' . $experience->name . '</p>';
            $message .= '<p>Должность: ' . $experience->position . '</p>';
            $message .= '<p>Дата зачисления: ' . $experience->hiring_at . '</p>';
            $message .= '<p>Дата увольнения: ' . $experience->dismissal_at . '</p>';

            $message .= '<p>Файлы для подтверждения:</p>';
            if(isset($notification['data']['files'])) {
                $message .= '<ul>';
                foreach ($notification['data']['files'] as $file) {
                    $message .= '<li><a href="' . Storage::url($file['file']) . '">' . $file['original_name'] . '</a></li>';
                }
                $message .= '</ul>';
            }

            return $message;
        }

        if ($notification['type'] == 'App\Notifications\ExperienceIsApprovedNotification') {
            return 'Подтверждение вашего стажа пройдено успешно.';
        }

        if ($notification['type'] == 'App\Notifications\ExperienceIsNotApprovedNotification') {
            return 'Подтверждение вашего стажа не пройдено.';
        }

        if ($notification['type'] == 'App\Notifications\OrganizationUserNotification') {
            if(isset($notification['data']['organization_id'])) {
                $organization = Organization::find($notification['data']['organization_id']);

                if ($organization) {
                    return 'Вас приняли в организацию "' . $organization->name . '" на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Вас приняли в организацию.';
                }
            }
        }

        if ($notification['type'] == 'App\Notifications\OrganizationUserApproveNotification') {
            if(isset($notification['data']['organization_id'])) {
                $organization = Organization::find($notification['data']['organization_id']);
                $user = User::find($notification['data']['user_id']);

                if ($organization) {
                    return 'Пользователь "' . $user->name . '", вступил в "' . $organization->name . '" на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Пользователь вступил в организацию.';
                }
            }
        }

        if ($notification['type'] == 'App\Notifications\OrganizationUserRejectNotification') {
            if(isset($notification['data']['organization_id'])) {
                $organization = Organization::find($notification['data']['organization_id']);
                $user = User::find($notification['data']['user_id']);

                if ($organization) {
                    return 'Пользователь "' . $user->name . '" отклонил Ваше предложение вступить в организацию "' . $organization->name . '" на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Пользователь отклонил Ваше предложение.';
                }
            }
        }

        if ($notification['type'] == 'App\Notifications\StructureUserNotification') {
            if(isset($notification['data']['structure_id'])) {
                $structure = Structure::find($notification['data']['structure_id']);

                if ($structure) {
                    return 'Вас приняли в организацию "' . $structure->organization->name . '"->"' . $structure->name . '"  на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Вас приняли в организацию.';
                }
            }
        }

        if ($notification['type'] == 'App\Notifications\StructureUserApproveNotification') {
            if(isset($notification['data']['structure_id'])) {
                $structure = Structure::find($notification['data']['structure_id']);
                $user = User::find($notification['data']['user_id']);

                if ($structure) {
                    return 'Пользователь "' . $user->name . '", вступил в "' . $structure->organization->name . '"->"' . $structure->name . '" на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Пользователь вступил в организацию.';
                }
            }
        }

        if ($notification['type'] == 'App\Notifications\StructureUserRejectNotification') {
            if(isset($notification['data']['structure_id'])) {
                $structure = Structure::find($notification['data']['structure_id']);
                $user = User::find($notification['data']['user_id']);

                if ($structure) {
                    return 'Пользователь "' . $user->name . '" отклонил Ваше предложение вступить в организацию "' . $structure->organization->name . '"->"' . $structure->name . '" на должность "' . $notification['data']['position'] . '".';
                } else {
                    return 'Пользователь отклонил Ваше предложение вступить в организацию.';
                }
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

        if ($notification['type'] == 'App\Notifications\JobReportNotification') {
            $job = Job::find($notification['data']['job_id']);

            return 'Заказчик задания <a href="' . route('jobs.show', $job)  . '">' . $job->name . '</a> запрашивает отчет о проделанной работе.';
        }

        if ($notification['type'] == 'App\Notifications\JobOwnerReportNotification') {
            $job = Job::find($notification['data']['job_id']);
            $user = User::find($notification['data']['user_id']);
            $report = $notification['data']['report'];

            return '<p>Пользователь ' . $user->name . ' ставил отчет в задании <a href="' . route('jobs.show', $job)  . '">' . $job->name . '</a>.</p><p>' . $report . '</p>';
        }

        return '';
    }

    protected function getActions($notification)
    {
        $actions = [];

        if ($notification['type'] == 'App\Notifications\JobDecliningNotification') {
            $actions[] = [
                'route' => route('jobs.approveDecline', $notification['data']['job_id']),
                'title' => 'Подтвердить возврат денег',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('jobs.disapproveDecline', $notification['data']['job_id']),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];

            return $actions;
        }

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

        if ($notification['type'] == 'App\Notifications\AccountApproveNotification') {
            $actions[] = [
                'route' => route('notifications.approveAccount'),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.rejectAccount'),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];

            return $actions;
        }

        if ($notification['type'] == 'App\Notifications\ExperienceApproveNotification') {
            $actions[] = [
                'route' => route('notifications.approveExperience'),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.rejectExperience'),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];

            return $actions;
        }

        if ($notification['type'] == 'App\Notifications\OrganizationUserNotification') {
            $actions[] = [
                'route' => route('notifications.approveOrganization'),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.rejectOrganization'),
                'title' => 'Отклонить',
                'class' => 'btn-danger',
            ];

            return $actions;
        }

        if ($notification['type'] == 'App\Notifications\StructureUserNotification') {
            $actions[] = [
                'route' => route('notifications.approveStructure'),
                'title' => 'Принять',
                'class' => 'btn-success',
            ];

            $actions[] = [
                'route' => route('notifications.rejectStructure'),
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