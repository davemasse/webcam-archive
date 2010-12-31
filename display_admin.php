<?php
	// Don't allow direct access
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__)
		die();
?>

<div class="wrap webcam-archive">
	<h2><?php _e('Webcam Archive'); ?></h2>
	
	<h3>Set image sizes</h3>
	
	<form action="" method="post">
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
					<td align="center"><input type="text" name="param[0][width]" id="width_0" size="10" /></td>
					<td align="center"><input type="text" name="param[0][height]" id="height_0" size="10" /></td>
				</tr>
			</tbody>
		</table>
		
		<p><input type="submit" value="Save" /></p>
	</form>
</div>