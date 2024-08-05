<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Event\Tournament\DataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AbstractController
{
    public function __construct(
        private readonly DataProvider $tournamentDataProvider
    ) {
    }

    #[Route(path: '/tournament')]
    public function tournament(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'tournament.html.twig',
                $this->tournamentDataProvider->get()
            );
        }

        return $this->render(
            'base.html.twig',
            ['tournament' => null]
        );
    }
}
