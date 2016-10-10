<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 09.
 * Time: 20:49
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrderSummaryRepository extends EntityRepository
{
    public function findOrdersByUser($user)
    {
        return $this->createQueryBuilder('os')
            ->leftJoin('os.selections', 'sel')
            ->addSelect('sel')
            ->leftJoin('sel.product', 'prod')
            ->addSelect('prod')
            ->andWhere('os.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('os.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}