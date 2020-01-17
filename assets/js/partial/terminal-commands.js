export function commandExit(terminal) {
  terminal.widget.hide();

  setTimeout(function () {
    alert('Well, I wanna see what you are gonna do now...');
  }, 2000);
}

export function commandOther(terminal) {
  alert('Coming soon...');
}
