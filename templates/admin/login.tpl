<div class="login">
{if login_error}
<div class="error">
There was an error during login. Plese check your login details and try again.
</div>
{/if}
<form method="post">
<table width="100%">
<tr>
<td width="50%"></td>
<td>Login: </td>
<td><input type="text" name="login" required="required"></td>
<td width="50%"></td>
</tr>
<tr>
<td width="50%"></td>
<td>Password: </td>
<td><input type="password" name="password" required="required"></td>
<td width="50%"></td>
</tr>
<tr>
<td width="50%"></td>
<td colspan="2"><input type="submit"></td>
<td width="50%"></td>
</tr>
</table>
</form>
</div>
