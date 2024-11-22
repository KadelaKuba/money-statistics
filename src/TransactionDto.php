<?php

namespace MoneyStatistics;

use Brick\Money\Money;
use League\Csv\Serializer\CastToDate;
use League\Csv\Serializer\MapCell;

class TransactionDto
{
    public function __construct(
        #[MapCell(
            column: 'date',
            cast: CastToDate::class,
            options: [
                'format' => '!d/m/Y',
            ],
            trimFieldValueBeforeCasting: true
        )]
        public \DateTimeImmutable $date,
        public string $direction,
        public string $category,
        public string $currency,
        public string $amount,
    ) {
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getMoney(): Money
    {
        return Money::of(str_replace(',', '.', $this->amount), $this->currency);
    }
}
