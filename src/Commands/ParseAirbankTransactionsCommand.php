<?php

namespace MoneyStatistics\Commands;

use League\Csv\Reader;
use MoneyStatistics\TransactionsMapper;
use MoneyStatistics\TransactionDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse-airbank-transactions')]
class ParseAirbankTransactionsCommand extends Command
{
    private const string TRANSACTION_FILE_PATH = __DIR__ . '/../var/data/utf-8-airbank_1726744011_2024-11-15_22-52.csv';

    public function __construct(
        private TransactionsMapper $transactionsMapper,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
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
        $transactionsByCategory = $this->transactionsMapper->getTransactionsByCategory($csvRecords);
        error_log(json_encode($transactionsByCategory, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}