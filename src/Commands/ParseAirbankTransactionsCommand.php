<?php

namespace MoneyStatistics\Commands;

use League\Csv\Reader;
use MoneyStatistics\TransactionCategory;
use MoneyStatistics\TransactionDto;
use MoneyStatistics\TransactionsMapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:parse-airbank-transactions')]
class ParseAirbankTransactionsCommand extends Command
{
    private const string TRANSACTION_FILE_PATH = __DIR__ . '/../../var/data/airbank_utf8_2024.csv';

    public function __construct(
        private TransactionsMapper $transactionsMapper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $csvReader =
            Reader::createFromPath(self::TRANSACTION_FILE_PATH)
                ->setHeaderOffset(0)
                ->setDelimiter(';');

        $headerColumnOffsetMapper = [
          0 => 'date',
          1 => 'direction',
          2 => 'type',
          3 => 'category',
          4 => 'currency',
          5 => 'amount',
        ];

        $csvRecords = $csvReader->getRecordsAsObject(TransactionDto::class, $headerColumnOffsetMapper);
        $transactions = $this->transactionsMapper->getTransactionsByCategory($csvRecords);

        $transactions = $this->orderTransactions($transactions);

        $io->title('Přehled výdajů za rok 2024');
        foreach ($transactions as $month => $monthTransaction) {
            $io->section(\DateTimeImmutable::createFromFormat('!m', $month)->format('F'));

            foreach ($monthTransaction as $transactionCategory) {
                $io->write($transactionCategory->name . ' - ');
                $io->write($transactionCategory->getTotalAmountReadable());
                $io->newLine();
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @param TransactionCategory[][] $transactionsByCategory
     * @return TransactionCategory[][]
     */
    public function orderTransactions(array $transactionsByCategory): array
    {
        uksort($transactionsByCategory, function ($item1, $item2) {
            return strcasecmp($item1, $item2);
        });

        //        foreach ($transactionsByCategory as $transactions) {
        //            usort($transactions, function($item1, $item2) {
        //                return $item2->getTotalAmountFloat() <=> $item1->getTotalAmountFloat();
        //            });
        //        }

        return $transactionsByCategory;
    }
}
