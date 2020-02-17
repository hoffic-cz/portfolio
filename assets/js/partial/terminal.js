import $ from "jquery";
import {Terminal} from "xterm";
import {FitAddon} from "xterm-addon-fit";
import {commandExit, commandOther, terminalCrack} from "./terminal-commands";
import {getState, setState, STATES} from "./states";
import {key as mazeKey} from "./vim";
import ansiEscapes from "ansi-escapes";

const promptText = '\x1b[1;32mvisitor@hoffic.dev\x1b[1;32m\x1b[1;37m:\x1b[1;34m~\x1b[1;0m$ ';
const promptTextLength = promptText.length
    - ((promptText.match(/\x1b\[1;3.m/g) || []).length * 7)
    - ((promptText.match(/\x1b\[1;0m/g) || []).length * 6);

/**
 * Constructs and configures the terminal element.
 * @param terminalElement
 */
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

  terminal.widget = $(terminalElement).closest('.terminal-widget');

  setState(terminal, STATES.NORMAL);
  configureInput(terminal);
  configureButtons(terminal);

  populateTerminal(terminal);
}

/**
 * Configures terminal input handling.
 * @param terminal
 */
function configureInput(terminal) {
  terminal.inputBuffer = '';

  terminal.onKey(function (event) {
    if (getState(terminal) === STATES.NORMAL) {
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
    } else if (getState(terminal) === STATES.MAZE) {
      mazeKey(terminal, event.key);
    }
  });

  terminal.prompt = function () {
    if (getState(terminal) === STATES.NORMAL) {
      terminal.write(promptText);
      terminal.write('\x1b[1;0m'); // Reset the colour
      terminal.write(ansiEscapes.cursorShow); // Reset the cursor
    }
  }
}

/**
 * Populates the terminal initially.
 * @param terminal
 */
function populateTerminal(terminal) {
  terminal.prompt();

  typeAndExecuteCommand(terminal, 'intro')
}

/**
 * Types in and executes a command.
 * @param terminal
 * @param commandName
 */
function typeAndExecuteCommand(terminal, commandName) {
  terminal.writeln(commandName);

  command(terminal, commandName, function () {
    terminal.prompt();
  });
}

/**
 * Handles pressing enter.
 * @param terminal
 * @param key
 */
function keyEnter(terminal, key) {
  terminal.write('\n');
  terminal.write(key);

  let input = terminal.inputBuffer;
  terminal.inputBuffer = '';

  input = removeEscapeColon(input);

  command(terminal, input, function () {
    terminal.prompt();
  });
}

function removeEscapeColon(input) {
  if (input.length > 0 && input[0] === ':') {
    input = input.substr(1);
  }

  return input;
}

/**
 * Handles pressing backspace.
 * @param terminal
 */
function keyBackspace(terminal) {
  if (terminal._core.buffer.x > promptTextLength) {
    terminal.write('\b \b');
    terminal.inputBuffer = terminal.inputBuffer.substring(
        0,
        terminal.inputBuffer.length - 1);
  }
}

/**
 * Handles pressing non-special keys.
 * @param terminal
 * @param key
 */
function keyOther(terminal, key) {
  // Excluding common non-printable characters
  if (key.charCodeAt(0) >= 32 && key.charCodeAt(0) !== 127) {
    terminal.write(key);
    terminal.inputBuffer += key;
  }
}

/**
 * Executes a command in the terminal. Writes the output out.
 * @param terminal
 * @param command
 * @param commandCallback
 */
function command(terminal, command, commandCallback) {
  switch (command) {
    case '':
      commandCallback();
      break;
    case 'exit':
      commandExit(terminal);
      break;
    default:
      commandOther(terminal, command, commandCallback);
      break;
  }
}

/**
 * Configures actions for terminal buttons.
 * @param terminal
 */
function configureButtons(terminal) {
  terminal.widget.find('.close-button img').first().click(function () {
    commandExit(terminal);
  });

  terminal.widget.find('.item.contact').first().click(function () {
    typeAndExecuteCommand(terminal, 'contact');
  });

  terminal.widget.find('.item.about').first().click(function () {
    typeAndExecuteCommand(terminal, 'about');
  });

  terminal.widget.find('.item.timeline').first().click(function () {
    typeAndExecuteCommand(terminal, 'timeline');
  });

  terminal.widget.dblclick(function () {
    terminalCrack(terminal);
  });
}
