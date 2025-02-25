<?php
// Add custom field to column
if(!function_exists('consultio_custom_column_params')){
    add_filter('ct-custom-column/custom-params', 'consultio_custom_column_params');
    function consultio_custom_column_params(){
        return array(
            'sections' => array(
                array(
					'name'     => 'custom_section',
					'label'    => esc_html__( 'Custom Settings', 'consultio' ),
					'tab'      => \Elementor\Controls_Manager::TAB_LAYOUT,
					'controls' => array(
                        array(
							'name'    => 'col_offset',
							'label'   => esc_html__( 'Offset Column', 'consultio' ),
							'type'    => \Elementor\Controls_Manager::SELECT,
							'options' => array(
								''           => esc_html__( 'None', 'consultio' ),
								'left' => esc_html__( 'Left', 'consultio' ),
                                'right' => esc_html__( 'Right', 'consultio' ),
                            ),
                            'default' => '',
                            'prefix_class' => 'ct-column-offset-'
                        )
                    )
                )
            )
        );
    }
}