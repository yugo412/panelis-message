<?php

namespace Panelis\Message\Panel\Resources\MessageResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Panelis\Message\Mail\SendMail;
use Panelis\Message\Models\Message;
use Panelis\Message\Panel\Resources\MessageResource;
use Panelis\Message\Panel\Resources\MessageResource\Enums\MessageStatus;
use Panelis\Message\Panel\Resources\MessageResource\Forms\SendForm;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send')
                ->label(__('message.btn.submit'))
                ->form(SendForm::schema())
                ->action(function (array $data): void {
                    foreach ($data['emails'] as $email) {
                        Mail::to($email)
                            ->send(new SendMail($data['body'], $data['subject']));
                    }
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('message.tab_all'))
                ->icon('heroicon-o-envelope-open')
                ->badge(
                    Message::query()
                        ->where('status', '!=', MessageStatus::Spam)
                        ->count()
                )
                ->modifyQueryUsing(function (Builder $query): Builder {
                    return $query->where('status', '!=', MessageStatus::Spam);
                }),

            'unread' => Tab::make(__('message.tab_unread'))
                ->icon('heroicon-o-envelope')
                ->badge(Message::unread()->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->unread()),

            'spam' => Tab::make(__('message.tab_spam'))
                ->icon('heroicon-o-exclamation-triangle')
                ->badge(Message::spam()->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->spam()),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        if (Message::unread()->count() >= 1) {
            return 'unread';
        }

        return 'all';
    }
}
