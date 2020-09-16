<?php

namespace App\Api\V1\Controllers;
use Mail;
use Config;
use Auth;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpFBRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SignUpFBController extends Controller
{
    public function signUpFB(SignUpFBRequest $request, JWTAuth $JWTAuth)
    {

        if (User::where('email', '=', $request->input('email'))->exists()) {

            $email =$request->input('email');
            $user = User::where('email',$email)->first();

            try {
            $token = Auth::login($user, true);

            if(!$token) {
                throw new AccessDeniedHttpException();
            }

            } catch (JWTException $e) {
                throw new HttpException(500);
            }


            return response()
            ->json([
                'status' => 'login',
                'token' => $token,
                'expires_in' => Auth::guard()->factory()->getTTL() * 1000
            ]);

         }
        else {
            $user = new User;
            $user->email = $request->input('email');
            $user->password = str_random(12);
            $user->name = $request->input('name');
            $user->verified = 1;
            $user->fbUser = 1;
            $user->datebirth = $request->input('datebirth');
            $user->save();
        

            if(!$user->save()) {
                throw new HttpException(500);
            }
            $token = Auth::login($user, true);
            
            return response()->json([
                'status' => 'ok',
                'token' => $token
            ], 201);
        }
        
    }
}
