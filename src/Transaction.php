<?php

namespace MoneyStatistics;

use Brick\Money\Money;

class Transaction implements \JsonSerializable
{

    private function __construct(
        public readonly string $categoryName,
        public readonly Money  $amount,
    ) {}

    public static function create(string $name, Money $amount): self
    {
        return new self($name, $amount);
    }

    public function jsonSerialize(): array
    {
        return [
            'categoryName' => $this->categoryName,
            'amount' => $this->amount,
        ];
    }
}