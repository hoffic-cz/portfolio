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

export function backEndCommand(command, success, error) {
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

export function autocomplete(input, success) {
  $.ajax('/autocomplete/', {
    method: 'POST',
    data: JSON.stringify({
      input: input,
    }),
    timeout: timeout,
    success: success,
    error: null,
  });
}
