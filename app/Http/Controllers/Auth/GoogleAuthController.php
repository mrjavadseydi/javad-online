<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();

    }
    public function authCallback(){
        try {
            $google = Socialite::driver('google')->user();
            if ($user = User::Where('email',$google->email)->first()){
                auth()->loginUsingId($user->id);
                return redirect(route('login'));
            }else{
                $user = User::create([
                    'name'=>$google->name,
                    'email'=>$google->email,
                    'password'=>bcrypt(Str::random(12))
                ]);
                auth()->loginUsingId($user->id);
                return redirect(route('login'));
            }
        }catch (\Exception $e){
            alert()->error('خطایی یافت شد')->confirmButton('تایید');
            return redirect(route('login'));
        }
    }
}
