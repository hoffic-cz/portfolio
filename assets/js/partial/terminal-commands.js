import $ from "jquery";
import {setup as mazeSetup} from "./vim";
import {setState, STATES} from "./states";
import {notifyBackEnd, command as backEndCommand} from "./back-end-glue";
import swal from "sweetalert";

export function commandExit(terminal) {
  terminal.widget.hide();
  notifyBackEnd('exit');

  setTimeout(function () {
    swal('Well, I wanna see what you are gonna do now...');
  }, 2000);
}

export function terminalCrack(terminal) {
  terminal.widget.addClass('cracked');
  setState(terminal, STATES.CRACKED);
  notifyBackEnd(': crack');
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
          swal(response.alert);
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
    case 'captcha':
      captchaAction();
  }
}

function rmAction(terminal) {
  terminal.widget.find('.curtain').first().show();
  document.documentElement.requestFullscreen();
}

function captchaAction() {
  $(document).find('.captcha-widget').first().show();
}
