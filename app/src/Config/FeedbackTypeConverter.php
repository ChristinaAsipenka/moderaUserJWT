<?php

namespace App\Config;

use App\DoctrineType\AbstractEnumType;

class FeedbackTypeConverter extends AbstractEnumType
{
    public const NAME = 'status';

    public function getName(): string // the name of the type.
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string // the enums class to convert
    {
        return FeedbackType::class;
    }
}
