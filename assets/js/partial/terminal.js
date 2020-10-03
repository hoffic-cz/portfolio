import $ from "jquery";
import {Terminal} from "xterm";
import {FitAddon} from "xterm-addon-fit";
import {commandExit, commandOther, terminalCrack} from "./terminal-commands";
import {getState, setState, STATES} from "./states";
import {configureInput} from "./input";

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
  if (getState(terminal) === STATES.NORMAL) {
    terminal.writeln(commandName);

    command(terminal, commandName, function () {
      terminal.prompt();
    });
  }
}

/**
 * Executes a command in the terminal. Writes the output out.
 * @param terminal
 * @param command
 * @param commandCallback
 */
export function command(terminal, command, commandCallback) {
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
