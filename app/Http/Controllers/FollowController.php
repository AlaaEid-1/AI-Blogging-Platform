<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotification;
use App\Mail\GreetingMessage;
use App\Models\User;
use App\Notifications\FollowNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class FollowController extends Controller
{
    public function store(Request $request, string $id)
    {
        $user = User::query()->findOrFail($id);

        $follower = $request->user();

        $exists = $follower->followings()->where('user_id', $user->id)->exists();

        if (!$exists && $follower->id != $user->id) {
            $follower->followings()->attach($user->id, [
                'id' => Str::uuid(),
                'created_at' => now(),
            ]);

            // Send notification
            dispatch(new SendNotification(
                new FollowNotification($user, $follower),
                $user
            ))->onQueue('notifications');

            // $users = User::all();
            // Notification::send($users, new FollowNotification($user, $follower));

            // Notification::route('mail', 'info@test.co')
            //     ->notify(new FollowNotification($user, $follower));

            // Mail::to([$user->email])
            //     ->send(new GreetingMessage($user->name));
        }

        return Redirect::back();
    }

    public function destroy(Request $request, string $id)
    {
        $user = User::query()->findOrFail($id);

        $follower = $request->user();

        $follower->followings()->detach($user->id);

        return Redirect::back();
    }
}
