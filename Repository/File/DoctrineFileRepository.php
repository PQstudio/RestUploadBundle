<?php
namespace PQstudio\RestUploadBundle\Repository\File;

use PQstudio\RestUploadBundle\Entity\File;
use Doctrine\ORM\EntityRepository;

class DoctrineFileRepository
{
    protected $em;

    protected $repo;

    protected $acl;

    public function __construct($em, $repo, $acl)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->acl = $acl;
    }

    public function find($fileId)
    {
        return $this->repo->find($fileId);
    }

    public function findByUniqueId($uniqueId)
    {
        $query = $this->em->createQuery(
           'SELECT p
            FROM PQstudio\RestUploadBundle\Entity\File p
            WHERE p.uniqueId = :uniqueId
            '
        )->setParameter('uniqueId', $uniqueId);

        $file = $query->getOneOrNullResult();
        return $file;
    }

    public function deleteOlderThan($timeInMinutes)
    {
        $time = new \DateTime('-'.$timeInMinutes.' minutes');
        $query = $this->em->createQuery(
           'DELETE
            PQstudio\RestUploadBundle\Entity\File p
            WHERE :time > p.createdAt
            '
        )
        ->setParameter('time', $time);

        $temporaryFilesDeleted = $query->getResult();
        return $temporaryFilesDeleted;
    }

    public function findOlderThan($timeInMinutes)
    {
        $time = new \DateTime('-'.$timeInMinutes.' minutes');
        $query = $this->em->createQuery(
           'SELECT p
            FROM PQstudio\RestUploadBundle\Entity\File p
            WHERE :time > p.createdAt
            '
        )
        ->setParameter('time', $time);

        $temporaryFilesDeleted = $query->getResult();
        return $temporaryFilesDeleted;
    }

    public function add(File $file)
    {
        $this->em->persist($file);
        $this->em->flush();

        return $this;
    }

    public function update(File $file)
    {
        $this->em->persist($file);
        $this->em->flush();

        return $this;
    }

    public function remove(File $file)
    {
        $this->em->remove($file);
        $this->em->flush();

        return $this;
    }
}
