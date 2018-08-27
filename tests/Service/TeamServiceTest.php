<?php

namespace App\Tests\Service;

use App\Entity\League;
use App\Entity\Team;
use App\Exception\ValidationException;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use App\Service\LeagueService;
use App\Service\TeamService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamServiceTest extends KernelTestCase
{
    /**
     * @var EntityManager|MockObject
     */
    private $em;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    /**
     * @var LeagueService|MockObject
     */
    private $leagueService;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();

        $this->em = $this->createMock(EntityManager::class);
        $this->leagueService = $this->createMock(LeagueService::class);
        $this->validator = self::$container->get('validator');
    }

    public function testCreateTeam(): void
    {
        $this->leagueService->expects($this->once())->method('getLeagueById')->willReturn(new League());

        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->createTeam([
            'leagueId' => 1,
            'name' => 'Team name',
            'strip' => 'strip',
        ]);
    }

    public function testCreateTeamWithNotValidData(): void
    {
        $leagueRepository = $this->createMock(LeagueRepository::class);
        $teamRepository = $this->createMock(TeamRepository::class);

        $this->leagueService->expects($this->once())->method('getLeagueById')->willReturn(new League());
        $this->expectException(ValidationException::class);

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->createTeam([
            'leagueId' => 1,
            'name' => '',
            'strip' => '',
        ]);
    }

    public function testUpdateTeamWithNotValidData(): void
    {
        $teamRepository = $this->createMock(TeamRepository::class);

        $this->em->expects($this->any())->method('getRepository')->willReturn($teamRepository);
        $teamRepository->expects($this->any())->method('find')->willReturn(new Team());

        $this->expectException(ValidationException::class);

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->updateTeam(1, [
            'name' => '',
            'strip' => '',
        ]);
    }

    public function testCreateTeamMissingLeague(): void
    {
        $this->leagueService->expects($this->once())->method('getLeagueById')->willThrowException(new EntityNotFoundException());

        $this->expectException(EntityNotFoundException::class);

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->createTeam([
            'leagueId' => 1,
            'name' => '',
            'strip' => '',
        ]);
    }

    public function testUpdateNotExistedTeam(): void
    {
        $teamRepository = $this->createMock(TeamRepository::class);

        $this->em->expects($this->once())->method('getRepository')->willReturn($teamRepository);
        $teamRepository->expects($this->once())->method('find')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->updateTeam(1, [
            'name' => 'name',
            'strip' => 'strip',
        ]);
    }

    public function testUpdateTeam(): void
    {
        $teamRepository = $this->createMock(TeamRepository::class);

        $this->em->expects($this->any())->method('getRepository')->willReturn($teamRepository);
        $teamRepository->expects($this->any())->method('find')->willReturn(new Team());
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $service->updateTeam(1, [
            'name' => 'New team name',
            'strip' => 'strip',
        ]);
    }

    public function testGetLeagueTeams(): void
    {
        $leagueEntity = new League();
        $leagueEntity->setTeams([new Team(), new Team(), new Team()]);

        $this->leagueService->expects($this->once())->method('getLeagueById')->willReturn($leagueEntity);

        $service = new TeamService($this->em, $this->validator, $this->leagueService);
        $this->assertCount($leagueEntity->getTeams()->count(), $service->getTeamsByLeagueId(1));
    }
}
