<?php
declare(strict_types=1);


namespace App\Terminal;


use App\Enum\ActivityType;
use App\Object\CommandOutput;
use App\Stats\MetricsLogger;
use App\Terminal\Command\AboutCommand;
use App\Terminal\Command\BugsCommand;
use App\Terminal\Command\CatCommand;
use App\Terminal\Command\CdCommand;
use App\Terminal\Command\ClearCommand;
use App\Terminal\Command\ContactCommand;
use App\Terminal\Command\DogCommand;
use App\Terminal\Command\ExitCommand;
use App\Terminal\Command\HelpCommand;
use App\Terminal\Command\IntroCommand;
use App\Terminal\Command\LsCommand;
use App\Terminal\Command\ManCommand;
use App\Terminal\Command\PwdCommand;
use App\Terminal\Command\RmCommand;
use App\Terminal\Command\SayCommand;
use App\Terminal\Command\TailCommand;
use App\Terminal\Command\TimelineCommand;
use App\Terminal\Command\VimCommand;
use App\Util\CmdImplProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class Terminal
{
    public const COMMANDS = [
        'intro' => [IntroCommand::class, true],
        'about' => [AboutCommand::class, true],
        'timeline' => [TimelineCommand::class, true],
        'clear' => [ClearCommand::class, true],
        'cat' => [CatCommand::class, true],
        'dog' => [DogCommand::class, false],
        'tail' => [TailCommand::class, true],
        'pwd' => [PwdCommand::class, true],
        'cd' => [CdCommand::class, true],
        'rm' => [RmCommand::class, true],
        'contact' => [ContactCommand::class, true],
        'say' => [SayCommand::class, true],
        'vi' => [VimCommand::class, true],
        'ls' => [LsCommand::class, true],
        'help' => [HelpCommand::class, true],
        'man' => [ManCommand::class, true],
        'exit' => [ExitCommand::class, true],
        'bugs' => [BugsCommand::class, false],
    ];

    public const TRIGGERS = [
        'vim' => VimCommand::class,
    ];

    public const DIRECTORIES = [
        'secrets'
    ];

    /** @var CmdImplProvider */
    private $cmdImplProvider;

    /** @var SessionInterface */
    private $session;

    /** @var MetricsLogger */
    private $metricsLogger;

    /**
     * Terminal constructor.
     * @param CmdImplProvider $cmdImplProvider
     * @param MetricsLogger $metricsLogger
     */
    public function __construct(
        CmdImplProvider $cmdImplProvider,
        MetricsLogger $metricsLogger
    )
    {
        $this->cmdImplProvider = $cmdImplProvider;
        $this->metricsLogger = $metricsLogger;
    }

    /**
     * @param string $command
     * @return CommandOutput
     */
    public function command(string $command): CommandOutput
    {
        $parts = explode(' ', $command);
        $this->removeSudo($parts);
        $name = $parts[0];

        if (is_null($this->session)) {
            $this->setSession(null); // Generate a mock session
        }
        $history = History::load($this->session);

        if ($name === ':') {
            $history->log($command);
            $this->metricsLogger->log(ActivityType::EVENT, $command, join(' ', $parts));
            return $this->trigger($parts, $history);
        } else {
            $history->log($name);
            $this->metricsLogger->log(ActivityType::COMMAND, $name, join(' ', $parts));
            return $this->execute($name, $parts, $history);
        }
    }

    private function removeSudo(array &$parts)
    {
        while (isset($parts[0]) && $parts[0] === 'sudo') {
            array_shift($parts);
        }
    }

    private function execute(string $name, array $parts, ?History $history): CommandOutput
    {
        if (in_array($name, self::DIRECTORIES)) {
            return new CommandOutput(sprintf('bash: %s: Is a directory', $name));
        } else {
            $implementation = $this->cmdImplProvider->get($name);

            return $implementation->execute($parts, $history);
        }
    }

    private function trigger(array $parts, ?History $history): CommandOutput
    {
        array_shift($parts); // Discarding the escape colon
        $trigger = array_shift($parts);
        $implementation = $this->cmdImplProvider->getTrigger($trigger);
        return $implementation->trigger($parts, $history);
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session = null): void
    {
        if (is_null($session)) {
            $session = new Session(new MockArraySessionStorage());
        }

        $this->session = $session;
    }
}
