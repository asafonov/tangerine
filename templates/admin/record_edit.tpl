<form method="post">
<table>
<tr>
<td>Title: </td>
<td><input required="required" type="text" name="title" value="{title}">
</tr>
<tr>
<td>Body: </td>
<td><textarea name="body" required="required">{body}</textarea>
</tr>
<tr>
<td>Date: </td>
<td><input type="text" name="date" value="{date}">
</tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
{if id}
<input type="hidden" name="id"  value="{id}">
{/if}
</form>
