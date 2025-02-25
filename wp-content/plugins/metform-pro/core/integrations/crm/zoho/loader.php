<?php

namespace MetForm_Pro\Core\Integrations\Crm\Zoho;

use MetForm_Pro\Traits\Singleton;
use MetForm_Pro\Utils\Render;

defined('ABSPATH') || exit;

class Integration
{
    use Singleton;

    /**
     * @var mixed
     */
    private $parent_id;
    /**
     * @var mixed
     */
    private $sub_tab_id;
    /**
     * @var mixed
     */
    private $sub_tab_title;

    public function init()
    {
        /**
         *
         * Create a new tab in admin settings tab
         *
         */

        $this->parent_id = 'mf_crm';

        $this->sub_tab_id    = 'zoho';
        $this->sub_tab_title = 'Zoho';

        add_action('metform_after_store_form_data', [$this, 'create_contact'], 10, 4);
        add_action('metform_settings_subtab_' . $this->parent_id, [$this, 'sub_tab']);
        add_action('metform_settings_subtab_content_' . $this->parent_id, [$this, 'sub_tab_content']);
        add_action('wp_ajax_get_contact_fields', [$this, 'get_contact_fields']);
        add_action('wp_ajax_zoho_revoke_token', [$this, 'zoho_revoke_token']);

        add_action('init', [$this, 'setup_token']);
    }

