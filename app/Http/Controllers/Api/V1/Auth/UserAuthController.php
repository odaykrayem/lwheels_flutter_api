<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogic\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\CustomValue;
use App\Models\RefRecord;
use App\Models\User;
use App\Models\WheelTimerRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class UserAuthController extends Controller
{



    public function register(Request $request)
    {
        $creds = [
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'phone' => $request['phone'],
            'password' => $request['passowrd'],
        ];
        if (!Auth::attempt($creds)) {

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'l_name' => 'required',
                'phone' => 'required|unique:users',
                'password' => 'required|min:6',
            ], [
                'f_name.required' => 'The first name field is required.',
                'l_name.required' => 'The last name field is required.',
                'phone.required' => 'The  phone field is required.',
                'password.min' => 'Password must be at least 6.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $this->error_processor($validator)], 403);
            }
            $refcode = '';
            do {
                $refcode = Str::random(20);
                $user_code = User::where('ref_code', $refcode)->first();
            } while (!empty($user_code));
            $pass = bcrypt($request->password);

            $refTimesExist = CustomValue::where('key', 'ref_times')->exists();
            $refTimes = 10;
            if($refTimesExist){
                $refTimes = CustomValue::where('key', 'ref_times')->first()['value'];
            }

            $userCreds = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'ref_times' => $refTimes,
                'phone' => $request->phone,
                'ref_code' => $refcode,
                'password' => $pass,
            ];
            $userCreate = User::create($userCreds);

            if(isset($request['ref_code'])){
                $refOnwer = User::where('ref_code' , $request['ref_code'])->first();
                $addPointUser = CustomValue::where('key', 'referral_points_user')->first();
                $addPointOwner = CustomValue::where('key', 'referral_points_owner')->first();

                if($refOnwer != null){
                    RefRecord::create(
                        ['owner_id' => $refOnwer->id ,
                        'user_id' => $userCreate->id
                        ]
                       );
                       if($addPointUser != null){
                        User::where('id', [$refOnwer->id])->update([
                            'points' => $refOnwer->points + $addPointOwner->value,
                        ]);
                        $refTimes = User::where('id', [$refOnwer->id])->first()['ref_times'];
                        if($refTimes > 0){
                            User::where('id', [$refOnwer->id])->update([
                                'ref_times' => $refOnwer->ref_times - 1 ,
                            ]);
                        }

                        User::where('id', [$userCreate->id])->update([
                            'points' => $userCreate->points + $addPointUser->value,
                        ]);
                       }
                }

            }

            $credentials = [
                'phone' => $request['phone'],
                'password' => $request['password'],
            ];

            if (Auth::attempt($credentials)) {

                $user = new UserResource(User::where('id',$userCreate->id)->first());
                // $wheelTimerRecordExist = WheelTimerRecord::where('user_id',$user->id)->exist();
                // $timer_on = false;
                // if($wheelTimerRecordExist){
                //     $timer_on = WheelTimerRecord::where('user_id', $user->id)->first()['is_on'];
                // }
                // $user['timer_on'] = $timer_on;
                return response()->json([
                    'msg' => 'success',
                    'data' => $user
                ], 200);
            }
        }
    }

    public function updatePassword(Request $request){
       $user =User::where('phone' , $request['phone'])->first();
       $newPass = $request['password'];
       $pass = bcrypt($newPass);


       User::where('id', [$user->id])->update([
        'password' => $pass,
       ]);

        return response()->json([
            'msg' => 'success',
            // 'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $this->error_processor($validator)], 403);
        }
        $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            //auth()->user() is coming from laravel auth:api middleware
            // $user = Auth::user();
            // if (auth('sanctum')->check()) {
            //     auth()->user()->tokens()->delete();
            // }

            // $token = $user->createToken('LWheelsAuth');
            // if (!auth()->user()->status) {
            //     $errors = [];
            //     array_push($errors, ['code' => 'auth-003', 'message' => trans('messages.your_account_is_blocked')]);
            //     return response()->json([
            //         'errors' => $errors
            //     ], 403);
            // }

            $user = new UserResource(User::where('phone', $request['phone'])->first());
            return response()->json([
                'data' => $user
            ], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->where('tokenable_id', $request['user_id'])->delete();
    }
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
}
