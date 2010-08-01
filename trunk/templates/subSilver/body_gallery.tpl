<!-- BEGIN select -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="3">{L_GALLERY}</td>
</tr>
<!-- BEGIN row_galleries -->
<tr>
	<td class="{select.row_galleries.CLASS}"><span style="float:right;">{select.row_galleries.GALLERY_PICS}&nbsp;</span><b><a href="{select.row_galleries.U_GALLERY}">{select.row_galleries.GALLERY_NAME}</a></b><br /><span class="genmed">{select.row_galleries.GALLERY_DESC}</span></td>
</tr>
<!-- END row_galleries -->
</table>
<!-- END select -->

<!-- BEGIN details -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="3">{L_GALLERY}</td>
</tr>
<!-- BEGIN row_pics -->
<tr>
	<!-- BEGIN col_pics -->
    <td align="center">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td>
				<a href="{details.row_pics.col_pics.IMAGE}" rel="lightbox" class="info">
					<span>
						{L_TITLE}: {details.row_pics.col_pics.TITLE}<br />
						{L_WIDTH}: {details.row_pics.col_pics.WIDTH}<br />
						{L_HEIGHT}: {details.row_pics.col_pics.HEIGHT}<br />
						{L_SIZE}: {details.row_pics.col_pics.SIZE}
					</span><img src="{details.row_pics.col_pics.PREV}" alt="" border="0" />
				</a>
			</td>
		</tr>
		
		<tr>
			<td>
			<!-- BEGIN switch_user_logged_in -->
			<input type="checkbox" name="pics[]" value="{details.row_pics.col_pics.PIC_ID}">&nbsp;{L_DELETE}as
			<!-- END switch_user_logged_in -->
			</td>
		</tr>
		
        </table>
    </td>
    <!-- END col_pics -->
</tr>
<!-- END row_pics -->
<tr>
	<td colspan="{PER_ROWS}" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="{PER_ROWS}" class="small">{S_AUTH}</td>
</tr>
</table>

<!-- END details -->