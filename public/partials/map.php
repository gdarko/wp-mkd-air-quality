<?php
/* @var array $units */
/* @var string $unit */
/* @var string $date */
?>

<div class="mkdaiq mkdaiq-map-element" data-date="<?php echo $date; ?>" data-unit="<?php echo $unit; ?>">
    <div class="mkdaiq-header">
        <div class="mkdaiq-header-filter">
            <label><?php _e( 'Date', 'mkd-air-quality' ); ?></label>
            <input type="text" name="date" class="mkdaiq-control mkdaiq-select-date">
        </div>
        <div class="mkdaiq-header-filter">
            <label><?php _e( 'Parameter', 'mkd-air-quality' ); ?></label>
            <select class="mkdaiq-control mkdaiq-select-unit">
                <?php foreach($units as $key => $u): ?>
                <option value="<?php echo $key; ?>" <?php selected($unit, $key); ?>><?php echo $u['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mkdaiq-main">
        <div class="mkdaiq-map" id="mkdaiq-map-<?php echo time(); ?>"></div>
    </div>
</div>