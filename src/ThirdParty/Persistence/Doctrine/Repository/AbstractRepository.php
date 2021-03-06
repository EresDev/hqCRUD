<?php

namespace App\ThirdParty\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Entity\Entity;
use App\Domain\Repository\Repository;
use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository implements Repository
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(
            $entityManager,
            $entityManager->getClassMetadata($this->getEntityClass())
        );

        $this->entityManager = $entityManager;
    }
    
    abstract public function getEntityClass() : string;

    public function getById($id) : ?Entity
    {
        return $this->entityManager->find($this->getEntityClass(), $id);
    }

    public function getAll() : array
    {
        return $this->entityManager
            ->getRepository($this->getEntityClass())
            ->findAll();
    }

    public function save(Entity $entity) : void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete(int $entityId) : ?Entity
    {
        $entity = $this->getById($entityId);

        if($entity != null){
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function update(Entity $entity): void
    {
        $this->entityManager->merge($entity);
        $this->entityManager->flush();
    }
}
