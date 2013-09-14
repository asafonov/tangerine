<div class="login">
{if login_error}
<div class="error">
There was an error during login. Plese check your login details and try again.
</div>
{/if}
<form method="post">
<table>
<tr>
<td>Login: </td>
<td><input type="text" name="login" required="required"></td>
</tr>
<tr>
<td>Password: </td>
<td><input type="password" name="password" required="required"></td>
</tr>
<tr>
<td colspan="2"><input type="submit"></td>
</tr>
</table>
</form>
</div>
