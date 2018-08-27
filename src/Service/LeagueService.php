<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\League;
use App\Entity\Team;
use App\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LeagueService
{
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     */
    public function deleteLeagueById(int $id): League
    {
        $leagueEntity = $this->getLeagueById($id);

        $this->em->remove($leagueEntity);
        $this->em->flush();

        return $leagueEntity;
    }

    /**
     * @param int $id
     * @return League
     * @throws EntityNotFoundException When league entity does not exist
     */
    public function getLeagueById(int $id): League
    {
        /** @var League $leagueEntity */
        $leagueEntity = $this->em->getRepository(League::class)->find($id);
        if (!$leagueEntity) {
            throw new EntityNotFoundException('League does not exist');
        }

        return $leagueEntity;
    }
}
