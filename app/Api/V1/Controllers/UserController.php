<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use App\Api\V1\Requests\updateNameRequest;
use App\Api\V1\Requests\updateDateBirthRequest;
use App\Api\V1\Requests\updateGenderRequest;
use App\Api\V1\Requests\updatePictureRequest;
use App\Api\V1\Requests\changePasswordRequest;
use App\Api\V1\Requests\changePasswordFRequest;
use App\Api\V1\Requests\updateInfoRequest;
use App\Api\V1\Requests\confirmRequest; 
use App\Api\V1\Requests\changeEmailRequest;
use App\Api\V1\Requests\PositionRequest;
use App\Api\V1\Requests\DiscoveryRequest;
use App\Api\V1\Requests\UserRequest; 
use App\Api\V1\Requests\LikeRequest;
use App\Api\V1\Requests\SendRequest;
use App\Api\V1\Requests\GetRequest; 
use App\Api\V1\Requests\DeleteMatchRequest;

use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use Hash;
use Carbon\Carbon;
use Mail;


class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::guard()->user());
    }

    public function allUsersInfo()
    {
        return response()->json(Auth::guard()->user()::all());
    }

    public function deleteUser()
    {
        $user = Auth::User();

        \DB::table('matches')->where('IdUser1', $user-> id)
        ->orWhere('IdUser2', $user-> id)
        ->delete();

        \DB::table('messages')->where('IdUser', $user-> id)
        ->delete();

        \DB::table('likes')
        ->where(function($q) use($user) {
            $q->where('UserId', $user -> id)
              ->orWhere('UserIdLiked', $user -> id);
        })
        ->delete();

        $user -> delete();

        return response()
            ->json(['Success' => 'Account Deleted']);
    }

    public function updateName(updateNameRequest $request)
    {
        $user = Auth::User();
        $user -> name = $request->input('name');
        $user -> AccCreationStep = 2;
        $user -> save();

        return response()
            ->json(['Success' => 'Name Changed']);

    }
    public function updateDateBirth(updateDateBirthRequest $request)
    {
        $user = Auth::User();
        $user -> datebirth = $request->input('datebirth');
        $user -> AccCreationStep = 3;
        $user -> save();

        return response()
            ->json(['Success' => 'DateBirth Changed']);
    }

    public function updateGender(updateGenderRequest $request)
    {
        $user = Auth::User();
        $user -> gender = $request->input('gender');
        $user -> AccCreationStep = 4;
        $user -> save();

        return response()
            ->json(['Success' => 'Gender Changed']);
    }

    public function updatePicture(updatePictureRequest $request)
    {
        $user = Auth::User();
        $user -> picture = $request->input('picture');
        $user -> AccCreationStep = 5;
        $user -> save();

        return response()
            ->json(['Success' => 'Picture Changed']);
    }


    public function changePassword(changePasswordRequest $request)
    {
        $user = Auth::User();
        $old = $request->oldPassword;

        if(Hash::check($old, $user->password)) {
            $user -> password = $request->input('password');
            $user -> save();
            return response()
            ->json(['Success' => 'Password Changed']);
        }
        else {
            return response()
            ->json(['Error' => 'Old Password not equal']);
        }
    }
    
    public function updateInfo(updateInfoRequest $request)
    {
        $user = Auth::User();
        $user -> bio = $request->input('bio');
        $user -> work = $request->input('work');
        $user -> place = $request->input('place');
        $user -> school = $request->input('school');
        $user -> gender = $request->input('gender');
        $user -> save();

        return response()
            ->json(['Success' => 'Info Changed']);

    }

    public function confirmEmail(confirmRequest $request)
    {
        $user = Auth::User();

        
        if($request->tokenTry == $user->email_token) { 
            $user -> verified = 1;
            $user -> save();

            return response()
            ->json(['Confirmed' => 'Account activated']);
        }
        else {
           return response()
            ->json(['Error' => 'Email Token not valid']); 
        }
        

    }

    public function sendCode(changeEmailRequest $request)
    {
        $exists = \DB::table('users')
            ->where('email', $request->input('email'))
            ->first();
        if (!$exists) {
            $user = Auth::User();

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
            ->json(['Error' => 'Email Exists']); 
        }
        
            
    }
        

    public function changeEmail(changeEmailRequest $request)
    {
        $user = Auth::User();

        if ($user -> email_code == $request->input('code')) {
            $user -> email = $request->input('email');
            $user -> save();

            return response()
            ->json(['Success' => 'Email changed']);   
        }
        else {
            return response()
            ->json(['Error' => 'Code not valid']);  
        }

        
    }

    public function updatePosition(PositionRequest $request)
    {
        $user = Auth::User();

        $user -> latitude = $request->input('latitude');
        $user -> longitude = $request->input('longitude');
        $user -> save();

        $user->touch();

        return response()
        ->json(['Success' => 'Position Updated']); 
        
    }

    public function getUsersClose() {
        $user = Auth::User();

        if ($user -> discovery == 1) {
            $latitude = $user -> latitude;
            $longitude = $user -> longitude;

            $upper_latitude = $latitude + (.005);
            $lower_latitude = $latitude - (.005);
            $upper_longitude = $longitude + (.005); 
            $lower_longitude = $longitude - (.005); 

            $result = \DB::table('users')->select('id','name','latitude','longitude')
            ->whereBetween('latitude', [$lower_latitude, $upper_latitude])
            ->whereBetween('longitude', [$lower_longitude, $upper_longitude])
            ->where('discovery', 1)
            ->where('updated_at', '>', Carbon::now()->subMinutes(1)->toDateTimeString())
            ->where('id', '!=', Auth::id())
            ->get();

            return response()
            ->json(['Success' => $result]);            
        }
        else {
            return response()
            ->json(['Error' => 'Discovery Disabled']);  
        }
    }

    public function updateDiscovery(DiscoveryRequest $request) {
        $user = Auth::User();

        $user -> discovery = $request->input('discovery');
        $user -> save();

        return response()
        ->json(['Success' => 'Discovery changed']); 
    }

    public function getUser(UserRequest $request) {
        $user = Auth::User();
        $todayDate = date('Y-m-d');
        $result = \DB::table('users')->select('id','name','picture','datebirth','gender','work','place','school','bio')
        ->where('id', '=', $request -> id)
        ->get();

        return response()
        ->json(['Success' => $result, $todayDate]);            
    
    }

    public function Like(LikeRequest $request) {
        $user = Auth::User();

        $check = \DB::table('likes')
        ->where('UserId', $request->input('TargetId'))
        ->where('UserIdLiked', $user -> id)
        ->first();

        if ($check) {
            $checkMatch = \DB::table('matches') 
            ->where(function($q) use($request,$user) {
                $q->where('IdUser1', $user -> id)
                  ->Where('IdUser2', $request->input('TargetId'));
            })
            ->orWhere(function($q2) use($request,$user) {
                $q2->where('IdUser1', $request->input('TargetId'))
                  ->Where('IdUser2', $user -> id);
            })
            ->first();

            if (!$checkMatch) {
                \DB::table('matches')->insert(
                    ['IdUser1' => $user -> id, 'IdUser2' => $request->input('TargetId'),'created_at' => \Carbon\Carbon::now()]
                ); 
                return response()
                ->json(['Success' => 'criei match']);   
            }
            else {
              return response()
                ->json(['Success' => 'match ja existe']);       
            }
        }
        else {
            $exists = \DB::table('likes')
            ->where('UserId', $user -> id)
            ->where('UserIdLiked', $request->input('TargetId'))
            ->first();
            if($exists) {
                return response()
                ->json(['Success' => 'existe']);     
            }
            else {
                \DB::table('likes')->insert(
                    ['UserId' => $user -> id, 'UserIdLiked' => $request->input('TargetId')]
                ); 
            }
        }
    }

    public function getMatches() {
        $user = Auth::User();

        $result = \DB::table('matches')->select('id','IdUser1','IdUser2','created_at')
        ->where('IdUser1', $user -> id)
        ->orWhere('IdUser2', $user -> id)
        ->get();

        $Array = array();
        
        foreach($result as $r) {
            if ($user -> id == $r->IdUser1) {

                $name = \DB::table('users')->select('id','name','picture')
                ->where('id', $r->IdUser2)
                ->get();

                $name->put('idMatch', $r-> id);
                $name->put('created_at', $r-> created_at);
                $Array[] = $name;
            }
            else {
                $name = \DB::table('users')->select('id','name','picture')
                ->where('id', $r->IdUser1)
                ->get();
                
                $name->put('idMatch', $r-> id);
                $name->put('created_at', $r-> created_at);
                $Array[] = $name;

            }
        }
        return response()
        ->json($Array);       
        
    }

    public function DeleteMatch(DeleteMatchRequest $request) {

        $user = Auth::User();

        \DB::table('matches')->where('id', $request->input('IdMatch'))->delete();
        \DB::table('messages')->where('IdMatch', $request->input('IdMatch'))->delete();

        \DB::table('likes')
        ->where(function($q) use($request,$user) {
            $q->where('UserId', $user -> id)
              ->Where('UserIdLiked', $request->input('TargetId'));
        })
        ->orWhere(function($q2) use($request,$user) {
            $q2->where('UserId', $request->input('TargetId'))
              ->Where('UserIdLiked', $user -> id);
        })
        ->delete();
    }   

    public function SendMessage(SendRequest $request) {

        $user = Auth::User();

         \DB::table('messages')->insert(
                    ['IdMatch' => $request->input('IdMatch'), 
                    'IdUser' => $user -> id, 
                    'message' => $request->input('message'),
                    'created_at' => \Carbon\Carbon::now()]
                ); 
    }

    public function GetMessages(GetRequest $request) {

        $user = Auth::User();

        $messages = \DB::table('messages')->select('idUser','message','created_at')
            ->where('IdMatch', $request->input('IdMatch'))
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();
            

        return response()
        ->json($messages);  

    }

    public function GetMyId() {

        $user = Auth::User();

        $id = $user -> id;
            

        return response()
        ->json($id);  

    }


    
}