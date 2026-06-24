<?php

namespace Panelis\Message\Panel\Resources\MessageResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MessageStatus: string implements HasColor, HasLabel
{
    case Unread = 'unread';

    case Read = 'read';

    case Replied = 'replied';

    case Resolved = 'resolved';

    case Spam = 'spam';

    public static function options(): array
    {
        return collect(MessageStatus::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->getLabel()])
            ->toArray();
    }

    public function getLabel(): string
    {
        return __(sprintf('message.status_%s', $this->value));
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Spam => 'warning',
            self::Replied => 'success',
            self::Read => 'primary',

            default => 'info',
        };
    }
}
