import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';

document.addEventListener('DOMContentLoaded', function () {
  tippy('[data-tippy-content]', {
    theme: 'light',
    placement: 'top',
    maxWidth: 300,
    allowHTML: true,
    animation: 'scale',
  });
});
