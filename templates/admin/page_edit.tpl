<form method="post">
<table>
<tr>
<td>Title: </td>
<td><input type="text" name="title" value="{title}">
</tr>
<tr>
<td>Keywords: </td>
<td><input type="text" name="keywords" value="{keywords}">
</tr>
<tr>
<td>Description: </td>
<td><input type="text" name="description" value="{description}">
</tr>
<tr>
<td>Url: </td>
<td><input required="required" type="text" name="url" value="{url}">
</tr>
<tr>
<td>Name: </td>
<td><input type="text" name="name" value="{name}">
</tr>
<tr>
<td>Layout: </td>
<td><input type="text" required="required" name="layout" value="{layout}">
</tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
{if id}
<input type="hidden" name="id"  value="{id}">
{/if}
</form>
