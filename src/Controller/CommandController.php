<?php


namespace App\Controller;


use App\Terminal\Terminal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * @Route(path="/command/")
     * @param Request $request
     * @param Terminal $terminal
     * @return JsonResponse
     */
    public function __invoke(Request $request, Terminal $terminal)
    {
        return new JsonResponse([
            'stdout' => sprintf(
                'Command received: "%s".',
                json_decode($request->getContent())->command
            ),
        ]);
    }
}
