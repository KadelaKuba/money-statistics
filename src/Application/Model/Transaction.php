<?php

namespace App\Application\Model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'transactions')]
class Transaction
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id;

    #[Column(type: 'string')]
    private string $direction;

    #[Column(type: 'string')]
    private string $category;

    #[Column(type: 'string')]
    private string $currency;

    #[Column(type: 'string')]
    private string $amount;

    #[Column(name: 'execution_date', type: 'datetime')]
    private DateTimeImmutable $executionDate;

    private function __construct(
        int $id,
        string $direction,
        string $category,
        string $currency,
        string $amount,
        DateTimeImmutable $executionDate
    ) {
        $this->id = $id;
        $this->direction = $direction;
        $this->category = $category;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->executionDate = $executionDate;
    }

    public static function create(
        int $id,
        string $direction,
        string $category,
        string $currency,
        string $amount,
        DateTimeImmutable $executionDate
    ): Transaction {
        return new self(
            $id,
            $direction,
            $category,
            $currency,
            $amount,
            $executionDate
        );
    }
}
