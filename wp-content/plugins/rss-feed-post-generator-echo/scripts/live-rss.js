"use strict"; 
var { registerBlockType } = wp.blocks;
var gcel = wp.element.createElement;

registerBlockType( 'rss-feed-post-generator-echo/live-rss', {
    title: 'Echo Live RSS',
    icon: 'rss',
    category: 'embed',
    attributes: {
        url : {
            default: '',
            type:   'string',
        },
        items : {
            default: '10',
            type:   'string',
        },
        orderby : {
            default: 'default',
            type:   'string',
        },
        title : {
            default: 'true',
            type:   'string',
        },
        excerpt : {
            default: '20',
            type:   'string',
        },
        cache : {
            default: '43200',
            type:   'string',
        },
        read_more : {
            default: 'true',
            type:   'string',
        },
        new_window : {
            default: 'true',
            type:   'string',
        },
        thumbnail : {
            default: 'true',
            type:   'string',
        },
        source : {
            default: 'true',
            type:   'string',
        },
        date : {
            default: 'true',
            type:   'string',
        },
        dofollow : {
            default: 'false',
            type:   'string',
        },
        ajax : {
            default: 'true',
            type:   'string',
        },
        layout : {
            default: 'thumbnail,title,content,postdata',
            type:   'string',
        },
        append_url : {
            default: '',
            type:   'string',
        },
        default_text : {
            default: '',
            type:   'string',
        },
        read_more_text : {
            default: '',
            type:   'string',
        },
        keep_content_tags : {
            default: '',
            type:   'string',
        }
    },
    keywords: ['list', 'posts', 'echo'],
    edit: (function( props ) {
		var url = props.attributes.url;
        var items = props.attributes.items;
        var orderby = props.attributes.orderby;
        var title = props.attributes.title;
        var excerpt = props.attributes.excerpt;
        var cache = props.attributes.cache;
        var read_more = props.attributes.read_more;
        var new_window = props.attributes.new_window;
        var thumbnail = props.attributes.thumbnail;
        var source = props.attributes.source;
        var date = props.attributes.date;
        var dofollow = props.attributes.dofollow;
        var ajax = props.attributes.ajax;
        var layout = props.attributes.layout;
        var append_url = props.attributes.append_url;
        var default_text = props.attributes.default_text;
        var read_more_text = props.attributes.read_more_text;
        var keep_content_tags = props.attributes.keep_content_tags;
		function updateMessage( event ) {
            props.setAttributes( { url: event.target.value} );
		}
        function updateMessage2( event ) {
            props.setAttributes( { items: event.target.value} );
		}
        function updateMessage3( event ) {
            props.setAttributes( { orderby: event.target.value} );
		}
        function updateMessage4( event ) {
            props.setAttributes( { title: event.target.value} );
		}
        function updateMessage5( event ) {
            props.setAttributes( { excerpt: event.target.value} );
		}
        function updateMessage6( event ) {
            props.setAttributes( { cache: event.target.value} );
		}
        function updateMessage7( event ) {
            props.setAttributes( { read_more: event.target.value} );
		}
        function updateMessage8( event ) {
            props.setAttributes( { new_window: event.target.value} );
		}
        function updateMessage9( event ) {
            props.setAttributes( { thumbnail: event.target.value} );
		}
        function updateMessage10( event ) {
            props.setAttributes( { source: event.target.value} );
		}
        function updateMessage11( event ) {
            props.setAttributes( { date: event.target.value} );
		}
        function updateMessage12( event ) {
            props.setAttributes( { dofollow: event.target.value} );
		}
        function updateMessage13( event ) {
            props.setAttributes( { ajax: event.target.value} );
		}
        function updateMessage14( event ) {
            props.setAttributes( { layout: event.target.value} );
		}
        function updateMessage15( event ) {
            props.setAttributes( { append_url: event.target.value} );
		}
        function updateMessage16( event ) {
            props.setAttributes( { default_text: event.target.value} );
		}
        function updateMessage17( event ) {
            props.setAttributes( { read_more_text: event.target.value} );
		}
        function updateMessage18( event ) {
            props.setAttributes( { keep_content_tags: event.target.value} );
		}
		return gcel(
			'div', 
			{ className: 'coderevolution_gutenberg_div' },
            gcel(
				'h4',
				{ className: 'coderevolution_gutenberg_title' },
                'Echo Live RSS ',
                gcel(
                    'div', 
                    {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                    ,
                    gcel(
                        'div', 
                        {className:'bws_hidden_help_text'},
                        'This block is used to display live contents from RSS feeds.'
                    )
                )
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'URL* (required): '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Set the feed URL from where you wish to display live feed items.'
                )
            ),
			gcel(
				'input',
				{ type:'url',placeholder:'Feed URL', value: url, onChange: updateMessage, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Item Count: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select how many items to display at max. Default is 10'
                )
            ),
			gcel(
				'input',
				{ type:'number',min:0,placeholder:'10', value: items, onChange: updateMessage2, className: 'coderevolution_gutenberg_input' }
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
                    'Select how to order imported items.'
                )
            ),
            gcel(
				'select',
				{ value: orderby, onChange: updateMessage3, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'default'},
                    'default'
                ), 
                gcel(
                    'option',
                    { value: 'date'},
                    'date'
                ), 
                gcel(
                    'option',
                    { value: 'date_reverse'},
                    'date_reverse'
                ), 
                gcel(
                    'option',
                    { value: 'random'},
                    'random'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Layout: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the layout of the feed items that will be displayed to users. Input a comma separated list of items. Default value is: thumbnail,title,content,postdata - you can mix these values up or add your own values. Example: <div class="my-custom-class">,thumbnail,title,content,postdata,</div>'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'thumbnail,title,content,postdata', value: layout, onChange: updateMessage14, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'URL Append: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Set a value to be added to each URL of the RSS feed items. You can add here your affiliate tracking tags, for example uid=332312'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'param=value', value: append_url, onChange: updateMessage15, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Titles: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to display item titles?'
                )
            ),
            gcel(
				'select',
				{ value: title, onChange: updateMessage4, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Excerpt Word Count: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the number of words of the excerpt.'
                )
            ),
			gcel(
				'input',
				{ type:'number',min:1,placeholder:'20', value: excerpt, onChange: updateMessage5, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Cache Timeout: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Select the cache timeout in seconds (how often should the feed items be refreshed).'
                )
            ),
			gcel(
				'input',
				{ type:'number',min:1,placeholder:'43200', value: cache, onChange: updateMessage6, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Read More: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to display item read more buttons?'
                )
            ),
            gcel(
				'select',
				{ value: read_more, onChange: updateMessage7, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'New Tab/Window: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to open read more links in a new tab/window?'
                )
            ),
            gcel(
				'select',
				{ value: new_window, onChange: updateMessage8, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Thumbnail: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to show a thumbnail in item listing? You can set: true, false or the size of the thumbnail, in pixels.'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'true', value: thumbnail, onChange: updateMessage9, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Source: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to show item\'s source in feed listing?'
                )
            ),
            gcel(
				'select',
				{ value: source, onChange: updateMessage10, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Date: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to show item\'s date in feed listing?'
                )
            ),
            gcel(
				'select',
				{ value: date, onChange: updateMessage11, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'DoFollow: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to set links as dofollow (remove nofollow)?'
                )
            ),
            gcel(
				'select',
				{ value: dofollow, onChange: updateMessage12, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Ajax Loader: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Do you want to enable ajax loading of items?'
                )
            ),
            gcel(
				'select',
				{ value: ajax, onChange: updateMessage13, className: 'coderevolution_gutenberg_select' }, 
                gcel(
                    'option',
                    { value: 'true'},
                    'true'
                ), 
                gcel(
                    'option',
                    { value: 'false'},
                    'false'
                )
            ),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Widget Default Text: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Set the default text which should appear in the widget, when the RSS fails to be loaded or parsed.'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'Sorry, there is a problem with this page right now. We are working on fixing it as soon as possible.', value: default_text, onChange: updateMessage16, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'Read More Link Text: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Set the "Read more" link text for feed items.'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'Read more', value: read_more_text, onChange: updateMessage17, className: 'coderevolution_gutenberg_input' }
			),
            gcel(
				'br'
			),
            gcel(
				'label',
				{ className: 'coderevolution_gutenberg_label' },
                'HTML Tag List To Keep In The Content: '
			),
            gcel(
                'div', 
                {className:'bws_help_box bws_help_box_right dashicons dashicons-editor-help'}
                ,
                gcel(
                    'div', 
                    {className:'bws_hidden_help_text'},
                    'Add a comma separated list of HTML tags to keep in the content.'
                )
            ),
			gcel(
				'input',
				{ type:'text',placeholder:'Read more', value: keep_content_tags, onChange: updateMessage18, className: 'coderevolution_gutenberg_input' }
			)
		);
    }),
    save: (function( props ) {
       return null;
    }),
} );