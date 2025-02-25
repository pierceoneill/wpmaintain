<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Admin_Notice_Display')) {

    class Admin_Notice_Display {

        /**
         * Message to be shown
         */
        private $message;

        /**
         * CSS classes to apply on the notice div
         */
        private $css_classes = array('notice');

        /**
         * @param  string  $message      Message to be shown
         * @param  array   $css_classes  CSS classes to apply on the notice div
         */
        public function __construct($message, $css_classes) {

            $this->message = $message;

            if (!empty($css_classes) && is_array($css_classes)) {
                $this->css_classes = array_merge($this->css_classes, $css_classes);
            }

            add_action('admin_notices', array($this, 'display_admin_notice'));
        }

        /**
         * Displays admin notice on success, error, warning, etc.
         *
         * @return void
         */
        public function display_admin_notice() {
            ?>
            <div class="<?php echo implode(' ', $this->css_classes); ?>">
                <p><?php echo $this->message; ?></p>
            </div>
            <?php
        }
    }

}