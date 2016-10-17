<?php
Route::get('/', function () {
    if (!Schema::hasTable('settings') || Config::get('database.default') == 'sqlite') {

        // If not installed, redirect to install page
        return Redirect::to('install');
    } else {
        $default_menu = Menu::published()->default('public')->first();

        if(!$default_menu || $default_menu->link == '/'){
            return Redirect::action('HomeController@index');
        } else {
            $link = str_replace('link_type', '', $default_menu->link);
            return Redirect::to($link);
        }
    }
});
//Registration routes...
Route::get('forgot_password', 'AuthController@getForgotPassword');
Route::get('logInSignUp', 'AuthController@getBothlr');
//Route::get('login/user', 'AuthController@getsLogin');
Route::post('login/user', 'AuthController@postsLogin');
Route::post('signup', 'AuthController@postRegisters');

Route::get('cscdata/{getdata}/{id}', 'AccountController@getScdata');
/*Route::post('event-date', 'EventController@saveEventdate');
Route::get('revent-date/{eid}', 'EventController@removeEventdate');
Route::post('editevent-date/{eid}', 'EventController@editEventdate');*/

Route::get('createPage', 'PageController@getCreatevent'); 
Route::get('createPage/{xword}', 'PageController@getSCreatevent');
Route::get('getStarted', 'PageController@getStarted');
Route::post('getStarted', 'PageController@postgetStarted');

Route::post('clocation', 'HomeController@postClocation');
Route::get('ghome-slide/{latlong}', 'HomeController@getImglocation');

Route::get('global-comet/{dyetimestamp}', 'HomeController@globalComet');
Route::get('global-notf/{dataid}', 'HomeController@globalNotification');
Route::get('isread-notf/{dataid}', 'HomeController@readNotification');
Route::get('globalrd-notf/{dataid}', 'HomeController@globalRdNotif');

Route::get('business-form', 'PageController@getBusinessform');
Route::get('municipality-form', 'PageController@getMunicipalityform');
Route::get('club-organization-form', 'PageController@getCluborgform');
Route::post('bmc-form', 'PageController@postAllform');
Route::post('contact/send', 'PageController@postContact');

Route::get('set-up-account', 'PageController@getSetaccount');
Route::post('set-up-account', 'PageController@postSetaccount');
Route::post('account-user/add-eventu', 'PageController@postAddeventuser');
Route::get('account-user/remove-eventu/{id}', 'PageController@removeEventuser');

Route::post('account-user/send-request', 'PageController@postSendrequest');
Route::get('account-user/{actype}', 'PageController@getAccountu');
Route::post('account-user/{actype}', 'PageController@postAccountu');
Route::get('account', 'PageController@getAccount');
Route::post('account', 'AccountController@postAccount');
Route::get('account/notifications', 'PageController@getNotifications');
Route::post('account/notifications', 'PageController@postNotifications');
Route::get('account/following', 'PageController@getFollowing');
//Route::get('account/users', 'PageController@getManageuser');
Route::post('account/follow', 'AccountController@postAccfollow');
Route::post('account/unfollow', 'AccountController@postUnfollow');
Route::post('event/pageList', 'AccountController@postEvpgList');
Route::post('event/arrangeList', 'AccountController@postEvagList');

//Route::post('event/follow', 'EventController@postEvntfollow');
Route::get('saveEvent/{eventurl}', 'EventController@saveEvent');
Route::post('event/unattendEvent', 'EventController@unattendEvent');
Route::post('sharewfriend', 'EventController@postSfriend');
Route::get('account/createEvent', 'EventController@createEvent');
Route::post('account/createEvent', 'EventController@postCevent');
Route::get('account/manageEvent', 'EventController@manageEvent');
Route::get('account/getmEvent', 'EventController@getMevent');
Route::get('account/updateEvent/{eid}', 'EventController@updateEvent');
Route::post('account/updateEvent/{eid}', 'EventController@postUpdatevent');
Route::get('account/deletevent/{eid}', 'EventController@getDeletevent');
Route::get('event/{eventurl}', 'EventController@getEvents');
Route::get('cotryevnt/{cshort}', 'EventController@getCountry');

