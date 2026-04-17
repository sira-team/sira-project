<?php

declare(strict_types=1);

namespace Modules\Camp\Enums;

use Filament\Support\Contracts\HasLabel;

enum FormFieldType: string implements HasLabel
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Number = 'number';
    case Email = 'email';
    case Phone = 'phone';
    case Date = 'date';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case Boolean = 'boolean';
    case Section = 'section';
    case Repeater = 'repeater';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => __('Text'),
            self::Textarea => __('Textarea'),
            self::Number => __('Number'),
            self::Email => __('Email'),
            self::Phone => __('Phone'),
            self::Date => __('Date'),
            self::Select => __('Select (dropdown)'),
            self::Radio => __('Radio (single choice)'),
            self::Checkbox => __('Checkbox (multiple choice)'),
            self::Boolean => __('Yes / No'),
            self::Section => __('Section heading'),
            self::Repeater => __('Repeater (multiple persons)'),
        };
    }

    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::Radio, self::Checkbox]);
    }

    public function isStructural(): bool
    {
        return in_array($this, [self::Section, self::Repeater]);
    }
}
