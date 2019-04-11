<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'AppController@index');
    
    // Info pages
    Route::get('/page/{id}','AppController@showPage');

    // Proposals
    Route::post('/jobs/{job}/proposals', 'JobProposalsController@store')->name('job.proposals');
    Route::post('/jobs/{job}/proposals/{proposal}/apply', 'JobProposalsController@apply')->name('proposals.apply');

    // Reports
    Route::post('/jobs/{job}/reports', 'JobReportsController@store')->name('job.reports');;
    Route::post('/jobs/{job}/notify', 'JobReportsController@notify')->name('job.notify');;

    //Job
    Route::resource('jobs', 'JobController');
    Route::get('jobs/category/{category}', 'JobController@jobsByCategories')->name('jobs.category');
    Route::post('shareJob', 'JobController@shareJob')->name('share-job');
    Route::post('complainJob', 'JobController@complainJob')->name('complain-job');
    Route::get('jobsAdmin', 'JobController@jobsAdmin')->name('jobs-admin');
    Route::patch('update-job-status', 'JobController@updateJobStatus')->name('job-status');
    Route::get('job/{id}/{app}/work','JobController@work');
    Route::resource('categories', 'CategoriesController');
    Route::post('order-categories', 'CategoriesController@order');
    Route::get('job/subtask', 'JobController@subtask');
    //Route::get('jobs/{tag?}','JobController@index')->name('jobs.index');


    Route::post('upload/files', 'UploadController@store')->name('files.upload');
    Route::get('upload/{file}', 'UploadController@download')->name('file.upload');

    //Projects
    Route::get('projects', 'ProjectsController@index')->name('projects.index');
    Route::get('projects/create', 'ProjectsController@create')->name('projects.create');
    Route::get('projects/{project}/edit', 'ProjectsController@edit')->name('projects.edit');
    Route::post('projects', 'ProjectsController@store')->name('projects.store');
    Route::patch('projects/{project}', 'ProjectsController@update')->name('projects.update');
    Route::delete('projects/{project}', 'ProjectsController@destroy')->name('projects.destroy');
    Route::get('projects/project{project}', 'ProjectsController@show')->name('projects.show');

    Route::post('projects/{project}/done', 'ProjectsController@done')->name('projects.done');
    Route::post('projects/{project}/restore', 'ProjectsController@restore')->name('projects.restore');
    Route::post('projects/{project}/edit', 'ProjectsController@edit')->name('projects.edit');    

    Route::post('projects/{project}/favorite', 'ProjectsController@favorite')->name('projects.favorite');
    Route::post('projects/{project}/unfavorite', 'ProjectsController@unfavorite')->name('projects.unfavorite');
    Route::post('projects/{project}/unfollow', 'ProjectsController@unfollow')->name('projects.unfollow');

    Route::post('order-projects', 'ProjectsController@order');
    Route::post('order-project-jobs', 'ProjectsController@orderJobs');

    //Application
