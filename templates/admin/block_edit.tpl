<form method="post">
<table>
<tr>
<td>Name: </td>
<td><input type="text" name="name" value="{name}">
</tr>
<tr>
<td>Value: </td>
<td><input type="text" name="value" value="{value}">
</tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
{if id}
<input type="hidden" name="id"  value="{id}">
{/if}
</form>
