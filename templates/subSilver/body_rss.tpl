<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{BOARD_TITLE}</title>
		<link>{BOARD_URL}</link>
		<description>{BOARD_DESCRIPTION}</description>
		<language>de</language>
		<item>
			<!-- BEGIN post_item -->
			<title>{post_item.NEWS_TITLE}</title>
			<link>{post_item.POST_URL}</link>
			<description>
				{L_AUTHOR}: {post_item.NEWS_AUTHOR}&lt;br /&gt;
				{L_POSTED}: {post_item.NEWS_DATE}&lt;br /&gt;&lt;br /&gt;
			</description>
			<!-- Link zum Lesen des Artikels -->
			<link>http://www.mein-beispiel.de/artikel1.html</link>
			<!-- Datum des Erscheines-->
			<pubDate>{post_item.NEWS_DATE}</pubDate>
			<!-- END post_item -->
		</item>
		
	</channel>
</rss>