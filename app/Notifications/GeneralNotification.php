<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $icon = 'fa-bell',
        public string $color = 'primary',
        public ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'color' => $this->color,
            'url' => $this->url,
        ];
    }

    public static function sendToUser($user, string $title, string $message, string $icon = 'fa-bell', string $color = 'primary', ?string $url = null): void
    {
        $user->notify(new static($title, $message, $icon, $color, $url));
    }

    public static function sendToUsers($users, string $title, string $message, string $icon = 'fa-bell', string $color = 'primary', ?string $url = null): void
    {
        foreach ($users as $user) {
            static::sendToUser($user, $title, $message, $icon, $color, $url);
        }
    }

    public static function sendToAllUsers(string $title, string $message, string $icon = 'fa-bell', string $color = 'primary', ?string $url = null): void
    {
        $users = \App\Models\User::all();
        static::sendToUsers($users, $title, $message, $icon, $color, $url);
    }
}
