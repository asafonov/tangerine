<form method="post">
{if error}
<div class="error">There was an error while logging in. Please try again</div>
{/if}
<table>
<tr><td>Login: </td><td><input type="text" name="login" value="{login}"></td></tr>
<tr><td>Password: </td><td><input type="password" name="password"></td></tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
</form>
