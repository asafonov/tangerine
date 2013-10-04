<form method="post">
<table>
<tr>
<td>Title: </td>
<td><input type="text" name="title" value="{title}">
</tr>
<tr>
<td>Description: </td>
<td><input type="text" name="description" value="{description}">
</tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
{if id}
<input type="hidden" name="id"  value="{id}">
{/if}
</form>
