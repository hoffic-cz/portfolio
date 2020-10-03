import {getState, STATES} from "./states";
import {key as mazeKey} from "./vim";
import ansiEscapes from "ansi-escapes";
import {command} from "./terminal";

const promptText = '\x1b[1;32mvisitor@hoffic.dev\x1b[1;32m\x1b[1;37m:\x1b[1;34m~\x1b[1;0m$ ';
const promptTextLength = promptText.length
    - ((promptText.match(/\x1b\[1;3.m/g) || []).length * 7)
    - ((promptText.match(/\x1b\[1;0m/g) || []).length * 6);

/**
 * Configures terminal input handling.
 * @param terminal
 */
export function configureInput(terminal) {
  terminal.inputBuffer = '';
  terminal.inputCaretPosition = 0;
  terminal.inputHistory = [];

  terminal.onKey(function (event) {
    if (getState(terminal) === STATES.NORMAL) {
      switch (event.key.charCodeAt(0)) {
        case 13:
          keyEnter(terminal, event.key);
          break;
        case 127:
          keyBackspace(terminal, event.key);
          break;
        case 27:
          keyArrow(terminal, event.key);
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
 * Handles pressing enter.
 * @param terminal
 * @param key
 */
function keyEnter(terminal, key) {
  terminal.write('\n');
  terminal.write(key);

  let input = terminal.inputBuffer;
  terminal.inputBuffer = '';
  terminal.inputCaretPosition = 0;

  input = removeEscapeColon(input);

  addToHistory(terminal, input);

  command(terminal, input, function () {
    terminal.prompt();
  });
}

/**
 * Handles pressing backspace.
 * @param terminal
 */
function keyBackspace(terminal) {
  if (terminal.inputBuffer.length > 0) {
    let leftPart = terminal.inputBuffer.substring(0,
        terminal.inputCaretPosition - 1);
    let rightPart = terminal.inputBuffer.substring(terminal.inputCaretPosition);

    terminal.inputBuffer = leftPart + rightPart;

    terminal.write(ansiEscapes.cursorBackward());
    terminal.write(ansiEscapes.eraseEndLine);

    if (rightPart.length > 0) {
      terminal.write(rightPart);
      terminal.write(ansiEscapes.cursorBackward(rightPart.length));
    }

    terminal.inputCaretPosition--;
  }
}

function keyArrow(terminal, key) {
  switch (key) {
    case '\x1b[A':
      //up;
      break;
    case '\x1b[B':
      //down;
      break;
    case '\x1b[C':
      moveCaretRight(terminal);
      break;
    case '\x1b[D':
      moveCaretLeft(terminal);
      break;
  }
}

/**
 * Handles pressing non-special keys.
 * @param terminal
 * @param key
 */
function keyOther(terminal, key) {
  let printable = key.charCodeAt(0) >= 32 && key.charCodeAt(0) !== 127;
  let fitsInLine = terminal.inputBuffer.length < terminal.cols
      - promptTextLength - 1;

  if (printable && fitsInLine) {
    let leftPart = terminal.inputBuffer.substring(0,
        terminal.inputCaretPosition);
    let rightPart = terminal.inputBuffer.substring(terminal.inputCaretPosition);

    terminal.inputBuffer = leftPart + key + rightPart;

    terminal.write(key);

    if (rightPart.length > 0) {
      terminal.write(rightPart);
      terminal.write(ansiEscapes.cursorBackward(rightPart.length));
    }

    terminal.inputCaretPosition++;
  }
}

function removeEscapeColon(input) {
  if (input.length > 0 && input[0] === ':') {
    input = input.substr(1);
  }

  return input;
}

function addToHistory(terminal, input) {
  let history = terminal.inputHistory;

  if (history.length === 0 || history[history.length - 1] !== input) {
    terminal.inputHistory.push(input);
  }
}

function moveCaretLeft(terminal) {
  if (terminal.inputCaretPosition > 0) {
    terminal.inputCaretPosition--;
    terminal.write(ansiEscapes.cursorBackward());
  }
}

function moveCaretRight(terminal) {
  if (terminal.inputCaretPosition < terminal.inputBuffer.length) {
    terminal.inputCaretPosition++;
    terminal.write(ansiEscapes.cursorForward());
  }
}
