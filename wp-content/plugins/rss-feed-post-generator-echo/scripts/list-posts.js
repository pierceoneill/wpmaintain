"use strict"; 
var { registerBlockType } = wp.blocks;
var gcel = wp.element.createElement;

registerBlockType( 'rss-feed-post-generator-echo/echo-list', {
    title: 'Echo List Posts',
    icon: 'rss',
    category: 'embed',
    attributes: {
        ruletype : {
            default: '',
            type:   'string',
        },
        ruleid : {
            default: '',
            type:   'string',
        },
        category : {
            default: '',
            type:   'string',
        },
        posts : {
            default: '50',
            type:   'string',
        },
        orderby : {
            default: 'title',
            type:   'string',
        },
        order : {
            default: 'ASC',
            type:   'string',
        },
        type : {
            default: 'any',
            type:   'string',
        },
        link_source : {
            default: 'any',
            type:   'string',
        }
    },
    keywords: ['list', 'posts', 'echo'],
    edit: (function( props ) {
		var ruletype = props.attributes.ruletype;
        var ruleid = props.attributes.ruleid;
        var category = props.attributes.category;
        var posts = props.attributes.posts;
        var orderby = props.attributes.orderby;
        var order = props.attributes.order;
        var type = props.attributes.type;
        var link_source = props.attributes.link_source;
		function updateMessage( event ) {
            props.setAttributes( { ruletype: event.target.value} );
		}
        function updateMessage2( event ) {
            props.setAttributes( { ruleid: event.target.value} );
		}
        function updateMessage3( event ) {
            props.setAttributes( { category: event.target.value} );
		}
        function updateMessage4( event ) {
            props.setAttributes( { posts: event.target.value} );
		}
        function updateMessage5( event ) {
            props.setAttributes( { orderby: event.target.value} );
		}
        function updateMessage6( event ) {
            props.setAttributes( { order: event.target.value} );
		}
        function updateMessage7( event ) {
            props.setAttributes( { type: event.target.value} );
		}
        function updateMessage8( event ) {
            props.setAttributes( { link_source: event.target.value} );
		}
		return gcel(
			'div', 
			{ className: 'coderevolution_gutenberg_div' },
            gcel(
				'h4',
				{ className: 'coderevolution_gutenberg_title' },
                'Echo List Posts ',
                gcel(
                    'div', 
                    {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                    ,
                    gcel(
                        'div', 
                        {className:'bws_hidden_help_text'},
                        'This block is used to list posts generated by this plugin. It is a simple way to list posts.'
                    )
                )
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Rule Type: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the source of the posts that will be listed.'
                )
            ),
			gcel(
				'select',
				{ value: ruletype, onChange: updateMessage, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 0},
                    'RSS To Post Rules'
                ),
                gcel(
                    'option',
                    { value: ''},
                    'Any'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Rule ID: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the ID of the rule you wish to list posts from. To list all posts from a specific rule, leave this field blank.'
                )
            ),
			gcel(
				'input',
				{ type:'number',min:0,placeholder:'Rule id to list', value: ruleid, onChange: updateMessage2, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Category Slug: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the post category slug from where you wish to list posts.'
                )
            ),
			gcel(
				'textarea',
				{ rows:1,placeholder:'Category slug', value: category, onChange: updateMessage3, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Posts Per Page: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the number of posts to be shown at max.'
                )
            ),
			gcel(
				'input',
				{ type:'number',min:1,placeholder:'10', value: posts, onChange: updateMessage4, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Order By: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select how to order results.'
                )
            ),
            gcel(
				'select',
				{ value: orderby, onChange: updateMessage5, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'none'},
                    'none'
                ), 
                gcel(
                    'option',
                    { value: 'date'},
                    'date'
                ), 
                gcel(
                    'option',
                    { value: 'ID'},
                    'ID'
                ), 
                gcel(
                    'option',
                    { value: 'author'},
                    'author'
                ), 
                gcel(
                    'option',
                    { value: 'title'},
                    'title'
                ), 
                gcel(
                    'option',
                    { value: 'name'},
                    'name'
                ), 
                gcel(
                    'option',
                    { value: 'type'},
                    'type'
                ), 
                gcel(
                    'option',
                    { value: 'modified'},
                    'modified'
                ), 
                gcel(
                    'option',
                    { value: 'parent'},
                    'parent'
                ), 
                gcel(
                    'option',
                    { value: 'rand'},
                    'rand'
                ), 
                gcel(
                    'option',
                    { value: 'comment_count'},
                    'comment_count'
                ), 
                gcel(
                    'option',
                    { value: 'relevance'},
                    'relevance'
                ), 
                gcel(
                    'option',
                    { value: 'menu_order'},
                    'menu_order'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Sort By: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select how to sort results.'
                )
            ),
            gcel(
				'select',
				{ value: order, onChange: updateMessage6, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'ASC'},
                    'ASC'
                ), 
                gcel(
                    'option',
                    { value: 'DESC'},
                    'DESC'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Post Type: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the post type to be listed. You can input a comma separated list of multiple post types (custom post types supported).'
                )
            ),
			gcel(
				'textarea',
				{ rows:1,placeholder:'post', value: type, onChange: updateMessage7, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Link Directly to Source: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select if you want to link the post listings directly to their original source URLs.'
                )
            ),
			gcel(
				'textarea',
				{ rows:1,placeholder:'post', value: link_source, onChange: updateMessage8, className: 'coderevolution_gutenberg_input' }
			)
		);
    }),
    save: (function( props ) {
       return null;
    }),
} );