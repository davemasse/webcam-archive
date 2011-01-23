<?php
	// Disable direct file access
	if ($_SERVER['SCRIPT_FILENAME'] == __FILE__)
		die();
?>

<div id="webcam_archive">
	<?php if ($entry_date == null) : ?>
		<p><?php _e('No webcam photos could be found for the specified date.'); ?></p>
	<?php else : ?>
		<h2><?php echo date('F j, Y', key($entry_array)); ?></h2>
		<?php foreach ($entry_array as $entry_date => $entry) : ?>
			<div class="image">
				<?php
					foreach ($entry['sizes'] as $key => $size) {
						if ($key == 0) {
							?>
								<div class="thumb">
									<a href="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $entry['sizes'][2]['id']; ?>.jpg" data-title="<?php echo date('n/j/Y \a\t g:i a', $entry_date); ?><br /><?php foreach ($entry['metas'] as $meta) : ?><?php echo $meta['name']; ?>: <?php echo $meta['value']; ?><br /><?php endforeach; ?>"><img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $size['id']; ?>.jpg" /></a><br />
									<?php echo date('g:i a', $entry_date); ?>
								</div>
							<?php
						} elseif ($key == 1) {
							?>
								<div class="tooltip">
									<img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date; ?>/<?php echo $size['id']; ?>.jpg" /><br />
									<p><?php echo date('n/j/Y \a\t g:i a', $entry_date); ?></p>
									<div class="meta">
										<?php foreach ($entry['metas'] as $meta) : ?>
											<?php echo $meta['name']; ?>: <?php echo $meta['value']; ?><br />
										<?php endforeach; ?>
										<a href="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date; ?>/<?php echo $entry['sizes'][3]['id']; ?>.jpg" target="_blank">View original size</a>
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
</div>