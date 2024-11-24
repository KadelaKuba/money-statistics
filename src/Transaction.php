<?php

namespace App;

use Brick\Money\Money;

class Transaction implements \JsonSerializable
{
    private function __construct(
        public readonly string $categoryName,
        public readonly Money $amount,
        public readonly \DateTimeImmutable $date,
    ) {
    }

    public static function create(string $name, Money $amount, \DateTimeImmutable $date): self
    {
        return new self($name, $amount, $date);
    }

    /**
     * @return array{categoryName: string, amount: \Brick\Money\Money}
     */
    public function jsonSerialize(): array
    {
        return [
            'categoryName' => $this->categoryName,
            'amount' => $this->amount,
            'date' => $this->date->format('m'),
        ];
    }
}
