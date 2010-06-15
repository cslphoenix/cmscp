<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{PAGE_TITLE}</title>
		<link>{PAGE_URL}</link>
		<description>{PAGE_DESCRIPTION}</description>
		<language>de-de</language>
		<!-- BEGIN post_item -->
		<item>
			<title>{post_item.NEWS_TITLE}</title>
			<link>{post_item.POST_URL}</link>
			<description>
				{L_AUTHOR}: {post_item.NEWS_AUTHOR}
				{L_POSTED}: {post_item.NEWS_DATE}
			</description>
			<!-- Link zum Lesen des Artikels -->
			<link>{PAGE_URL}news.php?n={post_item.NEWS_ID}</link>
			<!-- Datum des Erscheines-->
			<pubDate>{post_item.NEWS_DATE}</pubDate>
		</item>
		<!-- END post_item -->
	</channel>
</rss>