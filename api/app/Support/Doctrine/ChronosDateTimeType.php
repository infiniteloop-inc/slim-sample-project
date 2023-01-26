<?php

namespace App\Support\Doctrine;

use Cake\Chronos\Chronos;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class ChronosDateTimeType extends DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        /** @var DateTimeInterface */
        $dateTime = parent::convertToPHPValue($value, $platform);

        return Chronos::instance($dateTime);
    }
}
