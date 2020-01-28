import $ from "jquery";
import {setUpTerminal} from "../partial/terminal";
import {hookFooterUp} from "../partial/footer";
import {layConsoleEgg} from "../partial/console-egg";

$(document).ready(function () {
  setUpTerminal(document.getElementById('xterm'));
  hookFooterUp();
  layConsoleEgg();
});
