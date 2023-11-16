<?php

namespace App\Users\Resources\Notifications;

use App\Enums\NotificationProviderEnum;

class Email extends AbstractResource
{
    public function getProvider(): NotificationProviderEnum
    {
        return NotificationProviderEnum::EMAIL;
    }

    protected function fields(): void
    {
        //
    }
}
