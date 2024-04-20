<?php

$monolog = Log::getMonolog();
$syslog = new \Monolog\Handler\SyslogHandler('papertrail');
$formatter = new \Monolog\Formatter\LineFormatter('%channel%.%level_name%: %message% %extra%');
$syslog->setFormatter($formatter);

$monolog->pushHandler($syslog);

Route::get('/', ['as' => 'home', 'uses' => 'PostsController@index']);

Route::get('machine.php', 'PostsController@showOldFormat');
Route::get('machine/{id}', 'PostsController@show');
Route::get('upload', 'PostsController@create');
Route::get('popular','PostsController@popular');
Route::get('hot', 'PostsController@hot');
Route::get('misc', 'PostsController@misc');
Route::post('posts', array('before' => 'maxUploadFileSize', 'uses' => 'PostsController@store'));
Route::get('posts/delete/{id}', 'PostsController@destroy');
Route::get('posts/approve/{id}', 'PostsController@approve');
Route::get('posts/sendToMisc/{id}', 'PostsController@sendToMisc');
Route::get('download', 'PostsController@download');
Route::get('machinereroute', 'PostsController@showReroute');

Route::get('user/{name}', 'UsersController@show');
Route::get('category/{name}', ['as'=>'home', 'uses'=>'CategoriesController@show']);
Route::get('category/hot/{name}', 'CategoriesController@showHot');
Route::get('category/misc/{name}', 'CategoriesController@showMisc');
Route::get('category/popular/{name}', 'CategoriesController@showPopular');

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('like/{id}', ['uses'=>'LikesController@like', 'middleware'=>'auth']);
Route::post('like/{id}', 'LikesController@like');

Route::post('comments', 'CommentsController@store');

Route::get('reports', ['middleware'=>'reports', 'uses'=>'ReportsController@index']);
Route::get('reports/submitted', 'ReportsController@submitted');
Route::get('reports/delete/{id}', 'ReportsController@delete');
Route::post('reports', 'ReportsController@store');
Route::get('reports/create/{id}', 'ReportsController@create');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

//todo: change this to middleware
/*
|--------------------------------------------------------------------------
| Max Upload File Size filter
|--------------------------------------------------------------------------
|
| Check if a user uploaded a file larger than the max size limit.
| This filter is used when we also use a CSRF filter and don't want
| to get a TokenMismatchException due to $_POST and $_GET being cleared.
|
*/
Route::filter('maxUploadFileSize', function()
{
    // Check if upload has exceeded max size limit
    if (! (Request::isMethod('POST') or Request::isMethod('PUT'))) { return; }
    // Get the max upload size (in Mb, so convert it to bytes)
    $maxUploadSize = 1024 * 1024 * 10;
    $contentSize = 0;
    if (isset($_SERVER['HTTP_CONTENT_LENGTH']))
    {
        $contentSize = $_SERVER['HTTP_CONTENT_LENGTH'];
    }
    elseif (isset($_SERVER['CONTENT_LENGTH']))
    {
        $contentSize = $_SERVER['CONTENT_LENGTH'];
    }
    // If content exceeds max size, throw an exception
    if ($contentSize > $maxUploadSize)
    {
        throw new GSVnet\Core\Exceptions\MaxUploadSizeException;
    }
});

//Route::get('machine.php', function()
//{
//
//        $id = Input::get('id');
//        if(isset($id)){
//            //\App\Http\Controllers\PostsController->show($id);
//            //redirect()->route('PostsController@show');
//            Route::get('{id}', 'PostsController@show');
//            //return 'PostsController@show' . $id;
//        }
//
//
//});
//Route::resource('posts','PostsController');
