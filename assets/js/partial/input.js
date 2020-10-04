import {getState, STATES} from "./states";
import {key as mazeKey} from "./vim";
import ansiEscapes from "ansi-escapes";
import {command} from "./terminal";
import {autocomplete, backEndCommand} from "./back-end-glue";

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
  terminal.inputHistoryPositionSelected = -1;

  terminal.onKey(function (event) {
    if (getState(terminal) === STATES.NORMAL) {
      switch (event.key.charCodeAt(0)) {
        case 9:
          keyTab(terminal);
          break;
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
  terminal.inputHistoryPositionSelected = -1;

  input = removeEscapeColon(input);

  addToHistory(terminal, input);

  command(terminal, input, function () {
    terminal.prompt();
  });
}

function keyTab(terminal) {
  if (terminal.inputCaretPosition === terminal.inputBuffer.length) {
    autocomplete(terminal.inputBuffer, function (response) {
      if (response.autocomplete) {
        terminal.write(response.autocomplete);
        terminal.inputBuffer = terminal.inputBuffer + response.autocomplete;
        terminal.inputCaretPosition += response.autocomplete.length;
      } else if (response.suggestions) {
        terminal.writeln('');
        terminal.write(response.suggestions.join(' '));
        terminal.writeln('');
        terminal.prompt();
        terminal.write(terminal.inputBuffer);
      }
    });
  }
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
      historyPrevious(terminal);
      break;
    case '\x1b[B':
      historyNext(terminal);
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

  let isDifferent = history.length === 0 || history[history.length - 1] !== input;
  let isEmpty = input.length === 0;

  if (isDifferent && !isEmpty) {
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

function historyPrevious(terminal) {
  if (terminal.inputHistory.length > 0) {
    if (terminal.inputHistoryPositionSelected === -1) {
      if (terminal.inputBuffer.length > 0) {
        terminal.inputHistory.push(terminal.inputBuffer);
        terminal.inputHistoryPositionSelected = terminal.inputHistory.length - 2;
      } else {
        terminal.inputHistoryPositionSelected = terminal.inputHistory.length - 1;
      }

    } else if (terminal.inputHistoryPositionSelected > 0) {
      terminal.inputHistoryPositionSelected--;
    }

    renderHistory(terminal);
  }
}

function historyNext(terminal) {
  if (terminal.inputHistoryPositionSelected !== -1) {
    if (terminal.inputHistoryPositionSelected === terminal.inputHistory.length - 1) {
      terminal.inputHistoryPositionSelected = -1;
    } else if (terminal.inputHistoryPositionSelected < terminal.inputHistory.length -1) {
      terminal.inputHistoryPositionSelected++;
    }

    renderHistory(terminal);
  }
}

function renderHistory(terminal) {
  let contents = '';
  if (terminal.inputHistoryPositionSelected !== -1) {
    contents = terminal.inputHistory[terminal.inputHistoryPositionSelected];
  }

  terminal.inputBuffer = contents;
  terminal.inputCaretPosition = contents.length;

  terminal.write(ansiEscapes.eraseLine);
  terminal.write(ansiEscapes.cursorTo(0));
  terminal.prompt();
  terminal.write(contents);
}
