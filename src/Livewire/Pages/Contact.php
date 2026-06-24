<?php

namespace Panelis\Message\Livewire\Pages;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Panelis\Access\Traits\Cloudflare\UseTurnstile;
use Panelis\Message\Enums\Subject;
use Panelis\Message\Models\Message;
use Panelis\Message\Notifications\ReceivedNotification;
use Panelis\User\Models\User;

class Contact extends Component
{
    use UseTurnstile;

    #[Validate('required|min:3|max:50')]
    public string $name;

    #[Validate('nullable|email')]
    public ?string $email;

    #[Validate('required|string')]
    public string $subject;

    #[Validate('required|min:3')]
    public ?string $body;

    public string $turnstileToken = '';

    public function mount(): void
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    public function send(): void
    {
        if (Auth::guest()) {
            $this->validateTurnstileToken($this->turnstileToken);
        }

        $message = Message::query()->create($this->validate());

        Notification::send(
            notifiables: User::query()->whereDoesntHave('roles')->get(),
            notification: new ReceivedNotification($message),
        );

        $this->reset(Auth::check() ? ['body', 'turnstileToken'] : []);

        session()->flash('message', __('message.sent_success'));
    }

    public function render(): Renderable
    {
        seo()->title(__('message.contact'), false)
            ->description(__('message.intro'))
            ->openGraphSite(config('app.name'));

        return view('message::livewire.pages.contact')
            ->extends('layouts.daisy')
            ->with('subjects', Subject::cases())
            ->with('title', __('message.contact'));
    }
}
