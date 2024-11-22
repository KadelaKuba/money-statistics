<?php

namespace MoneyStatistics;

class TransactionsMapper
{
    /**
     * @param \Iterator $transactions
     * @return TransactionCategory[]
     */
    public function getTransactionsByCategory(\Iterator $transactions): array
    {
        $transactionCategories = [];

        /** @var TransactionDto[] $transactions */
        foreach ($transactions as $transactionDto) {
            if ($transactionDto->getDirection() === 'Příchozí') {
                continue;
            }

            $categoryName = $transactionDto->getCategory();

            $transaction = Transaction::create(
                $categoryName,
                $transactionDto->getMoney()
            );

            if (array_key_exists($transaction->categoryName, $transactionCategories) === false) {
                $transactionCategories[$categoryName] = new TransactionCategory();
            }

            $transactionCategories[$categoryName]->add($transaction);
        }

        return $transactionCategories;
    }
}
