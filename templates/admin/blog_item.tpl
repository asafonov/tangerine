<script>function a{id}() {location.href='/admin/blog/?delete={id}';}</script>
<tr>
<td>{if title}{title}{else}Unnamed blog{/if}</td>
<td align="center"><a href="/admin/blog/?id={id}"><img src="/images/admin/put.png"></a></td>
<td>
<nobr>
<a href="/admin/record/{id}/"><img src="/images/admin/edit.png"></a>
<a href="javascript:;" onclick="eyelessConfirm('Delete blog?', 'a{id}')"><img src="/images/admin/delete.png"></a>
</nobr>
</td>
</tr>
