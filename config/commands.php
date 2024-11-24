<?php

declare(strict_types=1);

use App\Commands\ParseAirbankTransactionsCommand;

return [
   ParseAirbankTransactionsCommand::getDefaultName() => ParseAirbankTransactionsCommand::class,
];
