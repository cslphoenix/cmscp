<?xml version="1.0" encoding="{S_CONTENT_ENCODING}" ?>
<rss version="0.92">
<channel>
<title>{BOARD_TITLE}</title>
<link>{BOARD_URL}</link>
<description>{BOARD_DESCRIPTION}</description>
<managingEditor>{BOARD_MANAGING_EDITOR}</managingEditor>
<webMaster>{BOARD_WEBMASTER}</webMaster>
<lastBuildDate>{BUILD_DATE}</lastBuildDate>
<!-- BEGIN post_item -->
<item>
	<title>{post_item.NEWS_TITLE}</title>
	<link>{post_item.POST_URL}</link>
	<description>
		{L_AUTHOR}: {post_item.NEWS_AUTHOR}&lt;br /&gt;
		{L_POSTED}: {post_item.NEWS_DATE}&lt;br /&gt;&lt;br /&gt;
	</description>
</item>
<!-- END post_item -->
</channel>
</rss>