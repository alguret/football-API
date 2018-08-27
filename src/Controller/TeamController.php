<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
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
 * @Route(path="/teams", name="team.")
 */
class TeamController extends Controller
{
    /**
     * @Route(path="/{teamId}", methods={"PUT"}, name="update")
     */
    public function updateTeam(int $teamId, Request $request, TeamService $service): Response
    {
        try {
            $teamEntity = $service->updateTeam($teamId, [
                'name' => $request->request->get('name'),
                'strip' => $request->request->get('strip'),
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => $e->getErrorMessages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($teamEntity);
    }
}
