<?php

namespace App\DataFixtures;

use App\Entity\League;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $leaguesData = [
            [
                'name' => 'English Premier League',
                'teams' => [
                    [
                        'name' => 'Liverpool FC',
                        'strip' => '',
                    ],
                    [
                        'name' => 'Manchester City FC',
                        'strip' => '',
                    ],
                    [
                        'name' => 'AFC Bournemouth',
                        'strip' => '',
                    ],
                    [
                        'name' => 'Chelsea FC',
                        'strip' => '',
                    ],
                    [
                        'name' => 'Watford FC',
                        'strip' => '',
                    ],
                    [
                        'name' => 'Tottenham Hotspur FC',
                        'strip' => '',
                    ],
                    [
                        'name' => 'Leicester City FC',
                        'strip' => '',
                    ],
                ]
            ],
            [
                'name' => 'Ukrainian Premier League',
                'teams' => [
                    [
                        'name' => 'FC Shakhtar Donetsk',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Olexandriya',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Dynamo Kyiv',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Chornomorets Odesa',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Zorya Luhansk',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Olimpik Donetsk',
                        'strip' => '',
                    ],
                    [
                        'name' => 'FC Vorskla Poltava',
                        'strip' => '',
                    ],
                ]
            ],
        ];

        foreach ($leaguesData as $leagueData) {
            $league = new League();
            $league->setName($leagueData['name']);
            $manager->persist($league);
            foreach ($leagueData['teams'] as $teamData) {
                $team = new Team();
                $team->setName($teamData['name']);
                $team->setStrip($teamData['strip']);
                $team->setLeague($league);
                $manager->persist($team);
            }
        }

        $manager->flush();
    }
}
