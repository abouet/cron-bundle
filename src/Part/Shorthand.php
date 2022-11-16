<?php

namespace Abouet\Cron\Part;

enum Shorthand: string {

    case Annually = '@annually';
    case Daily = '@daily';
    case Hourly = '@hourly';
    case Midnight = '@midnight';
    case Monthly = '@monthly';
    case Weekly = '@weekly';
    case Yearly = '@yearly';
    case Sunday = 'SUN';
    case Monday = 'MON';
    case Tuesday = 'TUE';
    case Wednesday = 'WED';
    case Thursday = 'THU';
    case Friday = 'FRI';
    case Saturday = 'SAT';
    case January = 'JAN';
    case February = 'FEB';
    case March = 'MAR';
    case April = 'APR';
    case May = 'MAY';
    case June = 'JUN';
    case July = 'JUL';
    case August = 'AUG';
    Case Septembre = 'SEP';
    case October = 'OCT';
    case November = 'NOV';
    case December = 'DEC';

    public function equivalent(): string {
        return match ($this) {
            static::Annually => '0 0 1 1 * *',
            static::Daily => '0 0 * * *',
            static::Hourly => '0 * * * *',
            static::Midnight => '0 0 * * *',
            static::Monthly => '0 0 1 * *',
            static::Weekly => '0 0 * * 0',
            static::Yearly => '0 0 1 1 * *',
            static::Sunday => '0',
            static::Monday => '1',
            static::Tuesday => '2',
            static::Wednesday => '3',
            static::Thursday => '4',
            static::Friday => '5',
            static::Saturday => '6',
            static::January => '1',
            static::February => '2',
            static::March => '3',
            static::April => '4',
            static::May => '5',
            static::June => '6',
            static::July => '7',
            static::August => '8',
            static::September => '9',
            static::October => '10',
            static::November => '11',
            static::December => '12',
        };
    }

    static public function toArray(): array {
        return [self::Annually,
            self::Daily,
            self::Hourly,
            self::Midnight,
            self::Monthly,
            self::Weekly,
            self::Yearly];
    }

}
