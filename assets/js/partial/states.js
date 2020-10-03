export const STATES = {
  NORMAL: 'normal',
  MAZE: 'maze',
  CRACKED: 'cracked',
};

export function getState(terminal) {
  return terminal.appState;
}

export function setState(terminal, state) {
  terminal.appState = state;
}
