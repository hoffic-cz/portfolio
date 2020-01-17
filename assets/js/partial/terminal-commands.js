import $ from "jquery";

export function commandExit(terminal) {
  terminal.widget.hide();

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
    timeout: 2500,
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
