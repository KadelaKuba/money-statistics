<?php

namespace MoneyStatistics;

use Brick\Money\Money;

class TransactionDto
{

    public function __construct(
        public string $date,
        public string $direction,
        public string $category,
        public string $currency,
        public string $amount,
    ) {
    }

    public function getDate(): string
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