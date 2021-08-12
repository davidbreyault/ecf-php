<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function searchProfile($settings)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u');

            if (!is_null($settings['skill']) || !is_null($settings['level'])) {
                $query = $query
                    ->innerJoin('u.skill', 's')
                    ->addSelect('s');
            }

            if (!is_null($settings['name'])) {
                $query = $query
                    ->where('u.lastname = :lastname')
                    ->setParameter('lastname', $settings['name']);
            }

            if (!is_null($settings['skill'])) {
                $query = $query
                    // ->innerJoin('u.skill', 's', 'WITH', 's.name = :name')
                    // ->addSelect('s')
                    ->andWhere('s.name = :name')
                    ->setParameter('name', $settings['skill']->getName());
            }

            if (!is_null($settings['level'])) {
                $query = $query
                    ->andWhere('s.level >= :level')
                    ->setParameter('level', $settings['level']);
            }

        return $query->getQuery()->getResult();
    }

    /**
     * Returns an array of User Candidate objects 
     */
    public function findAllCandidates()
    {
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.is_employed = :isEmployed')
            ->setParameter('isEmployed', 0);
        return $query->getQuery()->getResult();
    }

    /**
     * Returns an array of User Employee objects 
     */
    public function findAllEmployees()
    {
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.is_employed = :isEmployed')
            ->setParameter('isEmployed', 1);
        return $query->getQuery()->getResult();
    }

    
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
