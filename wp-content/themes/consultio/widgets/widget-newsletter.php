<?php
class Newsletter_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'newsletter_widget',
            esc_html__('* Newsletter', 'consultio'),
            array('description' => esc_html__('Newsletter Widget', 'consultio'),)
        );
    }

    function widget($args, $instance) {

        extract($args);

        $title = isset($instance['title']) ? (!empty($instance['title']) ? $instance['title']: '') : '';
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $introduction = isset($instance['introduction']) ? (!empty($instance['introduction']) ? $instance['introduction']: '') : '';
        $email_label = isset($instance['email_label']) ? (!empty($instance['email_label']) ? $instance['email_label']: '') : '';
        $contact_email = isset($instance['contact_email']) ? (!empty($instance['contact_email']) ? $instance['contact_email']: '') : '';
        $contact_phone = isset($instance['contact_phone']) ? (!empty($instance['contact_phone']) ? $instance['contact_phone']: '') : '';
        $phone_result = preg_replace('#[ () ]*#', '', $contact_phone);
        ?>
        <div class="ct-newsletter widget">
            <?php if(!empty($title)) :
                echo wp_kses_post($args['before_title']) . wp_kses_post($title) . wp_kses_post($args['after_title']);
            endif; ?>
            <div class="ct-newsletter-holder">
                <div class="ct-newsletter-introduction"><?php echo wp_kses_post( $introduction ); ?></div>
                <?php echo do_shortcode( '[newsletter_form contact_email="'.esc_html__('Subscribe', 'consultio').'"][newsletter_field name="email" label="'.$email_label.'"][/newsletter_form]' ); ?>
            </div>
        </div>
    <?php }

    function update( $new_instance, $old_instance ) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['introduction'] = strip_tags($new_instance['introduction']);
         $instance['email_label'] = strip_tags($new_instance['email_label']);
         $instance['contact_email'] = strip_tags($new_instance['contact_email']);
         $instance['contact_phone'] = strip_tags($new_instance['contact_phone']);

         return $instance;
    }

    function form( $instance ) {
         $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
         $introduction = isset($instance['introduction']) ? esc_attr($instance['introduction']) : '';
         $email_label = isset($instance['email_label']) ? esc_attr($instance['email_label']) : '';
         $contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
         $contact_phone = isset($instance['contact_phone']) ? esc_attr($instance['contact_phone']) : '';

         ?>
        <p><label for="<?php echo esc_url($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title', 'consultio' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

        <p><label for="<?php echo esc_url($this->get_field_id('email_label')); ?>"><?php esc_html_e( 'Email Label', 'consultio' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('email_label') ); ?>" name="<?php echo esc_attr( $this->get_field_name('email_label') ); ?>" type="text" value="<?php echo esc_attr( $email_label ); ?>" /></p>
    <?php
    }

}
function register_newsletter_widget() {
    if(function_exists('ct_register_wp_widget')){
        ct_register_wp_widget( 'Newsletter_Widget' );
    }
}
add_action('widgets_init', 'register_newsletter_widget');
