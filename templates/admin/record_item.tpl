<script>function a{id}() {location.href='./?delete={id}';}</script>
<tr>
<td>{if title}{title}{else}Unnamed record{/if}</td>
<td>{date}</td>
<td>
<nobr>
<a href="./?id={id}"><img src="/images/admin/edit.png"></a>
<a href="javascript:;" onclick="eyelessConfirm('Delete record?', 'a{id}')"><img src="/images/admin/delete.png"></a>
</nobr>
</td>
</tr>
