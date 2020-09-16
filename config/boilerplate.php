<?php

return [

    // these options are related to the sign-up procedure
    'sign_up' => [

        // this option must be set to true if you want to release a token
        // when your user successfully terminates the sign-in procedure
        'release_token' => env('SIGN_UP_RELEASE_TOKEN', false),

        // here you can specify some validation rules for your sign-in request
        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]
    ],
    'sign_upFB' => [

        // this option must be set to true if you want to release a token
        // when your user successfully terminates the sign-in procedure
        'release_token' => env('SIGN_UP_RELEASE_TOKEN', false),

        // here you can specify some validation rules for your sign-in request
        'validation_rules' => [
            'email' => 'required|email',
            'name' => 'required',
        ]
    ],

    // these options are related to the login procedure
    'login' => [

        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required'
        ]
    ],
    'updateName' => [

        'validation_rules' => [
            'name' => 'required',
        ]
    ],

    'updateDateBirth' => [

        'validation_rules' => [
            'datebirth' => 'required',
        ]
    ],
    'updateGender' => [

        'validation_rules' => [
            'gender' => 'required',
        ]
    ],
    'updatePicture' => [

        'validation_rules' => [
            'picture' => 'required',
        ]
    ], 
    'confirm' => [
 
        'validation_rules' => [
            'tokenTry' => 'required',
        ]
    ],    
    'changeEmail' => [
        
        'validation_rules' => [
            'email' => 'required',
        ]
    ], 
    'changePassword' => [

        'validation_rules' => [
            'oldPassword' => 'required',
            'password' => 'required|confirmed|min:6|max:12',
        ]
    ],
    'changePasswordF' => [

        'validation_rules' => [
            'password' => 'required|min:6|max:12',
        ]
    ],
    'Position' => [

        'validation_rules' => [
            'latitude' => 'required',
            'longitude' => 'required',
        ]
    ],  
    'Discovery' => [   

        'validation_rules' => [
            'discovery' => 'required',
        ]
    ],
    'UserData' => [   

        'validation_rules' => [
            'id' => 'required',
        ]
    ], 
    'Like' => [   

        'validation_rules' => [
            'TargetId' => 'required',
        ]
    ], 
    'Send' => [   

        'validation_rules' => [
            'IdMatch' => 'required',
            'message' => 'required',
        ]
    ], 
    'Get' => [   

        'validation_rules' => [
            'IdMatch' => 'required',
        ]
    ], 
    'DeleteMatch' => [   

        'validation_rules' => [
            'IdMatch' => 'required',
            'TargetId' => 'required',
        ]
    ], 
    // these options are related to the password recovery procedure
    'forgot_password' => [

        // here you can specify some validation rules for your password recovery procedure
        'validation_rules' => [
            'email' => 'required|email'
        ]
    ],

    // these options are related to the password recovery procedure
    'reset_password' => [

        // this option must be set to true if you want to release a token
        // when your user successfully terminates the password reset procedure
        'release_token' => env('PASSWORD_RESET_RELEASE_TOKEN', false),

        // here you can specify some validation rules for your password recovery procedure
        'validation_rules' => [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]
    ]

];
