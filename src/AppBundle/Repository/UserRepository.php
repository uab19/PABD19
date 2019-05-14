<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findIdByUsername($username) {
        return $this->getEntityManager()->createQuery(
            "SELECT user.id FROM AppBundle:User user WHERE user.email = :username")
        ->setParameter("username", $username)
        ->getSingleScalarResult();
    }
}
