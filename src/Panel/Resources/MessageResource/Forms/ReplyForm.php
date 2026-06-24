<?php

namespace Panelis\Message\Panel\Resources\MessageResource\Forms;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Panelis\Message\Models\Message;

class ReplyForm
{
    /**
     * @return array<int,mixed>
     */
    public static function schema(): array
    {
        return [
            Placeholder::make('name')
                ->label(__('message.name'))
                ->content(fn (Message $record): string => $record->name),

            Placeholder::make('email')
                ->label(__('message.email'))
                ->content(fn (Message $record): ?string => $record->email),

            TextInput::make('subject')
                ->label(__('message.subject'))
                ->default(fn (Message $record): string => 'Re: '.$record->subject)
                ->required(),

            MarkdownEditor::make('body')->label(__('message.body'))->required(),

            Toggle::make('quote_message')
                ->label(__('message.quote_original'))
                ->default(true),
        ];
    }
}
