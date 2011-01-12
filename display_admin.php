<?php
	// Don't allow direct access
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__)
		die();
?>

<div class="wrap webcam-archive">
	<h2><?php _e('Webcam Archive'); ?></h2>
	
	<form action="" method="post">
		<h3>Set image sizes</h3>
		
		<table>
			<thead>
				<tr>
					<th>Delete?</th>
					<th>Width</th>
					<th>Height</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($sizes as $size) : ?>
					<tr>
						<td><?php
							if ($size->permanent == false) {
								?>
									<input type="checkbox" name="param[<?php echo $size->id; ?>][delete]" value="1" />
								<?php
							}
						?></td>
						<td align="center"><?php
							if ($size->width == 0 && $size->height == 0) {
								echo __('original');
							} elseif ($size->width == 0) {
								echo __('scaled');
							} else {
								echo $size->width;
							}
						?></td>
						<td align="center"><?php
							if ($size->height == 0 && $size->width == 0) {
								echo __('original');
							} elseif ($size->height == 0) {
								echo __('scaled');
							} else {
								echo $size->height;
							}
						?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>&nbsp;</td>
					<td align="center"><input type="text" name="param[0][width]" size="10" /></td>
					<td align="center"><input type="text" name="param[0][height]" size="10" /></td>
				</tr>
			</tbody>
		</table>
		
		<h3>Define meta information fields (optional)</h3>
		
		<table>
			<thead>
				<tr>
					<th>Delete?</th>
					<th width="100">Short Name</th>
					<th>Name</th>
					<th>Sort Order</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($metas as $meta) : ?>
					<tr>
						<td><input type="checkbox" name="meta[<?php echo $meta->id; ?>][delete]" value="1" /></td>
						<td style="padding: 0 10px; text-align: right; white-space: nowrap;"><?php echo $meta->slug; ?></td>
						<td align="center"><input type="text" name="meta[<?php echo $meta->id; ?>][name]" value="<?php echo htmlentities($meta->name); ?>" size="30" /></td>
						<td align="center"><input type="text" name="meta[<?php echo $meta->id; ?>][sort]" value="<?php echo $meta->sort; ?>" size="10" /></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="center"><input type="text" name="meta[0][name]" size="30" /></td>
					<td align="center"><input type="text" name="meta[0][sort]" size="10" /></td>
				</tr>
			</tbody>
		</table>
		
		<h3>Require login?</h3>
		
		<p><label for="require_login"><input type="checkbox" name="require_login" id="require_login" value="1" <?php echo ($require_login ? 'checked="checked"' : ''); ?> /> Require login in order to view webcam pages and images?</label></p>
		
		<p><label for="use_css"><input type="checkbox" name="use_css" id="use_css" value="1" <?php echo ($use_css ? 'checked="checked"' : ''); ?> /> Use the CSS provided with this plugin?</label></p>
		
		<p><input type="submit" value="Save" /></p>
	</form>
</div>