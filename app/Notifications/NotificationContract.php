<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

interface NotificationContract
{
    public function getAvatarUrl(): ?string;

    public function getCategory(): string;

    public function getDatabaseMessage(): string;

    public function getDatabaseTitle(): string;

    public function getDatabaseUrl(): string;

    public function getMailBody(MailMessage $mail): void;

    public function getMailSubject(): string;

    public function getMessageText(): string;

    public function getSmsMessage(): string;
}
