<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostCommentedNotification extends Notification implements ShouldQueue{
    
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Post $post, protected User $user, protected string $commentContent)
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
            ->subject('New comment on your post')
            ->line("{$this->user->name} commented on your post: {$this->post->title}")
            ->line('Comment:')
            ->line("\"{$this->commentContent}\"")
            ->action('View Comment', route('posts.show', $this->post->slug).'#comments')
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
            'title' => 'New Comment',
            'body' => "{$this->user->name} commented on your post: {$this->post->title}",
            'link' => route('posts.show', $this->post->slug).'#comments',
            'meta' => [
                'user_id' => $this->user->id,
                'user_avatar' => $this->user->avatar_url,
                'post_id' => $this->post->id,
            ],
        ];
    }
}
