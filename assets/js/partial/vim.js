export function key(terminal, key) {
  move(terminal, key);

  render(terminal);
}

function move(terminal, key) {
  let x = terminal.maze.x;
  let y = terminal.maze.y;

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

  if (isValidPosition(x, y)) {
    terminal.maze.x = x;
    terminal.maze.y = y;
  }
}

export function setup(terminal) {
  terminal.maze = {
    tiles: generateMaze(),
    x: 5,
    y: 3,
  };

  render(terminal);
}

function render(terminal) {
  terminal.reset();
  for (let row = 0; row < terminal.maze.tiles.length; row++) {
    for (let column = 0; column < terminal.maze.tiles[row].length; column++) {
      if (terminal.maze.x === column && terminal.maze.y === row) {
        terminal.write('\x1b[1;32m' + '█' + '\x1b[1;0m');
      } else {
        terminal.write(terminal.maze.tiles[row][column]);
      }
    }
    terminal.writeln(''); //new line
  }
  console.log(terminal.maze);
}

function isValidPosition(x, y) {
  return true; //TODO: Check if the tile isn't a wall
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
