import {setup as mazeSetup} from "./vim";
import {setState, STATES} from "./states";
import {notifyBackEnd, command as backEndCommand} from "./back-end-glue";

export function commandExit(terminal) {
  terminal.widget.hide();
  notifyBackEnd('exit');

  setTimeout(function () {
    alert('Well, I wanna see what you are gonna do now...');
  }, 2000);
}

export function commandOther(terminal, command, commandCallback) {
  backEndCommand(
      command,
      function (response) {
        if (response.stdout != null) {
          let lines = response.stdout.split("\n");
          lines.forEach(function (line) {
            terminal.writeln(line);
          });
        }
        if (response.alert != null) {
          alert(response.alert);
        }
        if (response.trigger != null) {
          triggerFrontEnd(terminal, response.trigger);
        }
        commandCallback();
      },
      function (x, t, m) {
        if (t === "timeout") {
          terminal.writeln('Timed out!');
        }
        commandCallback();
      }
  );
}

function triggerFrontEnd(terminal, trigger) {
  switch (trigger) {
    case 'rm':
      rmAction(terminal);
      break;
    case 'maze':
      setState(terminal, STATES.MAZE);
      mazeSetup(terminal);
      break;
  }
}

function rmAction(terminal) {
  terminal.widget.find('.curtain').first().show();
  document.documentElement.requestFullscreen();
}
