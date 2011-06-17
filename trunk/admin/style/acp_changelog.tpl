  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	</tr>
  </table>

<table width="100%">
     <tr>
          <td valign="top" width="68%">
               <table class="forumline" cellpadding="2" width="100%" cellspacing="1">
                    <tr>
                         <th>
                              {L_CHANGE_LOGS_ID}
                         </th>
                         <th>
                              {L_CHANGE_LOGS_REFERENCE}
                         </th>
                         <th>
                              {L_CHANGE_LOGS_TITLE}
                         </th>
                         <th>
                              {L_CHANGE_LOGS_AUTHOR}
                         </th>
                         <th>
                              {L_CHANGE_LOGS_TIME}
                         </th>
                         <th>
                              {L_CHANGE_LOGS_FILES}
                         </th>
                    </tr>
                    <!-- BEGIN change_log -->
                    <tr>
                         <td class="row1" align="center">
                              <span class="gensmall">{change_log.CHANGE_LOGS_ID}</span>
                         </td>
                         <td class="row1" align="center">
                              <span class="gensmall">{change_log.CHANGE_LOGS_REFERENCE}</span>
                         </td>
                         <td class="row1" align="left">
                              <span class="gensmall"><b>{change_log.CHANGE_LOGS_TITLE}</b></span>
                              <span class="gensmall"><br>{change_log.CHANGE_LOGS_DESCRIPTION}</span>
                         </td>
                         <td class="row1" align="center">
                              <a href="{change_log.U_CHANGE_LOGS_AUTHOR}" class="gensmall">{change_log.CHANGE_LOGS_AUTHOR}</a>
                         </td>
                         <td class="row1" align="center">
                              <span class="gensmall">{change_log.CHANGE_LOGS_TIME}</span>
                         </td>
                         <td class="row1" align="center">
                              <a href="{change_log.U_CHANGE_LOGS_FILES}">{change_log.CHANGE_LOGS_FILES}</a>
                         </td>
                    </tr>
                    
                    <!-- END change_log -->
               </table>
          </td>
          <td width="4%">

          </td>
          <td valign="top" width="28%">
               <table class="forumline" cellpadding="2" cellspacing="1" width="100%">
                    <form enctype="multipart/form-data" method="post" action="{S_CHANGE_LOGS_ADD}">
                    <tr>
                         <th colspan="2">
                              {L_CHANGE_LOGS_ADD}
                         </th>
                    </tr>
                    <tr>
                         <td class="row1">
                              <span class="forumlink">{L_CHANGE_LOGS_TITLE}&nbsp;</span>
                         </td>
                         <td class="row1">
                              <input class="post" type="text" name="change_log_title" maxlength="100" size="35"/>
                         </td>
                    </tr>
                    <tr>
                          <td class="row1" colspan="2">
                              <span class="forumlink">{L_CHANGE_LOGS_DESCRIPTION}<br></span>
                               <textarea rows="10" cols="55" class="post" type="number" name="change_log_description" maxlength="255" size="255" /></textarea>
                          </td>
                    </tr>
                    <tr>
                         <td class="row1">
                              <span class="forumlink">{L_CHANGE_LOGS_REFERENCE}&nbsp;</span>
                         </td>
                         <td class="row1">
                              <select name="change_log_reference">{DD_CHANGE_LOGS_REFERENCE}</select>
                         </td>
                    </tr>
                    <tr>
                         <td class="row1">
                              <span class="forumlink">{L_CHANGE_LOGS_FILES}&nbsp;</span>
                         </td>
                         <td class="row1">
                              <input class="post" type="text" name="change_log_files" maxlength="100" size="35"/>
                         </td>
                    </tr>
                     <tr>
                         <td class="row2" align="center" colspan="2">
                             <input type="submit" class="mainoption" name="login" value="{L_CHANGE_LOGS_ADD}" />
                         </td>

                     </tr>

               </table>
               </form>
               <br>
               <table align="right">
                    <tr>
                         <td>
                               <span class="gensmall"><a href="http://www.tememento.de" target="_new">FR</a> <a href="http://www.php-styles.com" target="_new">php-styles.com</a></span>
                         </td>
                    </tr>
               </table>
          </td>
     </tr>
</table>