Route::get('ghome-ecity/{address}', 'EventController@getCeventdata');
Route::get('ghome-ecity/location/{country}/{state}', 'EventController@getCstatedata');

Route::get('evnt/get-events', 'EventController@getAllevent');
Route::get('evnt/search-events', 'PageController@SearchEvent');
Route::get('evt/{eventurl}', 'EventController@getEventpopup');
Route::post('evt', 'EventController@postEventpopup');
Route::get('accevt/{eventurl}', 'EventController@accEventpopup');

Route::get('account/event-data/{atype}/{event_type}/{etid}', 'EventController@getEventdata');
Route::get('account/event-img/{gname}', 'EventController@getEventimg');

Route::get('{bcmname}/account', 'PageController@getCreatedacc');
Route::get('{bcmname}/account/createEvent', 'EventController@createAccevent');
Route::post('{bcmname}/account/createEvent', 'EventController@postAccevent');
Route::get('{bcmname}/account/manageEvent', 'EventController@manageAccevent');
Route::get('{bcmname}/account/updateEvent/{eid}', 'EventController@updateAccevent');
Route::post('{bcmname}/account/updateEvent/{eid}', 'EventController@postAccupdatevent');

Route::get('search', 'PageController@getDyesearch');
Route::post('search', 'PageController@dyeSearch');
Route::post('refine-search', 'EventController@postRefinesearch');
Route::post('evnt/refine-search/{page}', 'EventController@ajexRefinesearch');
Route::get('all/notifications', 'PageController@getAnotification');
Route::get('all/ajexNotfication', 'PageController@getajexNotf');

Route::get('{bcmname}/account/notifications', 'PageController@getAccnotifications');
Route::post('{bcmname}/account/notifications', 'PageController@postAccnotifications');
Route::get('{bcmname}/account/users', 'PageController@getAccmuser');
//Route::get('{bcmname}/account/closeTransferAccount', 'PageController@closeTransferAccount');
Route::post('{bcmname}/account/closeAccount', 'PageController@closeAccount');
Route::post('{bcmname}/account/transferAccount', 'PageController@transferAccount');

Route::post('{bcmname}/account', 'AccountController@postCreatedacc');
Route::get('{bcmurl}/account/deletevent/{eid}', 'EventController@getAccdeletevent');

// Apply CSRF protection to every routes
Route::when('*', array('csrf'));

Route::get('/', 'HomeController@index');
Route::get('wrapper/{id}', 'HomeController@wrapper');

Route::group(array('prefix' => 'install', 'middleware' => 'isInstalled'), function () {
    Route::get('{step?}', 'InstallController@index');
    Route::post('configure/{step?}', 'InstallController@configure');
    Route::post('delete_files', 'InstallController@delete_files');
});
Route::get('login', 'AuthController@getLogin');
Route::get('4caDqf8/{target}', 'AuthController@getLogin');
Route::post('4caDqf8/{target}', 'AuthController@postLogin');
Route::post('login', 'AuthController@postLogin');

Route::get('logout', 'AuthController@getLogout');
Route::post('forgot_password', 'AuthController@postForgotPassword');
Route::get('reset_password/{id}/{token}/', 'AuthController@getResetfPassword');
Route::get('reset_password/{id}/{token}/{target?}', 'AuthController@getResetPassword');
Route::post('reset_password', 'AuthController@postResetPassword');
Route::get('suspend_user/{id}/{token}', 'AuthController@suspendUser');

Route::get('contact/{category}', 'Components\ContactManager\Controllers\PublicController@showCategory');
Route::get('contact/{category}/{contact}', 'Components\ContactManager\Controllers\PublicController@showPublic');
Route::post('contact/{contact}/send', 'Components\ContactManager\Controllers\PublicController@sendMessage');

Route::resource('form', 'FormController', array('only' => array('store', 'show')));

/***************pages****************/
Route::get('about', 'Components\Posts\Controllers\CpagesController@show');
Route::get('privacy', 'Components\Posts\Controllers\CpagesController@show');
Route::get('userAgreement', 'Components\Posts\Controllers\CpagesController@show');
Route::get('cookies', 'Components\Posts\Controllers\CpagesController@show');
Route::get('contact', 'Components\Posts\Controllers\CpagesController@contactShow');

