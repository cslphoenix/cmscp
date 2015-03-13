/* German initialisation for the jQuery UI date picker plugin. */
/* Written by Milian Wolff (mail@milianw.de). */
jQuery(function($){
	$.datepicker.regional['de'] = {
		closeText: 'schlie�en',
		prevText: '&#x3C;zur�ck',
		nextText: 'Vor&#x3E;',
		currentText: 'heute',
		monthNames: ['Januar','Februar','M�rz','April','Mai','Juni',
		'Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jan','Feb','M�r','Apr','Mai','Jun',
		'Jul','Aug','Sep','Okt','Nov','Dez'],
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		weekHeader: 'KW',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['de']);
});

/* German translation for the jQuery Timepicker Addon */
/* Written by Marvin */
(function($) {
	$.timepicker.regional['de'] = {
		timeOnlyTitle: 'Zeit W�hlen',
		timeText: 'Zeit',
		hourText: 'Stunde',
		minuteText: 'Minute',
		secondText: 'Sekunde',
		millisecText: 'Millisekunde',
		timezoneText: 'Zeitzone',
		currentText: 'Jetzt',
		closeText: 'Fertig',
		timeFormat: 'HH:mm',
		amNames: ['vorm.', 'AM', 'A'],
		pmNames: ['nachm.', 'PM', 'P'],
		isRTL: false
	};
	$.timepicker.setDefaults($.timepicker.regional['de']);
})(jQuery);