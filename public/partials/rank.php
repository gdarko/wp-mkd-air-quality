<?php
/* @var $stations */
/* @var $stationValues */
/* @var $stationIndicators */
/* @var $unit */
/* @var $date */
/* @var $timemode */
/* @var $type */
/* @var $footer */
?>

<div class="mkdaiq-rank">
	<?php if(!empty($stationValues)): ?>
	<div class="mkdaiq-table table-responsive">
		<table class="table">
			<thead>
			<tr>
				<th class="mkdaiq-icon-col" width="50"></th>
				<th><?php _e( 'Station', 'mkd-air-quality' ); ?></th>
				<th><?php _e( 'Value', 'mkd-air-quality' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ( $stationValues as $key => $value ):
				$station = isset( $stations[ $key ] ) ? $stations[ $key ] : null;
				$station = isset( $station['name'] ) ? $station['name'] : $key;
				?>
				<tr>
					<td><span class="mkdaiq-icon mkdaiq-color-<?php echo $stationIndicators[ $key ]; ?>"></span></td>
					<td><?php echo $station; ?></td>
					<td><?php echo round( $value, 3 ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot class="mkdaiq-table-footer">
			<tr>
				<td colspan="3">
					<small><?php echo implode( ' / ', $footer ); ?></small>
				</td>
			</tr>
			</tfoot>
		</table>
	</div>
	<?php else: ?>
	<p><?php _e('No data available.', 'mkd-air-quality'); ?></p>
	<?php endif; ?>
</div>
