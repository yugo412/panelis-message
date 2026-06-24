<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Panelis\Message\Livewire\Pages\Contact;

Route::livewire('/contact', Contact::class)
    ->middleware('check_ip')
    ->name('message.contact');
