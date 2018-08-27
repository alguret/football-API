<?php

namespace App\Service;

use App\Entity\League;
use App\Entity\Team;
use App\Exception\ValidationException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamService
{
    private $em;
    private $validator;
    private $leagueService;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, LeagueService $leagueService)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->leagueService = $leagueService;
    }

    /**
     * @param array $params
     * @return Team
     * @throws EntityNotFoundException
     * @throws ValidationException On validation errors
     */
    public function createTeam(array $params): Team
    {
        $leagueEntity = $this->leagueService->getLeagueById($params['leagueId']);

        $teamEntity = new Team();
        $teamEntity->setName($params['name']);
        $teamEntity->setStrip($params['strip']);
        $teamEntity->setLeague($leagueEntity);

        $errors = $this->validator->validate($teamEntity);

        if (count($errors) !== 0) {
            throw new ValidationException($errors);
        }

        $this->em->persist($teamEntity);
        $this->em->flush();

        return $teamEntity;
    }

    /**
     * @param int $teamId
     * @param array $data
     * @return Team
     * @throws EntityNotFoundException
     * @throws ValidationException On validation errors
     */
    public function updateTeam(int $teamId, array $data): Team
    {
        $repository = $this->em->getRepository(Team::class);

        /** @var League $leagueEntity */
        $teamEntity = $repository->find($teamId);
        if (!$teamEntity) {
            throw new EntityNotFoundException('Team does not exist');
        }

        $teamEntity->setName($data['name']);
        $teamEntity->setStrip($data['strip']);

        $errors = $this->validator->validate($teamEntity);

        if (count($errors) !== 0) {
            throw new ValidationException($errors);
        }

        $this->em->persist($teamEntity);
        $this->em->flush();

        return $teamEntity;
    }

    /**
     * @param int $leagueId
     * @return array
     * @throws EntityNotFoundException
     */
    public function getTeamsByLeagueId(int $leagueId): Collection
    {
        $league = $this->leagueService->getLeagueById($leagueId);

        return $league->getTeams();
    }
}
