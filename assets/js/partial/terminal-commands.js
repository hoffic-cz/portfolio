import $ from "jquery";

const timeout = 2500;

export function commandExit(terminal) {
  terminal.widget.hide();
  notifyBackEnd('exit');

  setTimeout(function () {
    alert('Well, I wanna see what you are gonna do now...');
  }, 2000);
}

export function commandOther(terminal, command, commandCallback) {
  $.ajax('/command/', {
    method: 'POST',
    data: JSON.stringify({
      command: command
    }),
    timeout: timeout,
    success: function (response) {
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
    error: function (x, t, m) {
      if (t === "timeout") {
        console.log('Timed out!');
      }
      commandCallback();
    }
  });
}

function notifyBackEnd(command) {
  $.ajax('/command/', {
    method: 'POST',
    data: JSON.stringify({
      command: command
    }),
    timeout: timeout,
  });
}

function triggerFrontEnd(terminal, trigger) {
  switch (trigger) {
    case 'rm':
      rmAction(terminal);
      break;
  }
}

function rmAction(terminal) {
  terminal.widget.find('.curtain').first().show();
  document.documentElement.requestFullscreen();
}
