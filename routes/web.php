<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'AppController@index');
    
    // Info pages
    Route::get('/page/{id}','AppController@showPage');

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
    Route::resource('projects', 'ProjectsController');
    Route::post('projects/{project}/done', 'ProjectsController@done')->name('projects.done');
    Route::post('projects/{project}/restore', 'ProjectsController@restore')->name('projects.restore');

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

    //Bookmark
    Route::get('my-bookmarks', 'BookmarksController@userBookmarks')->name('my-bookmarks');
    Route::post('bookmark/{job}', 'BookmarksController@store')->name('bookmark.store');
    Route::delete('bookmark', 'BookmarksController@destroy')->name('bookmark-remove');

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@index');
        Route::patch('/profile', 'AccountController@updateProfile');
        Route::post('/bio', 'AccountController@updateBio');
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
    });

    Route::resource('teams', 'TeamsController');

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
});

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');

Route::group(['prefix' => 'api', 'namespace' => '\API'], function () {
    Route::get('jobs', 'JobsController@index');
    Route::get('categories/{category}', 'CategoriesController@show');
});