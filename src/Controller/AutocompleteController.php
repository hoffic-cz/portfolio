<?php
declare(strict_types=1);


namespace App\Controller;


use App\Enum\ActivityType;
use App\Stats\MetricsLogger;
use App\Terminal\Terminal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AutocompleteController extends AbstractController
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
     * @Route(path="/autocomplete/")
     * @param Request $request
     * @param MetricsLogger $metricsLogger
     * @return JsonResponse
     */
    public function __invoke(Request $request, MetricsLogger $metricsLogger)
    {
        $content = $request->getContent();
        $payload = json_decode($content, false, 512, JSON_THROW_ON_ERROR);

        if (!isset($payload->input)) {
            throw new \InvalidArgumentException();
        }

        $metricsLogger->log(ActivityType::AUTOCOMPLETE, $payload->input);

        return new JsonResponse($this->findSuggestions($payload->input));
    }

    private function findSuggestions(string $start): array
    {
        $allCallables = array_merge(
            Terminal::COMMANDS,
            Terminal::TRIGGERS);

        $matching = $this->filterMatching($allCallables, $start);

        if (empty($matching)) {
            return [];
        } elseif (count($matching) === 1) {
            return [
                'autocomplete' => substr(
                    $matching[0],
                    strlen($start))
            ];
        } else {
            return [
                'suggestions' => $matching,
            ];
        }
    }

    private function filterMatching(array $all, string $start)
    {
        $matching = [];

        foreach ($all as $key => $item) {
            if (substr($key, 0, strlen($start)) === $start) {
                $matching[] = $key;
            }
        }

        return $matching;
    }
}
