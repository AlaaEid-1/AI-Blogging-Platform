<?php

namespace App\Notifications;

use App\Mail\GreetingMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected User $user, protected User $follower)
    {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via = ['database', 'mail', 'broadcast'];

        if ($notifiable->sms_notify) {
            $via[] = 'vonage';
        }

        if ($notifiable->broadcast_norify) {
            $via[] = 'broadcast';
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage|GreetingMessage
    {
        // return (new GreetingMessage($notifiable->name))
        //     ->to($notifiable->email);

        return (new MailMessage)
            ->subject('New Follower')
            ->from('follow@write.ai', 'Write.ai')
            ->level('info')
            // ->view('mails.follow', [
            //     'user' => $notifiable,
            //     'follower' => $this->follower,
            // ])
            ->greeting("Hi {$notifiable->name},")
            ->line("{$this->follower->name} started following you.")
            ->action('View Profile', route('users.profile', $this->follower->username))
            ->line('Thank you for using our application!')
            ->salutation('Best regards,')
        ;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New follower',
            'body' => "{$this->follower->name} started following you.",
            'link' => route('users.profile', $this->follower->username),
            'meta' => [
                'follower_id' => $this->follower->id,
                'follower_avatar' => $this->follower->avatar,
            ],
        ];
    }

    public function toBroadcast(object $notifiable): array|BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'New follower',
            'body' => "{$this->follower->name} started following you.",
            'link' => route('users.profile', $this->follower->username),
            'meta' => [
                'follower_id' => $this->follower->id,
                'follower_avatar' => $this->follower->avatar,
            ],
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
