<script>function a{id}() {location.href='/admin/page/blocks/{page_id}?delete={name}';}</script>
<tr>
<td>{name}:</td>
<td>Type: 
    <select name="{name}[type]">
        <option value=""></option>
        {type}
    </select>
<br>
Data: <textarea name="{name}[data]">{data}</textarea>
</td>
<td>
<a href="javascript:;" onclick="eyelessConfirm('Delete block?', 'a{id}')"><img src="/images/admin/delete.png"></a>
</td>
</tr>