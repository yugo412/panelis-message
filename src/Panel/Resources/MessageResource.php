<?php

namespace Panelis\Message\Panel\Resources;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Panelis\Message\Models\Message;
use Panelis\Message\Panel\Resources\MessageResource\Enums\MessageStatus;
use Panelis\Message\Panel\Resources\MessageResource\Pages\CreateMessage;
use Panelis\Message\Panel\Resources\MessageResource\Pages\EditMessage;
use Panelis\Message\Panel\Resources\MessageResource\Pages\ListMessages;
use Panelis\Message\Panel\Resources\MessageResource\Pages\ViewMessage;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    public static function getLabel(): ?string
    {
        return __('message.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('message.navigation');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        $unread = function (Message $message): FontWeight {
            if ($message->status === MessageStatus::Unread) {
                return FontWeight::Bold;
            }

            return FontWeight::Light;
        };

        return $table
            ->recordUrl(function (Message $message): string {
                return ViewMessage::getUrl([$message->id]);
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('status')
                    ->label(__('message.status'))
                    ->badge()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label(__('message.name'))
                    ->description(fn (Message $message): ?string => $message->email)
                    ->weight(fn (Message $message): FontWeight => $unread($message))
                    ->grow(false)
                    ->searchable(),

                TextColumn::make('subject')
                    ->label(__('message.subject'))
                    ->default(__('message.no_subject'))
                    ->weight(fn (Message $message): FontWeight => $unread($message))
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('body')
                    ->label(__('message.body'))
                    ->toggleable()
                    ->words(8)
                    ->searchable(),

                TextColumn::makeSinceDate('created_at', __('ui.created_at')),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('message.status'))
                    ->options(MessageStatus::options()),

                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name')
                            ->label(__('message.name')),

                        TextConstraint::make('email')
                            ->label(__('message.email')),

                        TextConstraint::make('subject')
                            ->label(__('message.subject')),

                        TextConstraint::make('body')
                            ->label(__('message.body')),

                        DateConstraint::make('created_at')
                            ->label(__('message.sent_at')),
                    ]),
            ])
            ->recordActions([
                Action::make('mark_as_read')
                    ->label(__('message.btn.mark_read'))
                    ->visible(fn (Message $message): bool => $message->status === MessageStatus::Unread)
                    ->icon('heroicon-o-envelope-open')
                    ->action(function (Message $message): void {
                        $message->markAsRead();

                        Notification::make('marked_as_read')
                            ->title(__('message.marked_as_read'))
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    Action::make('mark_as_unread')
                        ->label(__('message.btn.mark_unread'))
                        ->visible(fn (Message $message): bool => $message->status === MessageStatus::Read)
                        ->icon('heroicon-o-envelope')
                        ->action(function (Message $message): void {
                            $message->markAsUnread();

                            Notification::make('marked_as_unread')
                                ->title(__('message.marked_as_unread'))
                                ->success()
                                ->send();
                        }),

                    Action::make('mark_as_spam')
                        ->label(__('message.btn.mark_spam'))
                        ->visible(fn (Message $message): bool => $message->status !== MessageStatus::Spam)
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->action(function (Message $message): void {
                            $message->markAsSpam();

                            Notification::make('marked_as_spam')
                                ->title(__('message.marked_as_spam'))
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_as_read')
                        ->label(__('message.btn.mark_read'))
                        ->icon('heroicon-o-envelope-open')
                        ->action(function (Collection $records): void {
                            $records->each->markAsRead();

                            Notification::make('marked_as_read')
                                ->title(__('message.marked_as_read'))
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('mark_as_unread')
                        ->label(__('message.btn.mark_unread'))
                        ->icon('heroicon-o-envelope')
                        ->action(function (Collection $records): void {
                            $records->each->markAsUnread();

                            Notification::make('marked_as_unread')
                                ->title(__('message.marked_as_unread'))
                                ->success()
                                ->send();
                        }),

                    BulkAction::make('mark_as_spam')
                        ->label(__('message.btn.mark_spam'))
                        ->requiresConfirmation()
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->action(function (Collection $records): void {
                            $records->each->markAsSpam();

                            Notification::make('marked_as_spam')
                                ->title(__('message.marked_as_spam'))
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMessages::route('/'),
            'create' => CreateMessage::route('/create'),
            'edit' => EditMessage::route('/{record}/edit'),
            'view' => ViewMessage::route('/{record}'),
        ];
    }
}
