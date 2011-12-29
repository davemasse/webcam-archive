<?php
	/*
	 * Copyright 2011, Dave Masse
	 * GPL v2.0
	 * http://www.gnu.org/licenses/gpl-2.0.txt
	 */
	
	// Disable direct file access
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__)
		die();
?>

<div id="webcam_archive">
	<?php if ($entry_date == null) : ?>
		<p><?php _e('No webcam photos could be found for the specified date.'); ?></p>
	<?php else : ?>
		<h2><?php echo date('l, F j, Y', $first_image_date + $blog_offset); ?><form action="<?php echo $dateless_url; ?>" method="get"><input type="text" id="datepicker" class="datepicker" name="date" value="<?php echo date('Y-m-d', key($entry_array) + $blog_offset); ?>" />
				<?php foreach ($get as $k => $v): ?>
					<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
				<?php endforeach; ?>
				<input type="submit" class="submit" />
			</form>
		</h2>
		
		<div class="nav">
			<?php if ($next_date) : ?>
				<div class="next"><a href="<?php echo $dateless_url; ?><?php echo (strstr($dateless_url, '=') ? '&' : ''); ?>date=<?php echo date('Y-m-d', $next_date); ?>"><?php _e('View'); ?> <?php echo date('F j, Y', $next_date); ?> &raquo;</a></div>
			<?php endif; ?>
			<?php if ($prev_date) : ?>
				<div class="prev"><a href="<?php echo $dateless_url; ?><?php echo (strstr($dateless_url, '=') ? '&' : ''); ?>date=<?php echo date('Y-m-d', $prev_date); ?>">&laquo; <?php _e('View'); ?> <?php echo date('F j, Y', $prev_date); ?></a></div>
			<?php endif; ?>
			<div class="clear"><!-- --></div>
		</div>
		
		<div id="latest">
			<img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $first_image_date) . $first_image_date; ?>/<?php echo $first_image['id']; ?>.jpg" />
			<p><?php _e('Latest image:'); ?> <?php echo date('g:i a', $first_image_date + $blog_offset); ?></p>
		</div>
		
		<?php foreach ($entry_array as $entry_date => $entry) : ?>
			<div class="image">
				<?php
					foreach ($entry['sizes'] as $key => $size) {
						if ($key == 0) {
							?>
								<div class="thumb">
									<a href="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $entry['sizes'][2]['id']; ?>.jpg" data-title="<?php echo date('n/j/Y \a\t g:i a', $entry_date + $blog_offset); ?><br /><?php foreach ($entry['metas'] as $meta) : ?><?php echo $meta['name']; ?>: <?php echo $meta['value']; ?><br /><?php endforeach; ?>"><img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $size['id']; ?>.jpg" width="<?php echo $size['width']; ?>" /><br /><?php echo date('g:i a', $entry_date + $blog_offset); ?></a>
								</div>
							<?php
						} elseif ($key == 1) {
							?>
								<div class="tooltip">
									<img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date; ?>/<?php echo $size['id']; ?>.jpg" /><br />
									<p><?php echo date('n/j/Y \a\t g:i a', $entry_date + $blog_offset); ?></p>
									<div class="meta">
										<?php foreach ($entry['metas'] as $meta) : ?>
											<?php echo $meta['name']; ?>: <?php echo $meta['value']; ?><br />
										<?php endforeach; ?>
										<a href="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date; ?>/<?php echo $entry['sizes'][3]['id']; ?>.jpg" target="_blank"><?php _e('View original size'); ?></a>
									</div>
								</div>
								<br />
							<?php
						}
					}
				?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="clear"><!-- --></div>
</div>