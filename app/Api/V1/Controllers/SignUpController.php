<?php

namespace App\Api\V1\Controllers;
use Mail;
use Config;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use App\Api\V1\Requests\changeEmailRequest;
use App\Api\V1\Requests\changePasswordFRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }
        $user -> email_token = rand ( 10000 , 99999 );
        $user -> AccCreationStep = 1;
        $user -> save();

        Mail::raw($user['email_token'], function($message) use ($user)
        {
            $message->subject('Email Confirmation Code');
            $message->from('noreply@unKnown.app', 'unKnown');
            $message->to($user -> email);
        });

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }

    public function SendCodeForgot(changeEmailRequest $request)
    {
        $user = User::where('email',$request->input('email')) 
        ->where('fbuser', 0)
        -> first();
        if ($user) {
            $user -> email_code = rand ( 10000 , 99999 );
            $user -> save();

            Mail::raw($user['email_code'], function($message) use ($request)
            {
                $message->subject('Email Code');
                $message->from('noreply@unKnown.app', 'unKnown');
                $message->to($request->input('email'));
            });

            return response()
            ->json(['Success' => 'CodeOk']); 
        }
        else {
            return response()
            ->json(['Error' => 'Email doesnt Exist']); 
        }
    }

    public function changePasswordF(changePasswordFRequest $request)
    {
        $user = User::where('email',$request->input('email')) 
        -> first();

        if ($user -> email_code == $request->input('code')) {
            $user -> password = $request->input('password');
            $user -> save();

            return response()
            ->json(['Success' => 'Password changed']);   
        }
        else {
            return response()
            ->json(['Error' => 'Code not valid']);  
        }
    }
}
