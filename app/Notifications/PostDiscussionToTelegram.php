<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Discussion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

final class PostDiscussionToTelegram extends Notification
{
    use Queueable;

    public function __construct(public Discussion $discussion) {}

    public function via(mixed $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toTelegram(): TelegramMessage
    {
        return TelegramMessage::create()
            ->to('@laravelcm')
            ->content("{$this->discussion->title} ".route('discussions.show', $this->discussion->slug));
    }
}
