<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 06.
 * Time: 3:31
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function search($keyword)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :keyword')
            ->orWhere('p.description LIKE :keyword')
            ->orWhere('p.price LIKE :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }
}