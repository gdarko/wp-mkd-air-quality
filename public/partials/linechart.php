<?php
/* @var array $regions */
/* @var array $stations */
/* @var string $station */
/* @var string $timemode */
/* @var string $date */
/* @var string $unit */
/* @var string $xlabels */
/* @var bool $stations_selector */
?>

<div class="mkdaiq mkdaiq-linechart-element"
     data-stations-selector="<?php echo $stations_selector; ?>"
     data-date-labels="<?php echo $xlabels; ?>"
     data-date="<?php echo $date; ?>"
     data-default-unit="<?php echo $unit; ?>"
     data-default-timemode="<?php echo $timemode; ?>"
     data-default-station="<?php echo $station; ?>">
    <div class="mkdaiq-header">
        <?php if($stations_selector && count($stations) > 1): ?>
        <div class="mkdaiq-header-filter">
            <label><?php _e('Station:', 'wp-mkd-air-quality'); ?></label>
            <select class="mkdaiq-control mkdaiq-select-station">
                <?php foreach($stations as $key => $s): ?>
                <option value="<?php echo $key; ?>" <?php selected($station, $key); ?>><?php echo $s['name']; ?></option>
                <?php endforeach; ?>
	            <?php foreach($regions as $key => $r): ?>
                    <option value="<?php echo implode(',',$r['stations']); ?>" <?php selected($station, $key); ?>><?php echo $r['name']; ?></option>
	            <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>
    <div class="mkdaiq-main">
        <canvas class="mkdaiq-chart" width="400" height="300"></canvas>
    </div>
</div>