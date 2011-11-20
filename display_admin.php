<?php
	/*
	 * Copyright 2011, Dave Masse
	 * GPL v2.0
	 * http://www.gnu.org/licenses/gpl-2.0.txt
	 */
	
	// Don't allow direct access
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__)
		die();
?>

<div id="webcam_archive" class="wrap webcam-archive">
	<h2><?php _e('Webcam Archive'); ?></h2>
	
	<form action="" method="post">
		<h3><?php _e('Set image sizes'); ?></h3>
		
		<table>
			<thead>
				<tr>
					<th><?php _e('Delete?'); ?></th>
					<th><?php _e('Width'); ?></th>
					<th><?php _e('Height'); ?></th>
					<th><?php _e('ID (for embedding)'); ?></th>
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
							} else {
								?>
									n/a
								<?php
							}
						?></td>
						<td class="number"><?php
							if ($size->width == 0 && $size->height == 0) {
								echo __('original');
							} elseif ($size->width == 0) {
								echo __('scaled');
							} else {
								echo $size->width;
							}
						?></td>
						<td class="number"><?php
							if ($size->height == 0 && $size->width == 0) {
								echo __('original');
							} elseif ($size->height == 0) {
								echo __('scaled');
							} else {
								echo $size->height;
							}
						?></td>
						<td class="number"><?php echo $size->id; ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>&nbsp;</td>
					<td class="number"><input type="text" name="param[0][width]" size="10" /></td>
					<td class="number"><input type="text" name="param[0][height]" size="10" /></td>
				</tr>
			</tbody>
		</table>
		
		<h3><?php _e('Define meta information fields (optional)'); ?></h3>
		
		<table>
			<thead>
				<tr>
					<th><?php _e('Delete?'); ?></th>
					<th width="100"><?php _e('Short Name'); ?></th>
					<th><?php _e('Name'); ?></th>
					<th><?php _e('Sort Order'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($metas as $meta) : ?>
					<tr>
						<td><input type="checkbox" name="meta[<?php echo $meta->id; ?>][delete]" value="1" /></td>
						<td class="slug"><?php echo $meta->slug; ?></td>
						<td class="number"><input type="text" name="meta[<?php echo $meta->id; ?>][name]" value="<?php echo htmlentities($meta->name); ?>" size="30" /></td>
						<td class="number"><input type="text" name="meta[<?php echo $meta->id; ?>][sort]" value="<?php echo $meta->sort; ?>" size="10" /></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="number"><input type="text" name="meta[0][name]" size="30" /></td>
					<td class="number"><input type="text" name="meta[0][sort]" size="10" /></td>
				</tr>
			</tbody>
		</table>
		
		<h3><?php _e('Plugin Options'); ?></h3>
		
		<p><label for="require_login"><input type="checkbox" name="require_login" id="require_login" value="1" <?php echo ($require_login ? 'checked="checked"' : ''); ?> /> <?php _e('Require login in order to view webcam pages and images?'); ?></label> <?php _e('(Using nginx? See the help tab for details.)'); ?></p>
		
		<p><label for="allow_embed"><input type="checkbox" name="allow_embed" id="allow_embed" value="1" <?php echo ($allow_embed ? 'checked="checked"' : ''); ?> /> <?php echo _e('Allow embedding of the latest webcam image by other sites? If enabled, let other sites embed your webcam image using ' . get_bloginfo('wpurl') . '/webcam-archive.jpg?id=N, where N is one of the size IDs listed above.'); ?></label></p>
		
		<p><label for="use_css"><input type="checkbox" name="use_css" id="use_css" value="1" <?php echo ($use_css ? 'checked="checked"' : ''); ?> /> <?php _e('Use the CSS provided with this plugin?'); ?></label></p>
		
		<p><input type="submit" value="<?php _e('Save'); ?>" /></p>
	</form>
</div>