<script>function a{id}() {location.href='/admin/user/?delete={id}';}</script>
<tr>
<td>{login}</td>
<td>{if active}active{else}not active{/if}</td>
<td>
<nobr>
<a href="/admin/user/?id={id}"><img src="/images/admin/edit.png"></a>
<a href="javascript:;" onclick="eyelessConfirm('Delete user?', 'a{id}')"><img src="/images/admin/delete.png"></a>
</nobr>
</td>
</tr>
