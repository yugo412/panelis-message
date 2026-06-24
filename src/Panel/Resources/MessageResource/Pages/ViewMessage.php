<?php

namespace Panelis\Message\Panel\Resources\MessageResource\Pages;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Mail;
use Panelis\Message\Mail\ReplyMail;
use Panelis\Message\Models\Message;
use Panelis\Message\Panel\Resources\MessageResource;
use Panelis\Message\Panel\Resources\MessageResource\Enums\MessageStatus;
use Panelis\Message\Panel\Resources\MessageResource\Forms\ReplyForm;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label(__('message.btn.reply'))
                ->disabled(fn (Message $record): bool => empty($record->email))
                ->form(ReplyForm::schema())
                ->action(function (Message $message, array $data): void {
                    $message->status = MessageStatus::Replied;
                    $message->save();

                    Mail::to($message->email)
                        ->send(new ReplyMail(
                            message: $message,
                            body: $data['body'],
                            subject: $data['subject'],
                            quoteMessage: $data['quote_message'] ?? true,
                        ));

                    Notification::make('reply_sent')
                        ->title(__('message.reply_sent'))
                        ->success()
                        ->send();
                }),

            Action::make('mark_as_spam')
                ->visible(fn (Message $message): bool => $message->status !== MessageStatus::Spam)
                ->label(__('message.btn.mark_spam'))
                ->action(function (Message $message): void {
                    $message->markAsSpam();

                    Notification::make('marked_as_spam')
                        ->title(__('message.marked_as_spam'))
                        ->success()
                        ->send();
                }),

            Action::make('ummark_as_spam')
                ->visible(fn (Message $message): bool => $message->status === MessageStatus::Spam)
                ->label(__('message.btn.unmark_spam'))
                ->action(function (Message $message): void {
                    // alias for not spam
                    $message->markAsRead();

                    Notification::make('marked_as_spam')
                        ->title(__('message.unmarked_as_spam'))
                        ->success()
                        ->send();
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        $message = self::getRecord();
        if ($message->status === MessageStatus::Unread) {
            $message->markAsRead();
        }

        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('subject')
                            ->hiddenLabel()
                            ->default(__('message.no_subject'))
                            ->size(TextSize::Large),

                        TextEntry::make('body')
                            ->hiddenLabel()
                            ->markdown(),
                    ]),

                Section::make()
                    ->columnSpan(1)
                    ->collapsible()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('message.name')),

                        TextEntry::make('email')
                            ->label(__('message.email'))
                            ->default('-'),

                        TextEntry::make('created_at')
                            ->label(__('ui.created_at'))
                            ->since(),

                        TextEntry::make('status')
                            ->label(__('message.status'))
                            ->badge(),
                    ]),
            ]);
    }
}
