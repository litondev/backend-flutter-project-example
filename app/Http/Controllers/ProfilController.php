<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\FormatResponse;
use App\Http\Requests\{
    ProfilUpdateDataRequest,
    ProfilUpdatePasswordRequest,
    ProfilUpdatePhotoRequest
};

class ProfilController extends Controller
{
    public function updateData(ProfilUpdateDataRequest $request){
        try{
    		\DB::beginTransaction();

            throw_if(
                !\Hash::check($request->password,auth()->user()->password),
                new \Exception("Password lama salah",422)
            );

    		auth()->user()->update($request->only("name","email"));

    		\DB::commit();
    		return response()->json([
    			"status" => true,
    			"user" => auth()->user()
    		]);
    	}catch(\Exception $e){
    		\DB::rollback();
            return FormatResponse::failed($e);
    	}
    }

    public function updatePassword(ProfilUpdatePasswordRequest $request){
        try{
            \DB::beginTransaction();

            throw_if(
                !\Hash::check($request->old_password,auth()->user()->password),
                new \Exception("Password lama salah",422)
            );

            auth()->user()->update([
                "password" => \Hash::make($request->password)
            ]);

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }

    public function updatePhoto(ProfilUpdatePhotoRequest $request){
        try{
            \DB::beginTransaction();

            $filename = rand(10000,9999999).".png";

            if(auth()->user()->photo != "default.png" && file_exists(public_path('/users/'.auth()->user()->photo))){
                unlink(public_path('/users/'.auth()->user()->photo));
            }

            \Image::make(request()->file("photo"))->save(public_path("/users/".$filename));

            auth()->user()->update([
                "photo" => $filename
            ]);

            \DB::commit();
            return response()->json([
                "status" => true
            ]);
        }catch(\Exception $e){
            \DB::rollback();
            return FormatResponse::failed($e);
        }
    }
}
