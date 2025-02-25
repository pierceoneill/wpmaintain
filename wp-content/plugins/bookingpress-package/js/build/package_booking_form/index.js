( function( blocks, components, i18n, element){
	var el                 = wp.element.createElement,
	registerBlockType      = wp.blocks.registerBlockType;
	source                 = wp.blocks.source;
	var __                 = wp.i18n.__;
	const bookingpresslogo = el( 'img',{ src:__BOOKINGPRESSIMAGEURL + '/bookingpress_menu_icon.png' } );
	registerBlockType(
		'bookingpress/bookingpress-package-form',
		{
			title:__( 'Package Booking Forms - WordPress Booking Plugin' ),
			icon:bookingpresslogo,
			category:'bookingpress',
			keywords:[__( 'bookingpress' ),__( 'package' )],			
			attributes: {
				short_code: {
					type: 'string',
					default: '[bookingpress_package_form]'
				},
			},
			edit:function(props){
				if (props.name == 'bookingpress/bookingpress-package-form') {
					return 	el(
						'div',
						{},
						props.attributes.short_code
					)
				}
			},
			save:function(props) {
				return (
				el(
					'div',
					{},
					props.attributes.short_code
				)
				)
			}
		}
	);
})(
	window.wp.blocks,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
	window.wp.editor
);
