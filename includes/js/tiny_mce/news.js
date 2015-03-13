$().ready(function()
{
	$('textarea.tinymce').tinymce(
	{
		script_url : './../includes/js/tiny_mce/tiny_mce.js',
		
		theme : "advanced",
	//	plugins : "preview, xhtmlxtras, bbcode",
		plugins : "preview, xhtmlxtras",
		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,preview,code,image",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		
		skin : "o2k7",
		skin_variant : "silver",
		// content_css : "css/content.css",
		
		template_external_list_url : "js/template_list.js",
		external_link_list_url : "js/link_list.js",
		external_image_list_url : "js/image_list.js",
		media_external_list_url : "js/media_list.js",
		
		template_replace_values:
		{
			username : "Some User",
			staffid : "991234"
		}
	});
});