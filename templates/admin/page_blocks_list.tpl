<div id="add_block" style="display:none">
    <form method="post">
        <p>Name: <input type="text" required="required" name="block_name"></p>
        <p><input type="submit"></p>
    </form>
</div>
<script>
function addBlock() {
    eyelessDialog(document.getElementById('add_block'));
}
</script>
<div class="tangerine_add"><a href="javascript:;" onclick="addBlock()"><img src="/images/admin/add.png">Add</a></div>
<form method="post">
<table class="tangerine_table">
<tr><th>Name</th><th>Value</th><th></th></tr>
{list}
</table>
<input type="submit">
</form>