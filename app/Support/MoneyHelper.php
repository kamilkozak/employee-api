<?php

namespace App\Support;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class MoneyHelper
{
    public static function format(int $money): string
    {
        $money = new Money($money, new Currency(config('app.currency')));

        $moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $moneyFormatter->format($money);
    }
}
