<?php

namespace App\ThirdParty\Factory;

use App\Domain\Entity\Entity;
use App\Domain\Entity\Note;
use App\Domain\Entity\User;
use App\Domain\Repository\Repository;
use App\Domain\Service\Factory\RepositoryFactory;
use App\ThirdParty\Persistence\Doctrine\Repository\NoteRepositoryImpl;
use App\ThirdParty\Persistence\Doctrine\Repository\UserRepositoryImpl;
use Doctrine\ORM\EntityManagerInterface;

class RepositoryFactoryImpl implements RepositoryFactory
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Entity $entity): Repository
    {
        $class = get_class($entity);

        switch ($class) {
            case Note::class:
                return new NoteRepositoryImpl($this->entityManager);
            case User::class:
                return new UserRepositoryImpl($this->entityManager);
        }

        throw new \UnexpectedValueException(
          'Unable to create repository for '. $class
        );
    }
}
