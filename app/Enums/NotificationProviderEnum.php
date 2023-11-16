<?php

namespace App\Enums;

use NormanHuth\Helpers\Traits\EnumTrait;

enum NotificationProviderEnum: int
{
    use EnumTrait;

    case EMAIL = 0;
    case WEBHOOK = 1;
    //case TELEGRAM = 2;
    //case MS_TEAMS = 3;
    //case SLACK = 4;
    //case DISCORD = 5;

    /**
     * Return label for enum case.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::EMAIL => __('Email'),
            self::WEBHOOK => __('Webhook'),
            //self::TELEGRAM => __('Telegram'),
            //self::MS_TEAMS => __('MS Teams'),
            //self::SLACK => __('Slack'),
            //self::DISCORD => __('Discord'),
        };
    }
}
