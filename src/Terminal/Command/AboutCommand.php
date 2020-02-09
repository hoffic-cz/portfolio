<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class AboutCommand implements Command
{

    function execute(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput(<<<'STDOUT'


    Hi! I'm Petr,
    
    a software engineer based in London/Prague who enjoys developing software that is
    
    well tested, robust and does precisely what the user wants, except so well they
    
    are nicely surprised.
    
    
    
    I'm not getting lost in technical complexity. I am careful with assumptions and
    
    I build software piece by piece maintaining a stable and reliable base to build
    
    on top of.
    
    
    
    I love making people feel "Wow, I can't imagine how much effort that was..."


STDOUT
        );
    }
}
