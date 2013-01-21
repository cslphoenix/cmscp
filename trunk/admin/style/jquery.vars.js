


$(function()
{
	$('a').aToolTip();
	$('img').aToolTip();
	$('span').aToolTip();
	$('label').aToolTip();
	$('textarea').aToolTip();
	
	$.datepicker.regional['de'];
	
	$("#user_user_birthday").datepicker(
	{
		changeMonth: true,
		changeYear: true,
	//	minDate: 0,
	//	numberOfMonths: 2,
		dateFormat: 'yy-mm-dd'
	});
	
	$("#news_news_date").datetimepicker(
	{
	//	minDate: 0,
		dateFormat: 'dd.mm.yy',
		separator: ' ',
	//	stepMinute: 15,
	});
		
	$("#event_event_date").datetimepicker(
	{
		
		minDate: 0,
		dateFormat: 'dd.mm.yy',
		separator: ' ',
		stepMinute: 15,
	});
	
	$("#match_match_date").datetimepicker(
	{
		minDate: 0,
		dateFormat: 'dd.mm.yy',
		separator: ' ',
		stepMinute: 15,
	});
	
	$("#match_training_date").datetimepicker(
	{
		minDate: 0,
		dateFormat: 'dd.mm.yy',
		separator: ' ',
		stepMinute: 15,
	});
	
	$("#training_training_date").datetimepicker(
	{
		minDate: 0,
		dateFormat: 'dd.mm.yy',
		separator: ' ',
		stepMinute: 15,
	});
	
	
	$('a[rel*=lightbox]').lightBox();	/* match, gallery */
	
	$('textarea').expandingTextArea();
	
	$('#config_page_desc').maxlength({max: 255});
	$('#config_page_disable_msg').maxlength({max: 255});
	
	$('.tabs').liteTabs({ borders: true, height: 'auto', selectedTab: 1, width: '700' });
});

$(document).ready(function()
{
	$(".color-picker").miniColors({
		letterCase: 'uppercase',
	});
});