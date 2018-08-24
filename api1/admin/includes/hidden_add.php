<button type="submit" class="btn btn-default" name="ok">Lưu</button>
<input type="hidden" name="controller" value="<?= $entity ?>">
<input type="hidden" name="id" value="<?= isset($dan) ? $obj->getId() : '' ?>">
<input type="hidden" name="action" value="save">