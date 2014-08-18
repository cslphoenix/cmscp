<script type="text/javascript">
$(function() { $("#matchstats").sparkline('html',{type:'tristate', posBarColor:'#00ff00', negBarColor:'#ff0000', zeroBarColor:'#ffcc00'});});
</script>
<table class="type4" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th width="1%"><div id="matchstats">{RESULT}</div></th>
	<th>{L_SN_MATCH}</th>
	<th width="1%">{CACHE}</th>
</tr>
<!-- BEGIN sn_match_row -->
<tr>
	<td colspan="3" class="{sn_match_row.CLASS}"><span class="right {sn_match_row.CSS}">{sn_match_row.RESULT}</span>{sn_match_row.GAME} <a href="{sn_match_row.DETAILS}">{sn_match_row.NAME}</a></td>
</tr>
<!-- END sn_match_row -->
<!-- BEGIN sn_match_empty -->
<tr>
	<td colspan="3" class="empty">{sn_match_empty.L_EMPTY}</td>
</tr>
<!-- END sn_match_empty -->
</table>