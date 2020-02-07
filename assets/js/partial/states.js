export const STATES = {
  NORMAL: 'normal',
  MAZE: 'maze',
};

export function getState(terminal) {
  return terminal.appState;
}

export function setState(terminal, state) {
  terminal.appState = state;
}
