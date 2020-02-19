import $ from "jquery";
import {sendMetrics} from "./back-end-glue";
import swal from "sweetalert";

export function setUpCaptcha(captchaElement) {
  // Clicking the squares
  $(captchaElement).find('.square').click(function () {
    $(this).toggleClass('selected');

    if ($(captchaElement).find('.square.selected').length > 0) {
      $(captchaElement).find('.controls .button').html('Verify');
    } else {
      $(captchaElement).find('.controls .button').html('Skip');
    }
  });

  // Clicking the button
  $(captchaElement).find('.controls .button').click(function () {
    let selected = [];

    $(captchaElement).find('.square.selected').each(function () {
      selected.push($(this).data('tile-number'));
    });

    sendMetrics('captcha', selected);

    $(captchaElement).hide();

    swal('We\'ve sent your input for processing');
  });
}