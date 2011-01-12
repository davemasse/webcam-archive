<div id="webcam_archive">
	<?php foreach ($entry_array as $entry_date => $entry) : ?>
		<div class="image">
			<?php foreach ($entry['sizes'] as $key => $size) : ?>
				<?php if ($key == 0) : ?>
					<img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $size['id']; ?>.jpg" class="thumb" />
				<?php else : ?>
					<div class="tooltip">
						<img src="<?php echo $upload_path . '/' . self::upload_dir . '/' . date('Y/m/d/', $entry_date) . $entry_date; ?>/<?php echo $size['id']; ?>.jpg" /><br />
						<p><?php echo date('n/j/Y \a\t g:i a', $entry_date); ?></p>
						<div class="meta">
							<?php foreach ($entry['metas'] as $meta) : ?>
								<?php echo $meta['name']; ?>: <?php echo $meta['value']; ?><br />
							<?php endforeach; ?>
						</div>
					</div>
					<br />
				<?php endif; ?>
			<?php endforeach; ?>
			<?php echo date('g:i a', $entry_date); ?>
		</div>
	<?php endforeach; ?>
</div>