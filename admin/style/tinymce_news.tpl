<script type="text/javascript" src="./../includes/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "preview, xhtmlxtras",
	
	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull, preview, code, image",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	
	// Skin options
	skin : "o2k7",
	skin_variant : "silver",
	
	// Example content CSS (should be your site CSS)
	//  content_css : "css/example.css",
	
	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",
	
	// Replace values for the template plugin
	template_replace_values : {
			username : "Some User",
			staffid : "991234"
	}
});
</script>