Route::get('help', 'Components\Posts\Controllers\CpagesController@show');

/*
  |--------------------------------------------------------------------------
  | Backend Routes
  |--------------------------------------------------------------------------
*/

Route::get('pages/category/{alias}', 'Components\Posts\Controllers\PostsController@category');
//Route::resource('pages', 'Components\Posts\Controllers\PostsController');

Route::get('posts/category/{alias}', 'Components\Posts\Controllers\PostsController@category');
Route::resource('posts', 'Components\Posts\Controllers\PostsController');

Route::group(array('prefix' => 'backend', 'middleware' => array('auth', 'auth.backend', 'auth.permissions')), function () {

    // For changing the current user's password
    Route::get('users/change-password', 'Backend\UserController@getChangePassword');
    Route::put('users/change-password', array('uses' => 'Backend\UserController@putChangePassword',
    'as' => 'backend.users.change-password'));
});

Route::group(array('prefix' => 'backend', 'middleware' => array('auth', 'auth.backend', 'auth.permissions', 'auth.pw_6_months')), function () {
	
 Route::get('eventList', 'CustomAdminController@eventList');
 Route::get('event/{evid}/{bl}', 'CustomAdminController@eventBlock');
 Route::get('review-event/{evid}/', 'CustomAdminController@eventReview');
 Route::get('stats', 'CustomAdminController@getStats');
 Route::get('adminGraph', 'CustomAdminController@getStatGraph');
 
//Route::get('emailList', 'CustomAdminController@emailList');
 
    Route::any('/', 'Backend\HomeController@getIndex');
    Route::get('change_language/{lang}', 'Backend\HomeController@getChangeLang');
    Route::get('config', 'Backend\HomeController@getConfig');
    Route::post('config', array('uses' => 'Backend\HomeController@postConfig', 'as' => 'config'));

    Route::get('menu-manager/set-default/{menu_id}', [
            'uses' => 'Backend\MenuManagerController@setDefault',
            'as' => 'backend.menu-manager.set-default'
        ]);
    Route::resource('menu-manager', 'Backend\MenuManagerController');
    Route::resource('menu-categories', 'Backend\MenuCategoriesController');
    Route::resource('menu-positions', 'Backend\MenuPositionsController');

    Route::resource('form-builder', 'Backend\FormBuilderController');
    Route::resource('form-categories', 'Backend\FormCategoriesController');

    Route::resource('form', 'FormController', array('only' => array('index', 'store', 'show', 'destroy')));
    Route::get('form/{id}/list', 'FormController@index');

    Route::post('users/{id}/activate', array('as' => 'backend.users.activate', 'uses' => 'Backend\UserController@activate'));
    Route::post('users/{id}/deactivate', array('as' => 'backend.users.deactivate', 'uses' => 'Backend\UserController@deactivate'));
    Route::get('users/forgot_password', 'AuthController@postForgotPassword');

    Route::resource('users', 'Backend\UserController');
    Route::resource('user-groups', 'Backend\UserGroupsController');
    Route::get('profile', 'Backend\ProfileController@showProfile');
    Route::get('profile/edit', 'Backend\ProfileController@editProfile');

    Route::get('modules', array('as' => 'backend.modules.index', 'uses' => 'Backend\ModulesController@getIndex'));
    Route::get('modules/install', array('as' => 'backend.modules.create', 'uses' => 'Backend\ModulesController@getInstall'));
    Route::post('modules/install', array('as' => 'backend.modules.store', 'uses' => 'Backend\ModulesController@postInstall'));
    Route::delete('modules/delete/{id}', array('uses' => 'Backend\ModulesController@getDelete', 'as' => 'backend.modules.destroy'));

    Route::get('module-builder/download/{id}', 'Backend\ModuleBuilderController@download');
    Route::get('module-builder/form-dropdowns/{id}', 'Backend\ModuleBuilderController@getFormDropdowns');
    Route::get('module-builder/form-fields/{form_id}/{module_id?}', 'Backend\ModuleBuilderController@getFormFields');
    Route::resource('module-builder', 'Backend\ModuleBuilderController');

    // For pages and posts
    Route::resource('pages', 'Components\Posts\Controllers\Backend\PostsController');
    Route::resource('page-categories', 'Components\Posts\Controllers\Backend\PostCategoriesController');

    Route::resource('posts', 'Components\Posts\Controllers\Backend\PostsController');
    Route::resource('post-categories', 'Components\Posts\Controllers\Backend\PostCategoriesController');

    Route::any('media-manager/create_folder', 'Components\MediaManager\Controllers\Backend\MediaManagerController@create_folder');
    Route::any('media-manager/folder_contents', 'Components\MediaManager\Controllers\Backend\MediaManagerController@folder_contents');
    Route::resource('media-manager', 'Components\MediaManager\Controllers\Backend\MediaManagerController');

    Route::post('theme-manager/apply/{id}', array(
                                'uses' => 'Components\ThemeManager\Controllers\Backend\ThemeManagerController@apply',
                                'as' => 'backend.theme-manager.apply'
                            ));
    Route::resource('theme-manager', 'Components\ThemeManager\Controllers\Backend\ThemeManagerController');
    Route::get('report-builder/module-fields/{id}', 'Components\ReportBuilder\Controllers\Backend\ReportBuilderController@getModuleFields');
    Route::get('report-builder/download/{id}', 'Components\ReportBuilder\Controllers\Backend\ReportBuilderController@download');
    Route::resource('report-builder', 'Components\ReportBuilder\Controllers\Backend\ReportBuilderController');

    Route::get('report-generators/generate/{id}', 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController@getGenerate');
    Route::post('report-generators/generate/{id}', array('uses' => 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController@postGenerate', 'as' => 'backend.report-generators.generate'));
    Route::get('report-generators/install', 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController@create');
    Route::resource('report-generators', 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController');

    Route::resource('contact-categories', 'Components\ContactManager\Controllers\Backend\ContactCategoriesController');

    Route::resource('contact-manager', 'Components\ContactManager\Controllers\Backend\ContactController');
    Route::get('contact-manager/create/{form_id}', array('uses' => 'Components\ContactManager\Controllers\Backend\ContactController@create', 'as' => 'backend.contact-manager.create'));
    Route::get('contact-manager/{id}/{form_id}', array('uses' => 'Components\ContactManager\Controllers\Backend\ContactController@show', 'as' => 'backend.contact-manager.show'));
    Route::get('contact-manager/{id}/edit/{form_id}', array('uses' => 'Components\ContactManager\Controllers\Backend\ContactController@edit', 'as' => 'backend.contact-manager.edit'));
});

/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'admin', 'middleware' => array('auth', 'auth.permissions')), function () {

    // For changing the current user's password
    Route::get('users/change-password', 'Backend\UserController@getChangePassword');
    Route::put('users/change-password', array('uses' => 'Backend\UserController@putChangePassword', 'as' => 'admin.users.change-password'));
});

Route::group(array('prefix' => 'admin', 'middleware' => array('auth', 'auth.permissions', 'auth.pw_6_months')), function () {

    if (Schema::hasTable('menus')) {
        $default_menu = Menu::published()->default('admin')->first();

        if ($default_menu) {
            $link = str_replace('link_type', 'admin', $default_menu->link);
            Route::any('/', function() use ($link) {
                return Redirect::to($link);
            });
        } else {
            Route::any('/', 'Backend\HomeController@getIndex');
        }
    } else {
        Route::any('/', 'Backend\HomeController@getIndex');
    }

    Route::get('config', 'Backend\HomeController@getConfig');
    Route::post('config', array('uses' => 'Backend\HomeController@postConfig', 'as' => 'config'));

    Route::resource('menu-manager', 'Backend\MenuManagerController');
    Route::resource('menu-categories', 'Backend\MenuCategoriesController');
    Route::resource('menu-positions', 'Backend\MenuPositionsController');

    Route::resource('form', 'FormController', array('only' => array('index', 'store', 'show', 'destroy')));
    Route::get('form/{id}/list', 'FormController@index');

    Route::resource('form-builder', 'Backend\FormBuilderController');
    Route::resource('form-categories', 'Backend\FormCategoriesController');

    Route::post('users/{id}/activate', array('as' => 'admin.users.activate', 'uses' => 'Backend\UserController@activate'));
    Route::post('users/{id}/deactivate', array('as' => 'admin.users.deactivate', 'uses' => 'Backend\UserController@deactivate'));

    Route::resource('users', 'Backend\UserController');
    Route::resource('user-groups', 'Backend\UserGroupsController');
    Route::get('profile', 'Backend\ProfileController@showProfile');
    Route::get('profile/edit', 'Backend\ProfileController@editProfile');

    Route::get('modules', array('as' => 'admin.modules.index', 'uses' => 'Backend\ModulesController@getIndex'));
    Route::get('modules/install', array('as' => 'admin.modules.create', 'uses' => 'Backend\ModulesController@getInstall'));
    Route::post('modules/install', array('as' => 'admin.modules.store', 'uses' => 'Backend\ModulesController@postInstall'));
    Route::delete('modules/delete/{id}', array('uses' => 'Backend\ModulesController@getDelete', 'as' => 'admin.modules.destroy'));

    Route::get('module-builder/download/{id}', 'Backend\ModuleBuilderController@download');
    Route::get('module-builder/form-dropdowns/{id}', 'Backend\ModuleBuilderController@getFormDropdowns');
    Route::resource('module-builder', 'Backend\ModuleBuilderController');

    // For pages and posts
    Route::resource('pages', 'Components\Posts\Controllers\Backend\PostsController');
    Route::resource('page-categories', 'Components\Posts\Controllers\Backend\PostCategoriesController');

    Route::resource('posts', 'Components\Posts\Controllers\Backend\PostsController');
    Route::resource('post-categories', 'Components\Posts\Controllers\Backend\PostCategoriesController');

    Route::any('media-manager/create_folder', 'Components\MediaManager\Controllers\Backend\MediaManagerController@create_folder');
    Route::any('media-manager/folder_contents', 'Components\MediaManager\Controllers\Backend\MediaManagerController@folder_contents');
    Route::resource('media-manager', 'Components\MediaManager\Controllers\Backend\MediaManagerController');

    Route::post('theme-manager/apply/{id}', array('uses' => 'Components\ThemeManager\Controllers\Backend\ThemeManagerController@apply', 'as' => 'admin.theme-manager.apply'));
    Route::resource('theme-manager', 'Components\ThemeManager\Controllers\Backend\ThemeManagerController');

    Route::get('report-builder/module-fields/{id}', 'Components\ReportBuilder\Controllers\Backend\ReportBuilderController@getModuleFields');
    Route::resource('report-builder', 'Components\ReportBuilder\Controllers\Backend\ReportBuilderController');
    Route::get('report-generators/generate/{id}', 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController@getGenerate');
    Route::post('report-generators/generate/{id}', array(
                            'uses' => 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController@postGenerate',
                        'as' => 'admin.report-generators.generate'));
    Route::resource('report-generators', 'Components\ReportGenerator\Controllers\Backend\ReportGeneratorController');

    Route::resource('contact-categories', 'Components\ContactManager\Controllers\Backend\ContactCategoriesController');

    Route::resource('contact-manager', 'Components\ContactManager\Controllers\Backend\ContactController');
    Route::get('contact-manager/create/{form_id}', array(
                        'uses' => 'Components\ContactManager\Controllers\Backend\ContactController@create',
                        'as' => 'backend.contact-manager.create'
                    ));
    Route::get('contact-manager/{id}/{form_id}', array(
                        'uses' => 'Components\ContactManager\Controllers\Backend\ContactController@show',
                        'as' => 'backend.contact-manager.show'
                    ));
    Route::get('contact-manager/{id}/edit/{form_id}', array(
                        'uses' => 'Components\ContactManager\Controllers\Backend\ContactController@edit',
                        'as' => 'backend.contact-manager.edit'
                    ));
});

Route::when('backend/*', array('auth', 'auth.backend'));
Route::when('admin/*', array('auth'));

// Add the routes of installed modules
foreach (glob(base_path("app/Modules/*/routes.php")) as $route) {
    require_once ($route);
}

foreach (glob(base_path("app/Modules/*/*/routes.php")) as $route) {
    require_once ($route);
}
/*************account-url*****************/
Route::get('{bcmname}', 'PageController@getAccountpage');