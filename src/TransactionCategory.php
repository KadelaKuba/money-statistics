<?php

namespace MoneyStatistics;

use Brick\Money\MoneyBag;

class TransactionCategory implements \JsonSerializable
{
    /**
     * @var Transaction[]
     */
    public array $transactions = [];

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


    public function jsonSerialize(): array
    {
        return [
//            'transactions' => $this->transactions,
            'totalAmount' => $this->calculateTotalAmount()->getAmount('CZK')->toBigDecimal()->abs()->toFloat()
        ];
    }
}