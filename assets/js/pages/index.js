import {Terminal} from "xterm";
import {FitAddon} from "xterm-addon-fit";
import $ from "jquery";

$(document).ready(function () {
  const terminal = new Terminal({
    theme: {
      background: '#333333', // in colours.scss as $color-terminal-content
    }
  });
  const fitAddon = new FitAddon();

  terminal.loadAddon(fitAddon);
  terminal.open(document.getElementById('xterm'));
  fitAddon.fit();

  terminal.writeln('Hello!');
  for (let i = 0; i < 100; i++) {
    terminal.writeln('Yo');
  }
});
