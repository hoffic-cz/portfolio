<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * @Route(path="/command/")
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        return new JsonResponse([
            'stdout' => sprintf(
                'Command received: "%s".',
                json_decode($request->getContent())->command
            ),
        ]);
    }
}
