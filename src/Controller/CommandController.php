<?php
declare(strict_types=1);


namespace App\Controller;


use App\Terminal\Terminal;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /** @var SessionInterface */
    private $session;

    /**
     * CommandController constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->session->start();
    }

    /**
     * @Route(path="/command/")
     * @param Request $request
     * @param Terminal $terminal
     * @return JsonResponse
     */
    public function __invoke(Request $request, Terminal $terminal)
    {
        try {
            $content = $request->getContent();
            $payload = json_decode($content, false, 512, JSON_THROW_ON_ERROR);

            if (empty($payload->command)) {
                throw new \InvalidArgumentException();
            }

            $uid = $this->generateUserId();

            $output = $terminal->command($payload->command, $uid);

            $this->session->save();

            return new JsonResponse([
                'stdout' => $output->getStdout(),
                'alert' => $output->getAlert(),
                'trigger' => $output->getTrigger(),
            ]);
        } catch (Exception $e) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return string
     */
    private function generateUserId(): string
    {
        if ($this->session->has('uid')) {
            return $this->session->get('uid');
        } else {
            $uid = uniqid('user_');
            $this->session->set('uid', $uid);
            return $uid;
        }
    }
}
