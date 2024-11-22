<?php

namespace MoneyStatistics;

use Brick\Money\Money;
use Brick\Money\MoneyBag;

class TransactionCategory implements \JsonSerializable
{
    /**
     * @param Transaction[] $transactions
     */
    public function __construct(
        public string $name,
        public string $monthName,
        public array $transactions = [],
    ) {
    }

    public function add(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function calculateTotalAmount(): MoneyBag
    {
        $totalAmount = new MoneyBag();

        foreach ($this->transactions as $transaction) {
            $totalAmount->add($transaction->amount);
        }

        return $totalAmount;
    }

    public function getTotalAmountFloat(): float
    {
        return $this->calculateTotalAmount()->getAmount('CZK')->abs()->toFloat();
    }

    public function getTotalAmountReadable(): string
    {
        return Money::of($this->calculateTotalAmount()->getAmount('CZK')->abs(), 'CZK')->formatTo('cs-CZ');
    }


    /**
     * @return array{totalAmount: float}
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'transactions' => $this->transactions,
            'totalAmount' => $this->getTotalAmountFloat(),
            'monthName' => $this->monthName,
        ];
    }
}
