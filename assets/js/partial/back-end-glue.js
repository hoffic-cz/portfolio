import $ from "jquery";

const timeout = 2500;

export function notifyBackEnd(trigger) {
  $.ajax('/command/', {
    method: 'POST',
    data: JSON.stringify({
      command: trigger
    }),
    timeout: timeout,
  });
}

export function sendMetrics(name, data) {
  $.ajax('/metrics/', {
    method: 'POST',
    data: JSON.stringify({
      name: name,
      data: data,
    }),
    timeout: timeout,
  });
}

export function command(command, success, error) {
  $.ajax('/command/', {
    method: 'POST',
    data: JSON.stringify({
      command: command
    }),
    timeout: timeout,
    success: success,
    error: error,
  });
}
