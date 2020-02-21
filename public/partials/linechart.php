<?php
/* @var array $stations */
/* @var string $default_station */
/* @var string $default_timemode */
/* @var string $date */
/* @var string $unit */
/* @var string $date_labels */
?>

<div class="mkdaiq mkdaiq-linechart-element"
     data-date-labels="<?php echo $date_labels; ?>"
     data-date="<?php echo $date; ?>"
     data-default-unit="<?php echo $unit; ?>"
     data-default-timemode="<?php echo $default_timemode; ?>"
     data-default-station="<?php echo $default_station; ?>">
    <div class="mkdaiq-header">
        <?php if(count($stations) > 1): ?>
        <div class="mkdaiq-header-filter">
            <label><?php _e('Station', 'mkd-air-quality'); ?></label>
            <select class="mkdaiq-control mkdaiq-select-station">
                <?php foreach($stations as $key => $station): ?>
                <option value="<?php echo $key; ?>" <?php selected($default_station, $key); ?>><?php echo $station['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php else: ?>
            <div class="mkdaiq-station-info">
                <?php _e('Station: ', 'mkd-air-quality'); ?> <span><?php echo $default_station; ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="mkdaiq-main">
        <canvas class="mkdaiq-chart" width="400" height="300"></canvas>
    </div>
</div>