import {Terminal} from "xterm";
import {FitAddon} from "xterm-addon-fit";

const promptText = 'visitor@hoffic.dev:~$ ';

export function setUpTerminal(terminalElement) {
  const terminal = new Terminal({
    theme: {
      background: '#333333', // in colours.scss as $color-terminal-content
    }
  });
  const fitAddon = new FitAddon();

  terminal.loadAddon(fitAddon);
  terminal.open(terminalElement);
  fitAddon.fit();

  configureInput(terminal);

  terminal.prompt();
}

function configureInput(terminal) {
  terminal.onKey(function (event) {
    switch (event.key.charCodeAt(0)) {
      case 13:
        keyEnter(terminal, event.key);
        break;
      case 127:
        keyBackspace(terminal, event.key);
        break;
      default:
        keyOther(terminal, event.key);
        break;
    }

    console.log(event);
  });

  terminal.prompt = function () {
    terminal.write(promptText);
  }
}

function keyEnter(terminal, key) {
  terminal.write('\n');
  terminal.write(key);
  terminal.prompt();
}

function keyBackspace(terminal) {
  if (terminal._core.buffer.x > promptText.length) {
    terminal.write('\b \b');
  }
}

function keyOther(terminal, key) {
  if (key.charCodeAt(0) >= 32 && key.charCodeAt(0) !== 127)
  terminal.write(key);
}
