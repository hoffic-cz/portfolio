import $ from "jquery";
import {setUpTerminal} from "../partial/terminal";
import {hookFooterUp} from "../partial/footer";

$(document).ready(function () {
  setUpTerminal(document.getElementById('xterm'));
  hookFooterUp();
});