    public function setup_token(){

        if(!current_user_can('manage_options')){
            return false;
        }

        $data = [];
        if( isset($_REQUEST['zoho']) && 
            isset($_REQUEST['state']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['state'])))){
            
            $data['access_token']  = isset($_REQUEST['access_token']) ? sanitize_text_field(wp_unslash($_REQUEST['access_token'])) : '';
            $data['refresh_token'] = isset($_REQUEST['refresh_token']) ? sanitize_text_field(wp_unslash($_REQUEST['refresh_token'])) : '';
            $data['expires_in']    = isset($_REQUEST['expires_in']) ? sanitize_text_field(wp_unslash($_REQUEST['expires_in'])) : '';
            
            update_option('mf_zoho_token_info', json_encode($data));
            set_transient('mf_zoho_token', $data['access_token'], ((int)$data['expires_in'] - 20));
            // after saving token redirect to crm section            
            wp_safe_redirect(admin_url('admin.php?page=metform-menu-settings#mf_crm'));
        }

        
    }
    public function sub_tab()
    {
        Render::sub_tab($this->sub_tab_title, $this->sub_tab_id);
    }

    public function contents()
    {
       $token =  get_option('mf_zoho_token_info');
        ?>
            <div class="mf-setting-input-group">
                <p class="description">
                    <?php if(!trim($token)): ?>
                    <a href="https://api.wpmet.com/public/zoho-api/auth.php?redirect_url=<?php echo esc_url(get_admin_url() . 'admin.php?page=metform-menu-settings') . "&state=" . wp_create_nonce() . "&section_id=mf-newsletter_integration"; ?>" class="button-primary mf-setting-btn"> <?php esc_html_e('Connect Zoho ', 'metform-pro'); ?> </a>
                    <?php  else: ?>

                        <p id="revoke_zoho" data-admin-url="<?php echo admin_url('admin-ajax.php'); ?>" data-zoho-nonce="<?php echo  esc_attr(wp_create_nonce('revoke_zoho')); ?>" class="button-primary mf-setting-btn"> <?php esc_html_e('Disconnect Zoho ', 'metform-pro'); ?> </a>
                    <?php endif; ?>
                </p>
            </div>
        <?php
    }

    public function zoho_revoke_token(){
    
        if(!isset($_POST['nonce']) 
           || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce']) ), 'revoke_zoho')
           || !current_user_can( 'manage_options' ) ){ 

            wp_send_json_error(esc_html__("You're not permitted", 'metform-pro')); 
        }
       
        $mf_zoho_token_info = json_decode(get_option('mf_zoho_token_info'), true);
        
        if(isset($mf_zoho_token_info['refresh_token'])){

            // Define the URL for token revocation
            $revoke_url = 'https://accounts.zoho.com/oauth/v2/token/revoke';

            // Define the parameters to be sent in the request body
            $request_body = array(
                'body' => array(
                    'token' => $mf_zoho_token_info['refresh_token']
                )
            );

            // Send a POST request to revoke the token
            $response = wp_remote_request($revoke_url, $request_body);
            
            if (!is_wp_error($response)) {
                $response_body = wp_remote_retrieve_body($response);
                $zoho_response = json_decode($response_body, true);
                // zoho will return status success
                if(isset($zoho_response['status']) && $zoho_response['status'] == 'success'){
                    $this->clear_data(); // clear data 
                }
            }
        }
        $this->clear_data(); //if no token clear data
    }

    /**
     * Delete zoho token info, delete zoho transient
     * Send success response
     */
    function clear_data(){
        
        delete_transient('mf_zoho_token_info');
        delete_option('mf_zoho_token_info');
        // send response after deleting
        wp_send_json_success();
    }

    public function sub_tab_content()
    {
        Render::sub_tab_content($this->sub_tab_id, [$this, 'contents']);
    }

    /**
     * @param $form_id
     * @param $form_data
     * @param $form_settings
     * @param $attributes
     * @return null
     */
    public function create_contact($form_id, $form_data, $form_settings, $attributes)
    {
        if (isset($form_settings['mf_zoho']) && $form_settings['mf_zoho'] == '1') {

            $settings_option = $this->get_access_token();
            
            $token = $settings_option['access_token'];

            $zoho_existing_fields = get_post_meta($form_id, 'mf_zoho_fields');
            $zoho_data = [];

            // if empty or not array or the specific index is not present close here
            if('' == $zoho_existing_fields || (!is_array($zoho_existing_fields) &&  !isset($zoho_existing_fields[0]))){                
                return;
            }

            // if empty or not array or the specific index is not present close here
            $m_data = isset($zoho_existing_fields[0]) && trim($zoho_existing_fields[0]) !== ''? json_decode($zoho_existing_fields[0], true) : false;
            if( !$m_data || !is_array($m_data)){
                return ;
            }

            foreach ( $m_data as $key => $value) {
                $zoho_data[$value] =  isset($form_data[$key]) ? $form_data[$key] : '';  

                // Converting Date Object for zoho api date format
                if('Date_of_Birth' == $value && !empty(trim($form_data[$key]))){
                   
                    $dateTime = \DateTime::createFromFormat('m-d-Y', $form_data[$key])->format('Y-m-d');

                    $zoho_data['Date_of_Birth'] =  $dateTime;   
                }
                if('Email_Opt_Out'  == $value && !empty(trim($form_data[$key]))){
                   // Zoho Email_Opt_Out  boolean value setting according to api data format
                    $zoho_data['Email_Opt_Out'] = isset($form_data[$key]) ? true : false;
                }
            }
            
            $url  = 'https://www.zohoapis.com/crm/v2/Contacts';
            
            $data = [
                'data' => [$zoho_data]
            ];
            
           $rr = wp_remote_post($url, [
                'method'  => 'POST',
                'timeout' => 45,
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $token,
                    'Content-Type'  => 'application/json; charset=utf-8'
                ],
                'body'    => json_encode($data)
            ]);
            
        }

        return;
    }
    public function get_contact_fields() {

        $settings_option = $this->get_access_token();

        if(!isset($settings_option['access_token'])){
            wp_send_json_error($settings_option['error']);
        }

        $token = $settings_option['access_token'];

        $response = wp_remote_get(
            'https://zohoapis.com/crm/v2/settings/layouts?module=Contacts',
                array(
                    'headers' => array(
                        'Authorization' => 'Bearer '.$token
                    ),
                    'sslverify' => false, // Set to true for SSL verification currently false
                )
            );
            
        if (is_array($response) && !is_wp_error($response)) {
            $response_body = [];
            $response_body['zoho_api_fields'] = wp_remote_retrieve_body($response);
            
            if(isset($_POST['formId'])){
                $response_body['zoho_existing_fields'] = get_post_meta($_POST['formId'], 'mf_zoho_fields');
            }

            wp_send_json_success($response_body);

        }
    }

    /**
     * Get token or refresh new token from zoho
     * @method get_access_token()
     * @return array
     * @since 3.5.0
     */
    public function get_access_token( ){
        // update_option('mf_zoho_token_info', '');
        // is token expired if yes get new token
        if(!get_transient('mf_zoho_token')){  
            $mf_zoho_token_info = json_decode(get_option('mf_zoho_token_info'), true);
            
            if(!isset($mf_zoho_token_info['refresh_token'])){
                return ['error' => sprintf('%1$s <a class="mf-zoho-connect-url" href="'.admin_url().'admin.php?page=metform-menu-settings#mf_crm">%2$s</a>',esc_html__('Token Not Found. Please','metform-pro'), esc_html__(' Connect Zoho &rarr;','metform-pro')) ];
            }
            // Refresh the token
            $response = wp_remote_get( 'https://api.wpmet.com/public/zoho-api/refresh-token.php?refresh_token='.  $mf_zoho_token_info['refresh_token'] );
        
            // Check if request is successful
            if(isset($response['response']['code']) &&  $response['response']['code'] === 200){

                
                $responseBody = isset ($response['body']) ? json_decode($response['body'], true) : [];

                if(!isset($responseBody['access_token'])){
                    return ['error' => esc_html__("Access Token Not Found", 'metform-pro')];
                }
                // Save new token values
                $token_data = [];
                $token_data['access_token']  = isset($responseBody['access_token']) ? sanitize_text_field($responseBody['access_token']): '' ;
                $token_data['refresh_token'] = isset($mf_zoho_token_info['refresh_token']) ? sanitize_text_field($mf_zoho_token_info['refresh_token']) : '' ;
                $token_data['expires_in']    = isset($responseBody['expires_in'])? sanitize_text_field($responseBody['expires_in']) : '';

                // Save the results in a transient named
                set_transient( 'mf_zoho_token', $responseBody['access_token'], ((int)$responseBody['expires_in'] - 20));
                // Update mf_zoho_token_info options
                update_option('mf_zoho_token_info', json_encode($token_data));

                return $token_data;
            }else{

                return ['error' => esc_html__("Connection Failed", 'metform-pro')];
            }
        }

        $token_data = json_decode(get_option('mf_zoho_token_info'), true);

        // if no token need to connect zoho
        if(!isset($token_data['access_token'])){
            return ['error' => sprintf('%1$s <a class="mf-zoho-connect-url" href="'.admin_url().'admin.php?page=metform-menu-settings#mf_crm">%2$s</a>',esc_html__('Token Not Found. Please','metform-pro'), esc_html__(' Connect Zoho &rarr;','metform-pro')) ];
        }
        return $token_data;
    }
}

Integration::instance()->init();