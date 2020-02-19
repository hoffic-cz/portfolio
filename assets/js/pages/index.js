import $ from "jquery";
import {setUpTerminal} from "../partial/terminal";
import {setUpCaptcha} from "../partial/captcha";
import {hookFooterUp} from "../partial/footer";
import {layConsoleEgg} from "../partial/console-egg";

$(document).ready(function () {
  setUpTerminal(document.getElementById('xterm'));
  setUpCaptcha(document.getElementsByClassName('captcha-widget')[0]);
  hookFooterUp();
  layConsoleEgg();
});
