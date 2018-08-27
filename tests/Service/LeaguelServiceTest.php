<?php

namespace App\Tests\Service;

use App\Entity\League;
use App\Entity\Team;
use App\Exception\ValidationException;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use App\Service\LeagueService;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LeagueServiceTest extends KernelTestCase
{
    /**
     * @var EntityManager|MockObject
     */
    private $em;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();

        $this->em = $this->createMock(EntityManager::class);
        $this->validator = self::$container->get('validator');
    }

    public function testDeleteLeagueById(): void
    {
        $leagueRepository = $this->createMock(LeagueRepository::class);

        $this->em->expects($this->at(0))->method('getRepository')->willReturn($leagueRepository);
        $this->em->expects($this->at(1))->method('getRepository')->willReturn($leagueRepository);
        $leagueRepository->expects($this->any())->method('find')->willReturn(new League());
        $this->em->expects($this->once())->method('remove');
        $this->em->expects($this->once())->method('flush');

        $service = new LeagueService($this->em, $this->validator);
        $this->assertNull($service->deleteLeagueById(1)->getId());
    }

    public function testGetLeagueByExistedId(): void
    {
        $leagueRepository = $this->createMock(LeagueRepository::class);

        $leagueEntity = new League();

        $this->em->expects($this->once())->method('getRepository')->willReturn($leagueRepository);
        $leagueRepository->expects($this->once())->method('find')->willReturn($leagueEntity);

        $service = new LeagueService($this->em, $this->validator);
        $this->assertEquals($leagueEntity, $service->getLeagueById(1));
    }

    public function testGetLeagueByNotExistedId(): void
    {
        $leagueRepository = $this->createMock(LeagueRepository::class);

        $this->em->expects($this->once())->method('getRepository')->willReturn($leagueRepository);
        $leagueRepository->expects($this->once())->method('find')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);

        $service = new LeagueService($this->em, $this->validator);
        $service->getLeagueById(1);
    }
}