//    Route::post('applyJob', 'ApplicationsController@applyJob')->name('apply-job');
    Route::post('jobs/{job}/apply', 'ApplicationsController@applyJob')->name('jobs.apply');
    Route::get('job-app-status/{id}', 'ApplicationsController@appStatus');
    Route::post('change-application-status', 'ApplicationsController@changeStatus');
    Route::get('applications', 'ApplicationsController@myApplications')->name('my-applications');
    Route::get('applications/admin','ApplicationsController@applicationsAdmin')->name('applications-admin');
    Route::post('application/post-message', 'ApplicationsController@postMessage');
    Route::post('application/delete-message', 'ApplicationsController@deleteMessage');
    Route::post('jobs/{job}/review', 'ApplicationsController@review')->name('jobs.review');
    Route::post('jobs/{job}/rating', 'ApplicationsController@rating')->name('jobs.rating');

    Route::post('jobs/{job}/decline', 'ApplicationsController@decline')->name('jobs.decline');
    Route::post('jobs/{job}/approveDecline', 'ApplicationsController@approveDecline')->name('jobs.approveDecline');
    Route::post('jobs/{job}/disapproveDecline', 'ApplicationsController@disapproveDecline')->name('jobs.disapproveDecline');

    Route::get('jobs/{job}/editProject', 'JobController@editProject')->name('jobs.editProject');
    Route::post('jobs/{job}/updateProject', 'JobController@updateProject')->name('jobs.updateProject');

    //Bookmark
    Route::get('my-bookmarks', 'BookmarksController@userBookmarks')->name('my-bookmarks');
    Route::post('bookmark/{job}', 'BookmarksController@store')->name('bookmark.store');
    Route::delete('bookmark', 'BookmarksController@destroy')->name('bookmark-remove');

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@index')->name('account');
        Route::patch('/profile', 'AccountController@updateProfile');
        Route::post('/bio', 'AccountController@updateBio');
        Route::post('/portfolio', 'AccountController@addPortfolio');
        Route::post('/approve', 'AccountController@approve');
        Route::delete('/portfolio/{id}', 'AccountController@deletePortfolio');
        Route::post('/experiences', 'AccountController@experiences');
        Route::post('/experiences/approve', 'AccountController@approveExperience')->name('experience.approve');
    });

    //users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/','UserController@users')->name('users');
        Route::get('{id}/view', ['middleware'=>['ability:admin,read-users'],'uses'=>'UserController@user']);
        Route::post('register', ['middleware' => ['ability:admin,create-users'],'uses'=>'UserController@register']);
        Route::post('{id}/update', ['middleware' => ['ability:admin,update-users'],'uses'=>'UserController@updateUser']);
        Route::post('{id}/roles', ['middleware' => ['ability:admin,update-users'],'uses'=>'UserController@updateUserRoles']);
        Route::get('find/login', 'UserController@find')->name('find.login');
    });

    //Settings
    Route::group(['prefix' => 'admin'], function () {
        Route::get('settings','AdminController@settings');
        Route::post('settings', 'AdminController@updateEnv');
        Route::post('settings/backup','AdminController@backupEnv');
        Route::get('logs', ['middleware'=>['ability:admin,read-logs'],'uses'=>'AdminController@logs']);
        Route::post('logs', ['middleware'=>['ability:admin,delete-logs'],'uses'=>'AdminController@emptyLog'])->name('empty-log');
        Route::get('debug', ['middleware'=>['ability:admin,read-logs'],'uses'=>'AdminController@debug']);
        Route::post('debug', ['middleware'=>['ability:admin,delete-logs'],'uses'=>'AdminController@emptyDebugLog'])->name('empty-debug');
        Route::get('pages', 'AdminController@showPages');
        Route::get('create-page', 'AdminController@createPage');
        Route::post('store-page', 'AdminController@storePage');        
        Route::get('delete/{id}', 'AdminController@deletePage');
        Route::get('edit/{id}', 'AdminController@editPage');
        Route::post('update-page/{id}', 'AdminController@updatePage');        
    });

    //Roles
    Route::group(['prefix' => 'roles', 'middleware' => ['role:admin']], function () {
        Route::get('/', 'Auth\AuthController@roles');
        Route::get('/getRoles', 'Auth\AuthController@rolesJson');
        Route::post('/', 'Auth\AuthController@newRole');
    });
    Route::post('role','Auth\AuthController@showRole');
    Route::post('update-role/{id}','Auth\AuthController@updateRole');

    //Skill
    Route::resource('skills', 'SkillsController');
    Route::post('skills/delete/{id}', 'SkillsController@destroy');
    Route::get('skills-json','SkillsController@skillsJson');
    Route::post('delete-my-skill','SkillsController@deleteMySkill');

    //modules
    Route::resource('modules','ModulesController');
    Route::post('update-module/{id}','ModulesController@update');
    Route::get('module-permissions/{role_id}/{module_id}','Auth\AuthController@permissions');
    Route::post('role-permissions','Auth\AuthController@updateRolePermissions');
    Route::get('perms','ModulesController@perms');

    //paypal gateway
    Route::group(['prefix'=>'paypal'],function(){
        Route::post('/pay','PaypalController@pay');
        Route::post('/success','PaypalController@success');
        Route::get('/success','PaypalController@success');
        Route::get('/cancelled','PaypalController@cancelled');
        Route::post('/notify','PaypalController@notify');
    });

    Route::group(['prefix'=>'payouts'],function(){
        Route::get('/','BillingController@payouts');
        Route::post('stripe','StripeController@charge')->name('stripe-charge');
    });

    Route::group(['prefix' => 'peoples'], function () {
        Route::get('/','PeopleController@index')->name('peoples.index');
        Route::get('/{user}','PeopleController@show')->name('peoples.show');
        Route::post('/{user}','PeopleController@updateAvatar')->name('peoples.updateAvatar');

        Route::post('/{user}/favorite', 'PeopleController@favorite')->name('peoples.favorite');
        Route::post('/{user}/unfavorite', 'PeopleController@unfavorite')->name('peoples.unfavorite');
    });

    // Teams
    Route::get('/teams/myteams','TeamsController@myteams')->name('teams.myteams');    
    Route::get('/teams/projects','TeamsController@projects')->name('teams.projects');
    Route::post('/teams/{team}/addAdmin','TeamsController@addAdmin')->name('teams.addAdmin');
    Route::post('/teams/{team}/deleteAdmin','TeamsController@deleteAdmin')->name('teams.deleteAdmin');
    Route::post('/teams/{team}/disconnect','TeamsController@disconnect')->name('teams.disconnect');
    Route::post('/teams/{team}/favorite', 'TeamsController@favorite')->name('teams.favorite');
    Route::post('/teams/{team}/unfavorite', 'TeamsController@unfavorite')->name('teams.unfavorite');
    Route::resource('teams', 'TeamsController');

    Route::resource('/vacancies', 'VacanciesController', ['only' => ['index', 'show']]);
    Route::post('/vacancies/{vacancy}/favorite', 'VacanciesController@favorite')->name('vacancies.favorite');
    Route::post('/vacancies/{vacancy}/unfavorite', 'VacanciesController@unfavorite')->name('vacancies.unfavorite');

    Route::get('/vacancies/{vacancy}/cvs/create','VacancyCvsController@create')->name('vacancies.cvs.create');
    Route::post('/vacancies/{vacancy}/cvs/store','VacancyCvsController@store')->name('vacancies.cvs.store');
    Route::delete('/vacancies/{vacancy}/cvs/{cv}','VacancyCvsController@destroy')->name('vacancies.cvs.destroy');
    Route::post('/vacancies/{vacancy}/cvs/{cv}/approve','VacancyCvsController@approve')->name('vacancies.cvs.approve');
    Route::post('/vacancies/{vacancy}/cvs/{cv}/reject','VacancyCvsController@reject')->name('vacancies.cvs.reject');
    Route::get('/vacancies/{vacancy}/cvs/{cv}/success','VacancyCvsController@success')->name('vacancies.cvs.success');
    Route::post('/vacancies/{vacancy}/cvs/{cv}/success','VacancyCvsController@successStore')->name('vacancies.cvs.success-store');

    Route::get('notifications', 'NotificationsController@index')->name('notifications.index');
    Route::post('notifications/approve', 'NotificationsController@approve')->name('notifications.approve');
    Route::post('notifications/reject', 'NotificationsController@reject')->name('notifications.reject');
    Route::post('notifications/destroy', 'NotificationsController@destroy')->name('notifications.destroy');

    Route::post('notifications/approveOrganization', 'NotificationsController@approveOrganization')->name('notifications.approveOrganization');
    Route::post('notifications/rejectOrganization', 'NotificationsController@rejectOrganization')->name('notifications.rejectOrganization');

    Route::post('notifications/approveStructure', 'NotificationsController@approveStructure')->name('notifications.approveStructure');
    Route::post('notifications/rejectStructure', 'NotificationsController@rejectStructure')->name('notifications.rejectStructure');

    Route::post('notifications/approveAccount', 'NotificationsController@approveAccount')->name('notifications.approveAccount');
    Route::post('notifications/rejectAccount', 'NotificationsController@rejectAccount')->name('notifications.rejectAccount');

    Route::post('notifications/approveExperience', 'NotificationsController@approveExperience')->name('notifications.approveExperience');
    Route::post('notifications/rejectExperience', 'NotificationsController@rejectExperience')->name('notifications.rejectExperience');

    // Locale
    Route::get('setlocale/{locale}', function ($locale) {
        if (in_array($locale, \Config::get('app.locales'))) {
          Session::put('locale', $locale);
        }
          return redirect()->back();
    });

    // Comments
    Route::post('/comments','CommentController@store')->name('comments.store');

    Route::resource('threads', 'ThreadsController');

    // Messages
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/organizations/moderation','OrganizationsAccessController@moderation')->name('organizations.moderation');
        Route::post('/organizations/approve/{organization}','OrganizationsAccessController@approve')->name('organizations.approve');
        Route::post('/organizations/reject/{organization}','OrganizationsAccessController@approve')->name('organizations.reject');

        Route::post('/organizations/{organization}/addAdmin','OrganizationsAccessController@addAdmin')->name('organizations.addAdmin');
        Route::post('/organizations/{organization}/deleteAdmin','OrganizationsAccessController@deleteAdmin')->name('organizations.deleteAdmin');
        Route::post('/organizations/{organization}/addFullAccess','OrganizationsAccessController@addFullAccess')->name('organizations.addFullAccess');
        Route::post('/organizations/{organization}/deleteFullAccess','OrganizationsAccessController@deleteFullAccess')->name('organizations.deleteFullAccess');

        Route::get('/organizations/my-organizations','OrganizationsController@my')->name('organizations.my');
        Route::resource('organizations', 'OrganizationsController');

        Route::resource('/organizations/{organization}/structure', 'StructuresController');

        Route::resource('/organizations/{organization}/vacancies', 'OrganizationVacanciesController', ['as' => 'organizations']);
        Route::patch('/organizations/{organization}/vacancies/{vacancy}/publish', 'OrganizationVacanciesController@publish')->name('organizations.vacancies.publish');
    });

    Route::get('tasks', 'TasksController@index')->name('tasks.index');

    Route::group(['middleware' => 'auth'], function () {
       Route::post('/escrow/payer-card', 'Escrow\PayerCardsController@store')->name('escrow-payer-card');
       Route::delete('/escrow/payer-card', 'Escrow\PayerCardsController@destroy')->name('escrow-payer-card-delete');

        Route::post('/escrow/beneficiary-card', 'Escrow\BeneficiaryCardsController@store')->name('escrow-beneficiary-card');
        Route::delete('/escrow/beneficiary-card', 'Escrow\BeneficiaryCardsController@destroy')->name('escrow-beneficiary-card-delete');
    });
});

// Localization
Route::get('/js/lang.js', function () {
    //$strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

    //    return $strings;
    //});

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');

Route::group(['prefix' => 'api', 'namespace' => '\API'], function () {
    Route::get('jobs', 'JobsController@index');
    Route::get('categories/{category}', 'CategoriesController@show');
    Route::post('upload', 'UploaderController@index')->name('uploader');

    Route::get('threads', 'ThreadsController@index');
    Route::get('threads/{thread}/messages', 'MessagesController@index');

    Route::get('users/search', 'UserController@search');
    Route::get('teams/search', 'TeamController@search');

    Route::get('vacancies', 'VacanciesController@index');
    Route::get('vacancies/search', 'VacanciesController@search');

    Route::get('cvs', 'CvsController@index');
    Route::get('cvs/search', 'CvsController@search');

    Route::post('projects/{project}/notes', 'ProjectsController@notes');

    Route::resource('tasks', 'TasksController', [
        'as' => 'api'
    ]);
});