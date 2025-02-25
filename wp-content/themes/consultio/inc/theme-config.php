<?php
if(!function_exists('consultio_configs')){
    function consultio_configs($value){
         
        $t_gradient_color_from = consultio_get_opt('gradient_color', ['from' => '#06ffdf']);

        $t_gradient_color_to = consultio_get_opt('gradient_color', ['to' => '#0042ff']);
        
        $configs = [
            'gradient' => [
                'color-from' => $t_gradient_color_from['from'],
                'color-to' => $t_gradient_color_to['to'],
            ],
               
        ];
        return $configs[$value];
    }
}
if(!function_exists('consultio_inline_styles')) {
    function consultio_inline_styles() {  
        
        $gradient_color        = consultio_configs('gradient');
        ob_start();
        echo ':root{';
            
            foreach ($gradient_color as $color => $value) {
                printf('--gradient-%1$s: %2$s;', $color, $value);
            }
            foreach ($gradient_color as $color => $value) {
                printf('--gradient-%1$s-rgb: %2$s;', $color, consultio_hex_rgb($value));
            }
            
        echo '}';

        return ob_get_clean();
         
    }
}
 