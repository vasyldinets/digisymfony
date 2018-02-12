<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }


    public function getTransactionPaginated($page, $per_page, $where, $user_id) {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT p FROM App\Entity\Transaction p WHERE p.sellerId=".$user_id.$where);
        $query->setFirstResult(($page-1) * $per_page)->setMaxResults($per_page);

        return new Paginator($query);
    }

}
