import $ from "jquery";

export function hookFooterUp() {
  let footer = $(document).find('footer.footer').first();

  sourceLink(footer);
  copyrightLink(footer);
}

function sourceLink(footer) {
  let sourceLink = footer.find('a.source').first();
  sourceLink.click(function () {
    if (/Mobi/.test(navigator.userAgent)) {
      sourceLink.html('This only works on a desktop version :(');
    } else {
      let counter = 0;
      if (sourceLink.data('clicked') != null) {
        counter = sourceLink.data('clicked');
      }

      // Perform appropriate actions
      if (counter <= 0) {
        sourceLink.html('Press Ctrl + U');
      } else if (counter < 5) {
        sourceLink.html('Stop clicking and press Ctrl + U 😛');
      } else if (counter < 10) {
        sourceLink.html('Are you waiting for an easter egg?');
      } else if (counter < 100) {
        sourceLink.html(counter + '/100 clicks and counting...');
      } else {
        sourceLink.html('WIP: Sorry to disappoint you, please send me a suggestion of what should happen now 😇');
      }

      sourceLink.data('clicked', counter + 1);
    }
  });
}

function copyrightLink(footer) {
  let copyrightLink = footer.find('span.rights').first();
  let copyrightNote = footer.find('span.rights-note').first();

  copyrightLink.click(function () {
    copyrightLink.hide();
    copyrightNote.show();
  });
}