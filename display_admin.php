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
						<td><input type="checkbox" name="param[<?php echo $size->id; ?>][delete]" value="1" /></td>
						<td align="center"><?php echo $size->width; ?></td>
						<td align="center"><?php echo $size->height; ?></td>
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
					<th width="50">ID</th>
					<th>Name</th>
					<th>Sort Order</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($metas as $meta) : ?>
					<tr>
						<td><input type="checkbox" name="meta[<?php echo $meta->id; ?>][delete]" value="1" /></td>
						<td align="center"><?php echo $meta->id; ?></td>
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
		
		<p><input type="submit" value="Save" /></p>
	</form>
</div>