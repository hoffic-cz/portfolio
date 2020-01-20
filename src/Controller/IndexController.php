<?php
declare(strict_types=1);


namespace App\Controller;


use App\Util\SourceArt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     * @param SourceArt $sourceArt
     * @return Response
     */
    public function __invoke(SourceArt $sourceArt)
    {
        $source = $this->renderView('pages/index.html.twig');
        $beautifulSource = $sourceArt->draw($source);

        return new Response($beautifulSource);
    }
}
