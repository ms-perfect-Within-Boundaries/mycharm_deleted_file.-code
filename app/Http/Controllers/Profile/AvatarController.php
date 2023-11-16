<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;


class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {  
        $path=Storage::disk('public')->put('avatars',$request->file('avatar')) ;
        dd($path);
        $path = $request->file('avatar')->store('avatars','public');
        
        if($old_avatar=$request->user()->avatar){
            dd($old_avatar);
            Storage::disk('public')->delete($old_avatar);
        }
        
        auth()->user()->update(['avatar'=>$path]);
        //dd($path);
        return redirect(route('profile.edit'))->with('message','Avatar is updated');
    }
}