<?php

namespace App\Jobs;

use App\Mail\PostsSummary;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewPostsSummary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $posts = Post::query()
            ->published()
            ->where('created_at', '>=', now()->startOfWeek())
            ->where('created_at', '<=', now()->endOfWeek())
            ->orderBy('created_at', 'desc');

        $mailable = new PostsSummary($posts);

        User::cursor()->each(function ($user) use ($mailable) {

            Mail::to($user->email)->send($mailable);
        });
    }
}
