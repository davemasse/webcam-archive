<div id="webcam_archive">
	<?php foreach ($entry_array as $entry_date => $entry) : ?>
		<div>
			<?php
				echo date('Y-m-d H:i', $entry_date);
				
				foreach ($entry['sizes'] as $size) {
					?>
						<br />
						<img src="<?php echo $upload_path . '/webcam/' . date('Y/m/d/', $entry_date) . $entry_date . '/' . $size['id']; ?>.jpg" />
					<?php
				}
			?>
		</div>
	<?php endforeach; ?>
</div>