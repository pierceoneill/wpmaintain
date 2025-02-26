<?php defined( 'ABSPATH' ) || exit; // Exit if accessed directly ?>
<div class="bookly-form-group">
    <label for="bookly-b-<?php echo $type ?>-<?php echo $form_id ?>"><?php echo $title ?></label>
    <div>
        <select class="bookly-js-select-birthday-<?php echo $type ?>" id="bookly-b-<?php echo $type ?>-<?php echo $form_id ?>">
            <option value=""><?php echo esc_html( $empty ) ?></option>
            <?php foreach ( $options as $value => $option ) : ?>
                <option value="<?php echo $value ?>"<?php selected( $selected_value, $value ) ?>><?php echo esc_html( $option ) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="bookly-js-select-birthday-<?php echo $type ?>-error bookly-label-error"></div>
</div>