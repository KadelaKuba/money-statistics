<?php

namespace MoneyStatistics;

class TransactionsMapper
{
    /**
     * @param \Iterator $transactions
     * @return TransactionCategory[][]
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
            $month = $transactionDto->getDate()->format('m');

            $transaction = Transaction::create(
                $categoryName,
                $transactionDto->getMoney(),
                $transactionDto->getDate(),
            );

            if (array_key_exists($month, $transactionCategories) === false || array_key_exists($transaction->categoryName, $transactionCategories[$month]) === false) {
                $transactionCategories[$month][$categoryName] = new TransactionCategory($categoryName, $transactionDto->getDate()->format('F'));
            }

            $transactionCategories[$month][$categoryName]->add($transaction);
        }

        return $transactionCategories;
    }
}
