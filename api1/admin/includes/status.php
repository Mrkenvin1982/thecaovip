<?php if(isset($dan)): ?>
<tr>
                        <td class="key" width="10%">Trạng thái</td>
                        <td>
                            <input type="radio" name="status" value="1" <?= $obj->status == 1 ? 'checked' : '' ?> />&nbsp;Yes
                            <input type="radio" name="status" value="0" <?= $obj->status == 0 ? 'checked' : '' ?> />&nbsp;No
                        </td>
</tr>
<?php else: ?>
<tr>
                        <td class="key" width="10%">Trạng thái</td>
                        <td>
                            <input type="radio" name="status" value="1" checked />&nbsp;Yes
                            <input type="radio" name="status" value="0" />&nbsp;No
                        </td>
</tr>
<?php endif; ?>