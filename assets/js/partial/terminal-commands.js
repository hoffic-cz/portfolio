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
      if (typeof response.stdout !== 'undefined') {
        terminal.writeln(response.stdout);
      }
      if (typeof response.alert !== 'undefined') {
        alert(response.alert);
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
