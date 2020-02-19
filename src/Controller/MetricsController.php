<?php
declare(strict_types=1);


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController
{
    /**
     * @Route(path="/metrics/", methods={"POST"})
     * @return JsonResponse
     */
    public function __invoke()
    {
        return new JsonResponse();
    }
}
