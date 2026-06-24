# Panelis Message

Manage contact messages directly from the Panelis admin panel.

## Features

* Contact message management
* Read and unread status
* Message replies
* Spam management
* Message archive
* Automatic Panelis plugin discovery

## Requirements

* PHP 8.3+
* Laravel 13+
* Filament 5+

## Installation

Install the package via Composer:

```bash
composer require yugo/panelis-message
```

Run migrations:

```bash
php artisan migrate
```

## Usage

After installation, a **Messages** menu will be available in the Panelis admin panel.

The Message module provides a simple inbox for managing messages submitted from contact forms or other communication channels.

Available actions include:

* View messages
* Mark messages as read
* Mark messages as spam
* Archive messages
* Reply to messages

Typical message information includes:

* Name
* Email address
* Subject
* Message content
* Submission date

## Integration

The Message module can be integrated with contact forms, support requests, and other message submission workflows.

## License

The MIT License (MIT).
