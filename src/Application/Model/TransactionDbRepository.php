<?php

namespace App\Application\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TransactionDbRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @return \Doctrine\ORM\EntityRepository<Transaction>
     */
    private function getEshopRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Transaction::class);
    }
}
