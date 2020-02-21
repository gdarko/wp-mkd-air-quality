<?php
/* @var array $units */
/* @var string $unit */
/* @var string $date */
/* @var int $units_selector */
/* @var int $zoom */
?>

<div class="mkdaiq mkdaiq-map-element" data-date="<?php echo $date; ?>" data-unit="<?php echo $unit; ?>" data-zoom="<?php echo $zoom; ?>">
    <?php if(intval($units_selector)): ?>
    <div class="mkdaiq-header">
        <div class="mkdaiq-header-filter">
            <label><?php _e( 'Unit:', 'wp-mkd-air-quality' ); ?></label>
            <select class="mkdaiq-control mkdaiq-select-unit">
                <?php foreach($units as $key => $u): ?>
                <option value="<?php echo $key; ?>" <?php selected($unit, $key); ?>><?php echo $u['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>
    <div class="mkdaiq-main">
        <div class="mkdaiq-map" id="mkdaiq-map-<?php echo time(); ?>"></div>
    </div>
</div>