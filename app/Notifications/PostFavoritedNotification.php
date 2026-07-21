<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostFavoritedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected \App\Models\Post $post, protected \App\Models\User $user)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Someone liked your post')
            ->line("{$this->user->name} liked your post: {$this->post->title}")
            ->action('View Post', route('posts.show', $this->post->slug))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Favorite',
            'body' => "{$this->user->name} favorited your post: {$this->post->title}",
            'link' => route('posts.show', $this->post->slug),
            'meta' => [
                'user_id' => $this->user->id,
                'user_avatar' => $this->user->avatar_url,
                'post_id' => $this->post->id,
            ],
        ];
    }
}
