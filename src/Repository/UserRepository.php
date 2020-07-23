<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);
        $this->em = $manager;
    }


    public function createUser(User $user) : bool
    {
        $now = new \DateTime();
        $user
            ->setCreationdate($now)
            ->setUpdatedate($now);

        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function editUser( User $user) : bool
    {
        $now = new \DateTime();
        $user
            ->setUpdatedate($now);

        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function deleteUser( $id ) : bool
    {
        $user = $this->find($id);
        if($user == null){
            return false;
        }
        $this->em->remove($user);
        $this->em->flush();
        return true;
    }


}
