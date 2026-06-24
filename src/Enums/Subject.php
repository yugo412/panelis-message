<?php

namespace Panelis\Message\Enums;

enum Subject: string
{
    case General = 'general';
    case FeatureRequest = 'feature_request';
    case Partnership = 'partnership';
    case Ads = 'ads';
    case Feedback = 'feedback';
    case Bug = 'bug';
    case Other = 'other';

    public function getLabel(): string
    {
        return __('message.subject_'.$this->value);
    }
}
