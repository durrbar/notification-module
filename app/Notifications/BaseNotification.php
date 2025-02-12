<?php

namespace Modules\Notification\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public const NOTIFICATION_TYPE = '';

    protected $data;
    protected $preferences;
    protected string $notificationId;

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $preferences)
    {
        $this->data = $data;
        $this->preferences = $preferences;
        $this->notificationId = uniqid();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        $channels = [];

        if ($this->isEmailChannelEnabled()) {
            $channels[] = 'mail';
        }
        if ($this->isSmsChannelEnabled()) {
            $channels[] = 'nexmo'; // Or any other SMS driver
        }
        if ($this->isDatabaseChannelEnabled()) {
            $channels[] = 'database';
        }
        if ($this->isBroadcastChannelEnabled()) {
            $channels[] = 'broadcast';
        }

        return $channels;
    }

    /**
     * Determine if the email channel is enabled.
     */
    protected function isEmailChannelEnabled(): bool
    {
        return $this->preferences->email ?? false;
    }

    /**
     * Determine if the SMS channel is enabled.
     */
    protected function isSmsChannelEnabled(): bool
    {
        return $this->preferences->sms ?? false;
    }

    /**
     * Determine if the database channel is enabled.
     */
    protected function isDatabaseChannelEnabled(): bool
    {
        return true;
    }

    /**
     * Determine if the broadcast channel is enabled.
     */
    protected function isBroadcastChannelEnabled(): bool
    {
        return $this->preferences->broadcast ?? false;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage())
            ->subject($this->getMailSubject());

        // Add optional greeting
        if ($greeting = $this->getMailGreeting()) {
            $mailMessage->greeting($greeting);
        }

        // Add main content line(s)
        $mailMessage->line($this->getMailContent());

        // Add call-to-action button
        $mailMessage->action($this->getMailActionText(), $this->getMailActionUrl());

        // Add footer line
        $mailMessage->line($this->getMailFooter());

        // Add optional salutation
        if ($salutation = $this->getMailSalutation()) {
            $mailMessage->salutation($salutation);
        }

        // Add optional attachments
        foreach ($this->getMailAttachments() as $attachment) {
            $mailMessage->attach($attachment['file'], $attachment['options'] ?? []);
        }

        // Add optional Markdown content
        if ($markdown = $this->getMailMarkdown()) {
            $mailMessage->markdown($markdown['view'], $markdown['data'] ?? []);
        }

        return $mailMessage;
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toNexmo($notifiable)
    {
        // return (new \Illuminate\Notifications\Messages)
        //     ->content($this->getSmsMessage());
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'id' => $this->notificationId,
            'avatarUrl' => $this->getAvatarUrl(),
            'type' => static::NOTIFICATION_TYPE,
            'category' => $this->getCategory(),
            'isUnRead' => true,
            'createdAt' => now()->toDateTimeString(),
            'title' => $this->getDatabaseTitle(),
            'message' => $this->getDatabaseMessage(),
            'url' => $this->getDatabaseUrl(),
        ];
    }


    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->notificationId,
            'avatarUrl' => $this->getAvatarUrl(),
            'type' => 'order',
            'category' => $this->getCategory(),
            'isUnRead' => true,
            'createdAt' => now()->toDateTimeString(),
            'title' => $this->getDatabaseTitle(),
            'message' => $this->getDatabaseMessage(),
            'url' => $this->getDatabaseUrl(),
            'user_id' => $notifiable->id,
        ]);
    }

    /**
     * Customize the data payload for the broadcast event.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificationId,
            'avatarUrl' => $this->getAvatarUrl(),
            'type' => static::NOTIFICATION_TYPE,
            'category' => $this->getCategory(),
            'isUnRead' => true,
            'createdAt' => now()->toDateTimeString(),
            'title' => $this->getDatabaseTitle(),
            'message' => $this->getDatabaseMessage(),
            'url' => $this->getDatabaseUrl(),
            'user_id' => $this->preferences->user_id,
        ];
    }


    /**
     * Define the channels the notification should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.' . $this->preferences->user_id),
        ];
    }

    /**
     * Customize the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }

    /**
     * Abstract methods to be implemented by child classes.
     */
    abstract protected function getAvatarUrl(): ?string;
    abstract protected function getCategory(): string;
    abstract protected function getDatabaseMessage(): string;
    abstract protected function getDatabaseTitle(): string;
    abstract protected function getDatabaseUrl(): string;
    abstract protected function getMailActionText(): string;
    abstract protected function getMailActionUrl(): string;
    abstract protected function getMailAttachments(): array;
    abstract protected function getMailContent(): string;
    abstract protected function getMailFooter(): string;
    abstract protected function getMailGreeting(): ?string;
    abstract protected function getMailMarkdown(): ?array;
    abstract protected function getMailSalutation(): ?string;
    abstract protected function getMailSubject(): string;
    abstract protected function getSmsMessage(): string;
}
