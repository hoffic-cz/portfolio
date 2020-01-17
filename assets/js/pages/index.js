import $ from "jquery";
import {setUpTerminal} from "../partial/terminal";

$(document).ready(function () {
  setUpTerminal(document.getElementById('xterm'));
});
