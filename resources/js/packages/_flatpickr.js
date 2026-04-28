import flatpickr from 'flatpickr';
import scrollPlugin from 'flatpickr/dist/plugins/scrollPlugin';
import monthSelectPlugin from 'flatpickr/dist/plugins/monthSelect';

window.flatpickr = flatpickr;
window.scrollPlugin = scrollPlugin;
window.monthSelectPlugin = monthSelectPlugin;

flatpickr.l10ns.default.firstDayOfWeek = 1;
