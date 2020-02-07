import ansiEscapes from "ansi-escapes";
import {command, notifyBackEnd} from "./back-end-glue";
import {getState, setState, STATES} from "./states";

const PLAYER_CHAR = '\x1b[1;31m' + '█' + '\x1b[1;0m';

export function key(terminal, key) {
  move(terminal, key);

  if (getState(terminal) === STATES.MAZE) {
    render(terminal, false);
  } else {
    terminal.reset();
    terminal.prompt();
  }
}

function move(terminal, key) {
  let x = terminal.maze.x;
  let y = terminal.maze.y;
  terminal.maze.prevX = x;
  terminal.maze.prevY = y;

  switch (key) {
    case '\x1b[A':
      y--;
      break;
    case '\x1b[B':
      y++;
      break;
    case '\x1b[C':
      x++;
      break;
    case '\x1b[D':
      x--;
      break;
  }

  if (isExit(terminal, x, y)) {
    command(':vim:exit', function (response) {
      alert(response.alert);
    });
    setState(terminal, STATES.NORMAL);
  } else if (isValidPosition(terminal, x, y)) {
    terminal.maze.x = x;
    terminal.maze.y = y;
  }
}

export function setup(terminal) {
  terminal.maze = {
    tiles: generateMaze(),
    x: 80,
    y: 21,
    prevX: 5,
    prevY: 3,
  };

  render(terminal);
}

function render(terminal, redraw = true) {
  terminal.write(ansiEscapes.cursorHide);

  // Draw maze
  if (redraw) {
    terminal.reset();
    for (let row = 0; row < terminal.maze.tiles.length; row++) {
      for (let column = 0; column < terminal.maze.tiles[row].length; column++) {
        terminal.write(terminal.maze.tiles[row][column]);
      }
      terminal.writeln(''); //new line
    }
  }

  // Draw player position
  terminal.write(ansiEscapes.cursorTo(
      terminal.maze.prevX,
      terminal.maze.prevY));
  terminal.write(' ');
  terminal.write(ansiEscapes.cursorTo(terminal.maze.x, terminal.maze.y));
  terminal.write(PLAYER_CHAR);
}

function isExit(terminal, x, y) {
  return terminal.maze.tiles[y][x] === 'E';
}

function isValidPosition(terminal, x, y) {
  return terminal.maze.tiles[y][x] === ' ';
}

function generateMaze() {
  let plan = `


    █████████████████████████████████████████████████████████████████████████████████
    █     █               █       █   █                             █   █           █
    █████ █ █████████ ███ █ █████ █ █ █ █████████ ███ ███████ █████ █ █ ███ ███████ █
    █     █       █   █   █ █ █   █ █ █       █ █   █     █       █ █ █   █       █ █
    █ ███████████ █ ███ ███ █ █ █████ ███████ █ ███ ███ █ █████ ███ █████ ███████ █ █
    █ █     █     █   █ █ █ █ █ █   █ █     █ █     █   █ █     █   █   █       █ █ █
    █ █ ███ █ ███████ █ █ █ █ █ █ █ █ █ ███ █ ███████████ █ █████ ███ █ █ █ ███ █ █ █
    █ █ █   █ █       █ █   █   █ █   █ █ █ █   █       █ █   █ █     █   █   █   █ █
    █ █ █ ███ █ ███████ █ ███ ███ █ ███ █ █ ███ █ █████ █████ █ ███████ ███████████ █
    █   █ █   █     █   █   █ █   █   █   █   █ █     █ █     █   █   █     █   █   █
    █████ █ ███████ █ ███████ █ █████ ███████ █ ███ █ █ █ █████ █ █ ███████ █ █ █ ███
    █   █ █   █   █ █         █     █       █ █     █ █ █ █   █ █ █         █ █ █   █
    ███ █ ███ ███ █ █████████ █ ███ ███████ █ █████████ █ ███ █ █ ███████████ █████ █
    █   █     █     █       █   █   █   █   █         █ █ █   █ █ █     █   █   █   █
    █ █████████ █ █████ ███ █████ ███ █ █ ███████████ █ █ █ █████ █ ███ █ █ ███ █ ███
    █           █ █   █ █   █       █ █ █   █         █ █   █   █ █ █   █ █     █   █
    █████████████ █ █ █ █ ███ █████ █ █ ███ █ ███████ █ █████ █ █ ███ ███ █████ ███ █
    █ █           █ █   █ █   █   █   █   █   █     █ █ █     █ █         █   █     █
    █ █ █ █████████ █████ █ █████ ███████ █ ███ ███ █ █ █ █████ ███████████ █ ███ ███
    █   █           █     █       █       █     █   █   █     █             █      EXIT
    █████████████████████████████████████████████████████████████████████████████████


    Only the most hardcore programmers can exit Vim...

  `;

  let maze = plan.split("\n");

  return maze.slice(1, -1);
}
