<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('SendCodeForgot', 'App\\Api\\V1\\Controllers\\SignUpController@SendCodeForgot');
        $api->post('changePasswordF', 'App\\Api\\V1\\Controllers\\SignUpController@changePasswordF');
        $api->post('signUpFB', 'App\\Api\\V1\\Controllers\\SignUpFBController@signUpFB');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    
        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');

        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
        $api->post('deleteUser','App\\Api\\V1\\Controllers\\UserController@deleteUser');
        $api->post('updateName', 'App\\Api\\V1\\Controllers\\UserController@updateName');
        $api->post('updateDateBirth', 'App\\Api\\V1\\Controllers\\UserController@updateDateBirth');
        $api->post('updateGender', 'App\\Api\\V1\\Controllers\\UserController@updateGender');
        $api->post('updatePicture', 'App\\Api\\V1\\Controllers\\UserController@updatePicture');
        $api->post('changePassword', 'App\\Api\\V1\\Controllers\\UserController@changePassword');
        $api->post('updateInfo', 'App\\Api\\V1\\Controllers\\UserController@updateInfo');
        $api->post('confirmEmail', 'App\\Api\\V1\\Controllers\\UserController@confirmEmail');
        $api->post('changeEmail', 'App\\Api\\V1\\Controllers\\UserController@changeEmail');

        $api->post('updatePosition', 'App\\Api\\V1\\Controllers\\UserController@updatePosition');
        $api->get('getUsersClose', 'App\\Api\\V1\\Controllers\\UserController@getUsersClose');
        $api->post('updateDiscovery', 'App\\Api\\V1\\Controllers\\UserController@updateDiscovery'); 

        $api->post('getUser', 'App\\Api\\V1\\Controllers\\UserController@getUser'); 

        $api->post('Like', 'App\\Api\\V1\\Controllers\\UserController@Like');
        $api->get('getMatches', 'App\\Api\\V1\\Controllers\\UserController@getMatches');
        $api->post('DeleteMatch', 'App\\Api\\V1\\Controllers\\UserController@DeleteMatch');

        $api->post('SendMessage', 'App\\Api\\V1\\Controllers\\UserController@SendMessage'); 
        $api->post('GetMessages', 'App\\Api\\V1\\Controllers\\UserController@GetMessages');

        $api->get('GetMyId', 'App\\Api\\V1\\Controllers\\UserController@GetMyId'); 

        $api->post('sendCode', 'App\\Api\\V1\\Controllers\\UserController@sendCode');

    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'unknownApi v1'
        ]);
    });

    $api->get('serverTodayDate', function() {
        $todayDate = date('Y-m-d');
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        return response()
            ->json(array('dateToday'=>$todayDate,'day'=>$day,'month'=>$month,'year'=>$year));
    });

});
