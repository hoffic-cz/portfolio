<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class HelpCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput(/** @lang text */ <<<'STDOUT'

    This terminal is for you to have fun playing with while:

    - getting to know me a bit from my online presence
    - exploring the secret corners and easter eggs scattered around here
    - realizing you have just found who you were looking for
      (talking to you <inser a big tech company name here>)


    Exploit this to the maximum, but make sure you report all issues
    you find either to my email (petr@hoffic.dev) or through the GitLab
    issue tracker (https://gitlab.com/hoffic.cz/portfolio/issues)



    I WILL BE EXTREMELY HAPPY FOR ANY SUGGESTIONS OR FEEDBACK! :)



STDOUT
);
    }
}
