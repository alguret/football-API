<?php

namespace App\Controller;

use App\Exception\ValidationException;
use App\Service\LeagueService;
use App\Service\TeamService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/leagues", name="league.")
 */
class LeagueController extends Controller
{
    /**
     * @Route(path="/{leagueId}", methods={"DELETE"}, name="delete")
     */
    public function deleteLeague(int $leagueId, LeagueService $service): Response
    {
        try {
            $league = $service->deleteLeagueById($leagueId);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($league, Response::HTTP_OK);
    }

    /**
     * @Route(path="/{leagueId}/teams", methods={"POST"}, name="create_team")
     */
    public function createTeam(int $leagueId, Request $request, TeamService $service): Response
    {
        try {
            $teamEntity = $service->createTeam([
                'leagueId' => $leagueId,
                'name' => $request->request->get('name'),
                'strip' => $request->request->get('strip'),
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => $e->getErrorMessages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($teamEntity, Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/{leagueId}/teams", methods={"GET"}, name="get_teams")
     */
    public function getTeams(int $leagueId, TeamService $service): Response
    {
        try {
            $teams = $service->getTeamsByLeagueId($leagueId)->toArray();
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($teams);
    }
}
