<?php

namespace Panelis\Message\Panel\Resources\MessageResource\Forms;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;

class SendForm
{
    public static function schema(): array
    {
        return [
            TagsInput::make('emails')
                ->label(__('message.email'))
                ->unique()
                ->required(),

            TextInput::make('subject')
                ->label(__('message.subject'))
                ->required(),

            MarkdownEditor::make('body')
                ->label(__('message.body'))
                ->required(),
        ];
    }
}
