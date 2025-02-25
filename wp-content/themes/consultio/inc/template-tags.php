<?php
/**
 * Custom template tags for this theme.
 *
 * @package Consultio
 */

/**
 * Header layout
 **/
function consultio_page_loading()
{
    $page_loading = consultio_get_opt( 'show_page_loading', false );
    $loading_type = consultio_get_opt( 'loading_type', 'style1');
    $loading_img = consultio_get_opt( 'loading_img');

    $loading_page = consultio_get_page_opt( 'loading_page', 'themeoption');
    $loading_type_page = consultio_get_page_opt( 'loading_type', 'style1');

    if($loading_page == 'custom') {
        $loading_type = $loading_type_page;
    }
    if($page_loading) { ?>
        <div id="ct-loadding" class="ct-loader <?php echo esc_attr($loading_type); ?>">
            <?php switch ( $loading_type )
            {
                case 'style2': ?>
                    <div class="ct-spinner2"></div>
                    <?php break;

                case 'style3': ?>
                    <div class="ct-spinner3">
                      <div class="double-bounce1"></div>
                      <div class="double-bounce2"></div>
                    </div>
                    <?php break;

                case 'style4': ?>
                    <div class="ct-spinner4">
                      <div class="rect1"></div>
                      <div class="rect2"></div>
                      <div class="rect3"></div>
                      <div class="rect4"></div>
                      <div class="rect5"></div>
                    </div>
                    <?php break;

                case 'style5': ?>
                    <div class="ct-spinner5">
                      <div class="bounce1"></div>
                      <div class="bounce2"></div>
                      <div class="bounce3"></div>
                    </div>
                    <?php break;

                case 'style6': ?>
                    <div class="ct-cube-grid">
                      <div class="ct-cube ct-cube1"></div>
                      <div class="ct-cube ct-cube2"></div>
                      <div class="ct-cube ct-cube3"></div>
                      <div class="ct-cube ct-cube4"></div>
                      <div class="ct-cube ct-cube5"></div>
                      <div class="ct-cube ct-cube6"></div>
                      <div class="ct-cube ct-cube7"></div>
                      <div class="ct-cube ct-cube8"></div>
                      <div class="ct-cube ct-cube9"></div>
                    </div>
                    <?php break;

                case 'style7': ?>
                    <div class="ct-folding-cube">
                      <div class="ct-cube1 ct-cube"></div>
                      <div class="ct-cube2 ct-cube"></div>
                      <div class="ct-cube4 ct-cube"></div>
                      <div class="ct-cube3 ct-cube"></div>
                    </div>
                    <?php break;

                case 'style8': ?>
                    <div class="ct-loading-stairs">
                        <div class="loader-bar"></div>
                        <div class="loader-bar"></div>
                        <div class="loader-bar"></div>
                        <div class="loader-bar"></div>
                        <div class="loader-bar"></div>
                        <div class="loader-ball"></div>
                    </div>
                    <?php break;

                case 'style9': ?>
                    <div class="ct-dual-ring">
                    </div>
                    <?php break;

                case 'style10': ?>
                    <div class="loading-spinner">
                        <div class="loading-dot1"></div>
                        <div class="loading-dot2"></div>
                    </div>
                    <?php break;

                case 'style11': ?>
                    <div class="loading-spinner"></div>
                    <?php break;

                case 'style12': ?>
                    <div class="ct-dot-square">
                    </div>
                    <?php break;

                case 'style13': ?>
                    <div class="ct-spinner5">
                      <div class="bounce1"></div>
                      <div class="bounce2"></div>
                      <div class="bounce3"></div>
                    </div>
                    <?php break;

                case 'style14': ?>
                    <?php if(!empty($loading_img['url'])) : ?>
                        <div class="ct-loading-image">
                            <img src="<?php echo esc_url($loading_img['url']); ?>" alt="<?php echo esc_html__('Loading', 'consultio'); ?>" />
                        </div>
                    <?php endif; ?>
                    <?php break;

                default: ?>
                    <div class="loading-spin">
                        <div class="spinner">
                            <div class="right-side"><div class="bar"></div></div>
                            <div class="left-side"><div class="bar"></div></div>
                        </div>
                        <div class="spinner color-2">
                            <div class="right-side"><div class="bar"></div></div>
                            <div class="left-side"><div class="bar"></div></div>
                        </div>
                    </div>
                    <?php break;
            } ?>
        </div>
    <?php }
}

/**
 * Header layout
 **/
function consultio_header_layout()
{
    if(is_singular('elementor_library')) return;
    
    $header_type = consultio_get_opt( 'header_type', 'layout' );
    $header_type_page = consultio_get_page_opt( 'header_type', 'themeoption' );
    if(isset($header_type_page) && !empty($header_type_page) && $header_type_page !== 'themeoption') {
        $header_type = $header_type_page;
    }

    if($header_type == 'layout') {
        $header_layout = consultio_get_opt( 'header_layout', '1' );
        $custom_header = consultio_get_page_opt( 'custom_header', '0' );
        if ( $custom_header == '1' && $header_type_page != 'themeoption' && !is_singular('service') ) {
            $page_header_layout = consultio_get_page_opt('header_layout');
            $header_layout = $page_header_layout;
            if($header_layout == '0') {
                return;
            }
        }

        $s_custom_header = consultio_get_opt( 's_custom_header', false );
        $s_header_layout = consultio_get_opt( 's_header_layout', '1' );
        if(is_search() && $s_custom_header == '1' && isset($s_header_layout)) {
            $header_layout = $s_header_layout;
        }

        get_template_part( 'template-parts/header-layout', $header_layout );

        $h_address = consultio_get_opt( 'h_address', '' );
        $h_address_label = consultio_get_opt( 'h_address_label', '' );
        $h_phone = consultio_get_opt( 'h_phone', '' );
        $h_phone_label = consultio_get_opt( 'h_phone_label', '' );
        $h_time = consultio_get_opt( 'h_time', '' );
        $h_time_label = consultio_get_opt( 'h_time_label', '' );
        $h_custom_menu = consultio_get_page_opt('h_custom_menu');
        if($header_layout == '5') { ?>
            <div class="ct-header-popup-wrap">
                <div class="ct-header-popup-inner">
                    <div class="ct-header-popup-hidden">
                        <div class="ct-menu-close"><i class="ct-icon-close ct-center"></i></div>
                        <div class="ct-header-popup-holder">
                            <div class="ct-header-popup-logo">
                                <?php get_template_part( 'template-parts/header-branding' ); ?>
                            </div>
                            <div class="ct-header-popup-menu">
                                <?php  if ( has_nav_menu( 'primary' ) ) {
                                    $attr_menu = array(
                                        'theme_location' => 'primary',
                                        'container'  => '',
                                        'menu_id'    => 'ct-main-menu-popup',
                                        'menu_class' => 'ct-main-menu-popup clearfix',
                                        'link_before'     => '<span>',
                                        'link_after'      => '</span>',
                                        'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
                                    );
                                    if(isset($h_custom_menu) && !empty($h_custom_menu)) {
                                        $attr_menu['menu'] = $h_custom_menu;
                                    }
                                    wp_nav_menu( $attr_menu );
                                } ?>
                            </div>
                            <div class="ct-header-meta">
                                <?php if(!empty($h_address)) : ?>
                                    <div class="ct-header-address">
                                        <div class="h-item-icon">
                                            <i class="far fac-globe"></i>
                                        </div>
                                        <div class="h-item-meta">
                                            <label><?php echo esc_attr($h_address_label); ?></label>
                                            <span><?php echo esc_attr($h_address); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($h_phone)) : ?>
                                    <div class="ct-header-call">
                                        <div class="h-item-icon">
                                            <i class="far fac-phone"></i>
                                        </div>
                                        <div class="h-item-meta">
                                            <label><?php echo esc_attr($h_phone_label); ?></label>
                                            <span><?php echo esc_attr($h_phone); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($h_time)) : ?>
                                    <div class="ct-header-address">
                                        <div class="h-item-icon">
                                            <i class="far fac-clock"></i>
                                        </div>
                                        <div class="h-item-meta">
                                            <label><?php echo esc_attr($h_time_label); ?></label>
                                            <span><?php echo esc_attr($h_time); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        get_template_part( 'template-parts/header-layout', 'elementor' );
    }
}

function consultio_header_elementor_popup() { 
    $h_popup_logo = consultio_get_opt( 'h_popup_logo' );
    ?>
    <div class="ct-header-elementor-popup">
        <div class="ct-close"><i class="ct-icon-close ct-center"></i></div>
        <div class="ct-header-popup-inner">
            <div class="ct-header-popup-scroll">
                <div class="ct-header-popup-main">
                    <?php if(!empty($h_popup_logo['url'])) : ?>
                        <div class="ct-header-popup-logo">
                            <?php 
                                printf(
                                    '<a href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s"/></a>',
                                    esc_url( home_url( '/' ) ),
                                    esc_attr( get_bloginfo( 'name' ) ),
                                    esc_url( $h_popup_logo['url'] )
                                );
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php  if ( has_nav_menu( 'menu-popup' ) ) { ?>
                        <div class="elementor-popup-menu">
                            <?php $attr_menu = array(
                                'theme_location' => 'menu-popup',
                                'container'  => '',
                                'link_before'     => '<span>',
                                'link_after'      => '</span>',
                                'walker'         => class_exists( 'EFramework_Mega_Menu_Walker' ) ? new EFramework_Mega_Menu_Walker : '',
                            );
                            wp_nav_menu( $attr_menu ); ?>
                        </div>
                    <?php } ?>
                    <div class="elementor-popup-social">
                        <?php consultio_social_header_popup(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

/**
 * Page title layout
 **/
function consultio_page_title_layout()
{
    get_template_part( 'template-parts/page-title', '' );
}

/**
 * Footer
 **/
function consultio_footer()
{
    if(is_singular('elementor_library')) return;
    get_template_part( 'template-parts/footer-layout', 'custom' );
}

/**
 * Set primary content class based on sidebar position
 *
 * @param  string $sidebar_pos
 * @param  string $extra_class
 */
function consultio_primary_class( $sidebar_pos, $extra_class = '' )
{
    if ( class_exists( 'WooCommerce' ) && (is_product_category()) || class_exists( 'WooCommerce' ) && (is_shop()) ) :
        $sidebar_load = 'sidebar-shop';
    elseif (is_page()) :
        $sidebar_load = 'sidebar-page';
    else :
        $sidebar_load = 'sidebar-blog';
    endif;

    if ( is_active_sidebar( $sidebar_load ) ) {
        $class = array( trim( $extra_class ) );
        switch ( $sidebar_pos )
        {
            case 'left':
                $class[] = 'content-has-sidebar float-right col-xl-9 col-lg-8 col-md-12 col-sm-12';
                break;

            case 'right':
                $class[] = 'content-has-sidebar float-left col-xl-9 col-lg-8 col-md-12 col-sm-12';
                break;

            default:
                $class[] = 'content-full-width col-12';
                break;
        }

        $class = implode( ' ', array_filter( $class ) );

        if ( $class )
        {
            echo ' class="' . esc_html($class) . '"';
        }
    } else {
        echo ' class="content-area col-12"'; 
    }
}

/**
 * Set secondary content class based on sidebar position
 *
 * @param  string $sidebar_pos
 * @param  string $extra_class
 */
function consultio_secondary_class( $sidebar_pos, $extra_class = '' )
{
    if ( class_exists( 'WooCommerce' ) && (is_product_category()) ) :
        $sidebar_load = 'sidebar-shop';
    elseif (is_page()) :
        $sidebar_load = 'sidebar-page';
    else :
        $sidebar_load = 'sidebar-blog';
    endif;

    if ( is_active_sidebar( $sidebar_load ) ) {
        $class = array(trim($extra_class));
        switch ($sidebar_pos) {
            case 'left':
                $class[] = 'widget-has-sidebar sidebar-fixed col-xl-3 col-lg-4 col-md-12 col-sm-12';
                break;

            case 'right':
                $class[] = 'widget-has-sidebar sidebar-fixed col-xl-3 col-lg-4 col-md-12 col-sm-12';
                break;

            default:
                break;
        }

        $class = implode(' ', array_filter($class));

        if ($class) {
            echo ' class="' . esc_html($class) . '"';
        }
    }
}


/**
 * Prints HTML for breadcrumbs.
 */
function consultio_breadcrumb()
{
    if ( ! class_exists( 'CT_Breadcrumb' ) )
    {
        return;
    }

    $breadcrumb = new CT_Breadcrumb();
    $entries = $breadcrumb->get_entries();

    if ( empty( $entries ) )
    {
        return;
    }

    ob_start();

    foreach ( $entries as $entry )
    {
        $entry = wp_parse_args( $entry, array(
            'label' => '',
            'url'   => ''
        ) );

        if ( empty( $entry['label'] ) )
        {
            continue;
        }

        echo '<li>';

        if ( ! empty( $entry['url'] ) )
        {
            printf(
                '<a class="breadcrumb-entry" href="%1$s">%2$s</a>',
                esc_url( $entry['url'] ),
                esc_attr( $entry['label'] )
            );
        }
        else
        {
            printf( '<span class="breadcrumb-entry" >%s</span>', esc_html( $entry['label'] ) );
        }

        echo '</li>';
    }

    $output = ob_get_clean();

    if ( $output )
    {
        printf( '<ul class="ct-breadcrumb">%s</ul>', wp_kses_post($output));
    }
}


function consultio_entry_link_pages()
{
    wp_link_pages( array(
        'before'      => '<div class="page-links">',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
    ) );
}


if ( ! function_exists( 'consultio_entry_excerpt' ) ) :
    /**
     * Print post excerpt based on length.
     *
     * @param  integer $length
     */
    function consultio_entry_excerpt()
    {
        $excerpt_length = consultio_get_opt( 'excerpt_length', '50' );
        $ct_the_excerpt = get_the_excerpt();
        if(!empty($ct_the_excerpt)) {
            echo wp_trim_words( $ct_the_excerpt, $excerpt_length, $more = null );
        } else {
            echo wp_kses_post(consultio_get_the_excerpt( $excerpt_length ));
        }
    }
endif;


if(!function_exists('consultio_ajax_paginate_links')){
    function consultio_ajax_paginate_links($link){
        $parts = parse_url($link);
        if( !isset($parts['query']) ) return $link;
        parse_str($parts['query'], $query);
        if(isset($query['page']) && !empty($query['page'])){
            return '#' . $query['page'];
        }
        else{
            return '#1';
        }
    }
}

add_action( 'wp_ajax_consultio_get_pagination_html', 'consultio_get_pagination_html' );
add_action( 'wp_ajax_nopriv_consultio_get_pagination_html', 'consultio_get_pagination_html' );
if(!function_exists('consultio_get_pagination_html')){
    function consultio_get_pagination_html(){
        try{
            if(!isset($_POST['query_vars'])){
                throw new Exception(__('Something went wrong while requesting. Please try again!', 'consultio'));
            }
            $query = new WP_Query($_POST['query_vars']);
            ob_start();
            consultio_posts_pagination( $query,  true );
            $html = ob_get_clean();
            wp_send_json(
                array(
                    'status' => true,
                    'message' => esc_attr__('Load Successfully!', 'consultio'),
                    'data' => array(
                        'html' => $html,
                        'query_vars' => $_POST['query_vars'],
                        'post' => $query->have_posts()
                    ),
                )
            );
        }
        catch (Exception $e){
            wp_send_json(array('status' => false, 'message' => $e->getMessage()));
        }
        die;
    }
}

/**
 * Prints posts pagination based on query
 *
 * @param  WP_Query $query     Custom query, if left blank, this will use global query ( current query )
 * @return void
 */
function consultio_posts_pagination( $query = null, $ajax = false )
{
    if($ajax){
        add_filter('paginate_links', 'consultio_ajax_paginate_links');
    }

    $classes = array();

    if ( empty( $query ) )
    {
        $query = $GLOBALS['wp_query'];
    }

    if ( empty( $query->max_num_pages ) || ! is_numeric( $query->max_num_pages ) || $query->max_num_pages < 2 )
    {
        return;
    }

    $paged = $query->get( 'paged', '' );

    if ( ! $paged && is_front_page() && ! is_home() )
    {
        $paged = $query->get( 'page', '' );
    }

    $paged = $paged ? intval( $paged ) : 1;

    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) )
    {
        wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    $html_prev = '<i class="far fac-angle-left"></i>';
    $html_next = '<i class="far fac-angle-right"></i>';
    $paginate_links_args = array(
        'base'     => $pagenum_link,
        'total'    => $query->max_num_pages,
        'current'  => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => $html_prev,
        'next_text' => $html_next,
    );
    if($ajax){
        $paginate_links_args['format'] = '?page=%#%';
    }
    $links = paginate_links( $paginate_links_args );
    if ( $links ):
    ?>
    <nav class="navigation posts-pagination <?php echo esc_attr($ajax?'ajax':''); ?>">
        <div class="posts-page-links">
            <?php echo wp_kses_post($links); ?>
        </div>
    </nav>
    <?php
    endif;
}

/**
 * Prints archive meta on blog
 */
if ( ! function_exists( 'consultio_archive_meta' ) ) :
    function consultio_archive_meta() {
        $archive_date_on = consultio_get_opt( 'archive_date_on', true );
        $archive_author_on = consultio_get_opt( 'archive_author_on', true );
        $archive_categories_on = consultio_get_opt( 'archive_categories_on', false );
        $archive_comments_on = consultio_get_opt( 'archive_comments_on', true );
        if($archive_author_on || $archive_comments_on || $archive_categories_on || $archive_date_on) : ?>
            <ul class="entry-meta">
                <?php if ($archive_date_on && !has_post_thumbnail()) : ?>
                    <li class="item-date"><i class="fac fac-calendar-alt"></i><?php echo get_the_date(); ?></li>
                <?php endif; ?>
                <?php if($archive_author_on) : ?>
                    <li class="item-author">
                        <i class="fac fac-user"></i><?php the_author_posts_link(); ?>
                    </li>
                <?php endif; ?>
                <?php if($archive_categories_on) : ?>
                    <li class="item-category"><i class="fac fac-folder-open"></i><?php the_terms( get_the_ID(), 'category', '', ', ' ); ?></li>
                <?php endif; ?>
                <?php if($archive_comments_on) : ?>
                    <li class="item-comment"><i class="fac fac-comments"></i><a href="<?php the_permalink(); ?>"><?php echo comments_number(esc_attr__('No Comments', 'consultio'),esc_attr__('Comment: 1', 'consultio'),esc_attr__('Comments: %', 'consultio')); ?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; }
endif;

if ( ! function_exists( 'consultio_post_meta' ) ) :
    function consultio_post_meta() {
        $post_date_on = consultio_get_opt( 'post_date_on', true );
        $post_author_on = consultio_get_opt( 'post_author_on', true );
        if($post_author_on || $post_date_on) : ?>
            <ul class="entry-meta">
                <?php if($post_date_on) : ?>
                    <li class="item-date"><i class="fac fac-calendar-alt"></i><?php echo get_the_date(); ?></li>
                <?php endif; ?>
                <?php if($post_author_on) : ?>
                    <li class="item-author">
                        <i class="fac fac-user"></i><?php the_author_posts_link(); ?>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; }
endif;

if ( ! function_exists( 'consultio_post_meta_event' ) ) :
    function consultio_post_meta_event() {
        $event_date = get_post_meta(get_the_ID(), 'event_date', true);
        ?>
        <ul class="entry-meta">
            <li>
                <?php
                if(!empty($event_date)) {
                    echo esc_attr($event_date);
                } else {
                    echo get_the_date();
                }
                ?>
            </li>
            <li class="item-category"><?php the_terms( get_the_ID(), 'event-category', '', ', ' ); ?></li>
        </ul>
    <?php }
endif;

/**
 * Prints tag list
 */
if ( ! function_exists( 'consultio_entry_tagged_in' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function consultio_entry_tagged_in()
    {
        $tags_list = get_the_tag_list( '<label class="label">'.esc_attr__('Tags:', 'consultio'). '</label>', ' ' );
        if ( $tags_list )
        {
            echo '<div class="entry-tags">';
            printf('%2$s', '', $tags_list);
            echo '</div>';
        }
    }
endif;

/**
 * List socials share for post.
 */
function consultio_socials_share_default() { 
    $post_social_facebook = consultio_get_opt( 'post_social_facebook', true );
    $post_social_twitter = consultio_get_opt( 'post_social_twitter', true );
    $post_social_pinterest = consultio_get_opt( 'post_social_pinterest', true );
    $post_social_linkedin = consultio_get_opt( 'post_social_linkedin', true );
    ?>
    <div class="entry-social">
        <label><?php echo esc_html__('Share:', 'consultio'); ?></label>
        <?php if($post_social_facebook) : ?>
            <a class="fb-social" title="<?php echo esc_attr__('Facebook', 'consultio'); ?>" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="fab fac-facebook-f"></i></a>
        <?php endif; ?>
        <?php if($post_social_twitter) : ?>
            <a class="tw-social" title="<?php echo esc_attr__('Twitter', 'consultio'); ?>" target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>"><i class="fab fac-twitter"></i></a>
        <?php endif; ?>
        <?php if($post_social_pinterest) : ?>
            <a class="pin-social" title="<?php echo esc_attr__('Pinterest', 'consultio'); ?>" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url(the_post_thumbnail_url( 'full' )); ?>&media=&description=<?php the_title(); ?>"><i class="fab fac-pinterest-p"></i></a>
        <?php endif; ?>
        <?php if($post_social_linkedin) : ?>
            <a class="in-social" title="<?php echo esc_attr__('LinkedIn', 'consultio'); ?>" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>"><i class="fab fac-linkedin-in"></i></a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Related Post
 */
function consultio_related_post()
{
    $post_related_on = consultio_get_opt( 'post_related_on', false );

    if($post_related_on) {
        global $post;
        $current_id = $post->ID;
        $posttags = get_the_category($post->ID);
        if (empty($posttags)) return;

        $tags = array();

        foreach ($posttags as $tag) {

            $tags[] = $tag->term_id;
        }
        $post_number = '6';
        $query_similar = new WP_Query(array('posts_per_page' => $post_number, 'post_type' => 'post', 'post_status' => 'publish', 'category__in' => $tags));
        if (count($query_similar->posts) > 1) {
            wp_enqueue_script( 'owl-carousel' );
            wp_enqueue_script( 'consultio-carousel' );
            ?>
            <div class="ct-related-post">
                <h4 class="widget-title"><?php echo esc_html__('Related Posts', 'consultio'); ?></h4>
                <div class="ct-related-post-inner owl-carousel" data-item-xs="1" data-item-sm="2" data-item-md="3" data-item-lg="3" data-item-xl="3" data-item-xxl="3" data-margin="30" data-loop="false" data-autoplay="false" data-autoplaytimeout="5000" data-smartspeed="250" data-center="false" data-arrows="false" data-bullets="false" data-stagepadding="0" data-stagepaddingsm="0" data-rtl="false">
                    <?php foreach ($query_similar->posts as $post):
                        $thumbnail_url = '';
                        if (has_post_thumbnail(get_the_ID()) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) :
                            $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'consultio-blog-small', false);
                        endif;
                        if ($post->ID !== $current_id) : ?>
                            <div class="grid-item">
                                <div class="grid-item-inner">
                                    <?php if (has_post_thumbnail()) { ?>
                                        <div class="item-featured">
                                            <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumbnail_url[0]); ?>" /></a>
                                        </div>
                                    <?php } ?>
                                    <h3 class="item-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                </div>
                            </div>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
        <?php }
    }

    wp_reset_postdata();
}

/**
 * Header Search Mobile
 */
function consultio_header_mobile_search()
{
    $search_field_placeholder = consultio_get_opt( 'search_field_placeholder' );
    $search_icon = consultio_get_opt( 'search_icon', false );
    if($search_icon) : ?>
    <div class="header-mobile-search">
        <form role="search" method="get" action="<?php echo esc_url(home_url( '/' )); ?>">
            <input type="text" placeholder="<?php if(!empty($search_field_placeholder)) { echo esc_attr( $search_field_placeholder ); } else { esc_attr_e('Search...', 'consultio'); } ?>" name="s" class="search-field" />
            <button type="submit" class="search-submit"><i class="fac fac-search"></i></button>
        </form>
    </div>
<?php endif; }

/**
 * Header Search Popup
 */
function consultio_search_popup()
{
    $search_icon = consultio_get_opt( 'search_icon', false );
    $h_search_field_placeholder = consultio_get_opt( 'h_search_field_placeholder' ); 
    if($search_icon == true) { ?>
        <div class="ct-modal ct-modal-search">
            <div class="ct-modal-close"><i class="ct-icon-close ct-center"></i></div>
            <div class="ct-modal-overlay"></div>
            <div class="ct-modal-content">
                <form role="search" method="get" class="search-form-popup" action="<?php echo esc_url(home_url( '/' )); ?>">
                    <div class="searchform-wrap">
                        <input type="text" placeholder="<?php if(!empty($h_search_field_placeholder)) { echo esc_attr( $h_search_field_placeholder ); } else { esc_attr_e('Enter Keywords...', 'consultio'); } ?>" id="search" name="s" class="search-field" />
                        <button type="submit" class="search-submit"><i class="zmdi zmdi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    <?php }
}

if(!function_exists('consultio_hook_anchor_search')){
    function consultio_hook_anchor_search(){ 
        $search_icon = consultio_get_opt( 'search_icon', false ); 
        if($search_icon == false) { ?>
            <div class="ct-modal ct-modal-search">
                <div class="ct-modal-close"><i class="ct-icon-close ct-center"></i></div>
                <div class="ct-modal-overlay"></div>
                <div class="ct-modal-content">
                    <form role="search" method="get" class="search-form-popup" action="<?php echo esc_url(home_url( '/' )); ?>">
                        <div class="searchform-wrap">
                            <input type="text" placeholder="<?php if(!empty($h_search_field_placeholder)) { echo esc_attr( $h_search_field_placeholder ); } else { esc_attr_e('Enter Keywords...', 'consultio'); } ?>" id="search" name="s" class="search-field" />
                            <button type="submit" class="search-submit"><i class="zmdi zmdi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        <?php }
    }
} 

/**
 * Sidebar Hidden
 */
function consultio_sidebar_hidden()
{
    $hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false );
    if($hidden_sidebar_icon && is_active_sidebar('sidebar-hidden')) { ?>
        <div class="ct-hidden-sidebar-wrap">
            <div class="ct-hidden-sidebar-overlay"></div>
            <div class="ct-hidden-sidebar">
                <div class="ct-hidden-close"><i class="zmdi zmdi-close"></i></div>
                <div class="ct-hidden-sidebar-inner">
                    <div class="ct-hidden-sidebar-holder">
                        <?php dynamic_sidebar( 'sidebar-hidden' ); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

if(!function_exists('consultio_hook_anchor_hidden_sidebar')){
    function consultio_hook_anchor_hidden_sidebar(){ 
        $hidden_sidebar_icon = consultio_get_opt( 'hidden_sidebar_icon', false ); 
        if($hidden_sidebar_icon == false && is_active_sidebar('sidebar-hidden')) { ?>
            <div class="ct-hidden-sidebar-wrap">
                <div class="ct-hidden-sidebar-overlay"></div>
                <div class="ct-hidden-sidebar">
                    <div class="ct-hidden-close"><i class="zmdi zmdi-close"></i></div>
                    <div class="ct-hidden-sidebar-inner">
                        <div class="ct-hidden-sidebar-holder">
                            <?php dynamic_sidebar( 'sidebar-hidden' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
} 


/**
 * Cart Sidebar
 */
function consultio_cart_sidebar() { 
    $cart_icon = consultio_get_opt( 'cart_icon', false );
    $cart_icon_sidebar = consultio_get_opt( 'cart_icon_sidebar', false ); ?>
    <?php if(class_exists('Woocommerce') && $cart_icon || class_exists('Woocommerce') && $cart_icon_sidebar) : 
        global $woocommerce; ?>
        <div class="ct-widget-cart-wrap">
            <div class="ct-widget-cart-overlay"></div>
            <div class="ct-widget-cart-sidebar">
                <div class="ct-close"><i class="ct-icon-close ct-center"></i></div>
                <div class="widget_shopping_cart">
                    <div class="widget_shopping_title">
                        <?php echo esc_html__( 'Shopping Cart', 'consultio' ); ?> <span class="widget_cart_counter">(<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'consultio' ), WC()->cart->cart_contents_count ); ?>)</span>
                    </div>
                    <div class="widget_shopping_cart_content">
                        <?php
                            $cart_is_empty = sizeof( $woocommerce->cart->get_cart() ) <= 0;
                        ?>
                        <ul class="cart_list product_list_widget">

                        <?php if ( ! WC()->cart->is_empty() ) : ?>

                            <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                    $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                                        $product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                                        $thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                        $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                        ?>
                                        <li>
                                            <?php if(!empty($thumbnail)) : ?>
                                                <div class="cart-product-image">
                                                    <a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
                                                        <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="cart-product-meta">
                                                <h3><a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"><?php echo esc_html($product_name); ?></a></h3>
                                                <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                                                <?php
                                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                                        '<a href="%s" class="remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><i class="zmdi zmdi-close-circle-o"></i></a>',
                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                        esc_attr__( 'Remove this item', 'consultio' ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $cart_item_key ),
                                                        esc_attr( $_product->get_sku() )
                                                    ), $cart_item_key );
                                                ?>
                                            </div>  
                                        </li>
                                        <?php
                                    }
                                }
                            ?>

                        <?php else : ?>

                            <li class="empty">
                                <i class="fal fac-dolly-flatbed-empty"></i>
                                <span><?php esc_html_e( 'Your cart is empty', 'consultio' ); ?></span>
                                <a class="btn" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo esc_html__('Browse Shop', 'consultio'); ?></a>
                            </li>

                        <?php endif; ?>

                        </ul><!-- end product list -->
                    </div>
                    <?php if ( ! WC()->cart->is_empty() ) : ?>
                        <div class="widget_shopping_cart_footer">
                            <p class="total"><strong><?php esc_html_e( 'Subtotal', 'consultio' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

                            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

                            <p class="buttons">
                                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn wc-forward"><?php esc_html_e( 'View Cart', 'consultio' ); ?></a>
                                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn checkout wc-forward"><?php esc_html_e( 'Checkout', 'consultio' ); ?></a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php }
/**
 * User custom fields.
 */
add_action( 'show_user_profile', 'consultio_user_fields' );
add_action( 'edit_user_profile', 'consultio_user_fields' );
function consultio_user_fields($user){

    $user_facebook = get_user_meta($user->ID, 'user_facebook', true);
    $user_twitter = get_user_meta($user->ID, 'user_twitter', true);
    $user_linkedin = get_user_meta($user->ID, 'user_linkedin', true);
    $user_skype = get_user_meta($user->ID, 'user_skype', true);
    $user_google = get_user_meta($user->ID, 'user_google', true);
    $user_youtube = get_user_meta($user->ID, 'user_youtube', true);
    $user_vimeo = get_user_meta($user->ID, 'user_vimeo', true);
    $user_tumblr = get_user_meta($user->ID, 'user_tumblr', true);
    $user_pinterest = get_user_meta($user->ID, 'user_pinterest', true);
    $user_instagram = get_user_meta($user->ID, 'user_instagram', true);
    $user_yelp = get_user_meta($user->ID, 'user_yelp', true);

    ?>
    <h3><?php esc_html_e('Social', 'consultio'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="user_facebook"><?php esc_html_e('Facebook', 'consultio'); ?></label></th>
            <td>
                <input id="user_facebook" name="user_facebook" type="text" value="<?php echo esc_attr(isset($user_facebook) ? $user_facebook : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_twitter"><?php esc_html_e('Twitter', 'consultio'); ?></label></th>
            <td>
                <input id="user_twitter" name="user_twitter" type="text" value="<?php echo esc_attr(isset($user_twitter) ? $user_twitter : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_linkedin"><?php esc_html_e('Linkedin', 'consultio'); ?></label></th>
            <td>
                <input id="user_linkedin" name="user_linkedin" type="text" value="<?php echo esc_attr(isset($user_linkedin) ? $user_linkedin : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_skype"><?php esc_html_e('Skype', 'consultio'); ?></label></th>
            <td>
                <input id="user_skype" name="user_skype" type="text" value="<?php echo esc_attr(isset($user_skype) ? $user_skype : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_google"><?php esc_html_e('Google', 'consultio'); ?></label></th>
            <td>
                <input id="user_google" name="user_google" type="text" value="<?php echo esc_attr(isset($user_google) ? $user_google : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_youtube"><?php esc_html_e('Youtube', 'consultio'); ?></label></th>
            <td>
                <input id="user_youtube" name="user_youtube" type="text" value="<?php echo esc_attr(isset($user_youtube) ? $user_youtube : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_vimeo"><?php esc_html_e('Vimeo', 'consultio'); ?></label></th>
            <td>
                <input id="user_vimeo" name="user_vimeo" type="text" value="<?php echo esc_attr(isset($user_vimeo) ? $user_vimeo : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_tumblr"><?php esc_html_e('Tumblr', 'consultio'); ?></label></th>
            <td>
                <input id="user_tumblr" name="user_tumblr" type="text" value="<?php echo esc_attr(isset($user_tumblr) ? $user_tumblr : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_pinterest"><?php esc_html_e('Pinterest', 'consultio'); ?></label></th>
            <td>
                <input id="user_pinterest" name="user_pinterest" type="text" value="<?php echo esc_attr(isset($user_pinterest) ? $user_pinterest : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_instagram"><?php esc_html_e('Instagram', 'consultio'); ?></label></th>
            <td>
                <input id="user_instagram" name="user_instagram" type="text" value="<?php echo esc_attr(isset($user_instagram) ? $user_instagram : ''); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="user_yelp"><?php esc_html_e('Yelp', 'consultio'); ?></label></th>
            <td>
                <input id="user_yelp" name="user_yelp" type="text" value="<?php echo esc_attr(isset($user_yelp) ? $user_yelp : ''); ?>" />
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save user custom fields.
 */
add_action( 'personal_options_update', 'consultio_save_user_custom_fields' );
add_action( 'edit_user_profile_update', 'consultio_save_user_custom_fields' );
function consultio_save_user_custom_fields( $user_id )
{
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    if(isset($_POST['user_facebook']))
        update_user_meta( $user_id, 'user_facebook', $_POST['user_facebook'] );
    if(isset($_POST['user_twitter']))
        update_user_meta( $user_id, 'user_twitter', $_POST['user_twitter'] );
    if(isset($_POST['user_linkedin']))
        update_user_meta( $user_id, 'user_linkedin', $_POST['user_linkedin'] );
    if(isset($_POST['user_skype']))
        update_user_meta( $user_id, 'user_skype', $_POST['user_skype'] );
    if(isset($_POST['user_google']))
        update_user_meta( $user_id, 'user_google', $_POST['user_google'] );
    if(isset($_POST['user_youtube']))
        update_user_meta( $user_id, 'user_youtube', $_POST['user_youtube'] );
    if(isset($_POST['user_vimeo']))
        update_user_meta( $user_id, 'user_vimeo', $_POST['user_vimeo'] );
    if(isset($_POST['user_tumblr']))
        update_user_meta( $user_id, 'user_tumblr', $_POST['user_tumblr'] );
    if(isset($_POST['user_pinterest']))
        update_user_meta( $user_id, 'user_pinterest', $_POST['user_pinterest'] );
    if(isset($_POST['user_instagram']))
        update_user_meta( $user_id, 'user_instagram', $_POST['user_instagram'] );
    if(isset($_POST['user_yelp']))
        update_user_meta( $user_id, 'user_yelp', $_POST['user_yelp'] );
}
/* Author Social */
function consultio_get_user_social() {

    $user_facebook = get_user_meta(get_the_author_meta( 'ID' ), 'user_facebook', true);
    $user_twitter = get_user_meta(get_the_author_meta( 'ID' ), 'user_twitter', true);
    $user_linkedin = get_user_meta(get_the_author_meta( 'ID' ), 'user_linkedin', true);
    $user_skype = get_user_meta(get_the_author_meta( 'ID' ), 'user_skype', true);
    $user_google = get_user_meta(get_the_author_meta( 'ID' ), 'user_google', true);
    $user_youtube = get_user_meta(get_the_author_meta( 'ID' ), 'user_youtube', true);
    $user_vimeo = get_user_meta(get_the_author_meta( 'ID' ), 'user_vimeo', true);
    $user_tumblr = get_user_meta(get_the_author_meta( 'ID' ), 'user_tumblr', true);
    $user_pinterest = get_user_meta(get_the_author_meta( 'ID' ), 'user_pinterest', true);
    $user_instagram = get_user_meta(get_the_author_meta( 'ID' ), 'user_instagram', true);
    $user_yelp = get_user_meta(get_the_author_meta( 'ID' ), 'user_yelp', true);

    ?>
    <ul class="user-social">
        <?php if(!empty($user_facebook)) { ?>
            <li><a href="<?php echo esc_url($user_facebook); ?>"><i class="fab fa-facebook-f"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_twitter)) { ?>
            <li><a href="<?php echo esc_url($user_twitter); ?>"><i class="fab fa-twitter"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_linkedin)) { ?>
            <li><a href="<?php echo esc_url($user_linkedin); ?>"><i class="fab fa-linkedin-in"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_instagram)) { ?>
            <li><a href="<?php echo esc_url($user_instagram); ?>"><i class="fa fa-instagram"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_google)) { ?>
            <li><a href="<?php echo esc_url($user_google); ?>"><i class="fa fa-google-plus"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_skype)) { ?>
            <li><a href="<?php echo esc_url($user_skype); ?>"><i class="fa fa-skype"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_pinterest)) { ?>
            <li><a href="<?php echo esc_url($user_pinterest); ?>"><i class="fa fa-pinterest"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_vimeo)) { ?>
            <li><a href="<?php echo esc_url($user_vimeo); ?>"><i class="fa fa-vimeo"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_youtube)) { ?>
            <li><a href="<?php echo esc_url($user_youtube); ?>"><i class="fa fa-youtube"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_yelp)) { ?>
            <li><a href="<?php echo esc_url($user_yelp); ?>"><i class="fa fa-yelp"></i></a></li>
        <?php } ?>
        <?php if(!empty($user_tumblr)) { ?>
            <li><a href="<?php echo esc_url($user_tumblr); ?>"><i class="fa fa-tumblr"></i></a></li>
        <?php } ?>

    </ul> <?php
}

function consultio_social_share_product() { ?>
    <a class="fb-social hover-effect" title="Facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="zmdi zmdi-facebook"></i></a>
    <a class="tw-social hover-effect" title="Twitter" target="_blank" href="https://twitter.com/home?status=<?php the_permalink(); ?>"><i class="zmdi zmdi-twitter"></i></a>
    <a class="pin-social hover-effect" title="Pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(the_post_thumbnail_url( 'full' )); ?>&media=&description=<?php the_title(); ?>"><i class="zmdi zmdi-pinterest"></i></a>
    <?php
}

function consultio_product_nav() {
    global $post;
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
        return;
    ?>
    <?php
    $next_post = get_next_post();
    $previous_post = get_previous_post();
    if( !empty($next_post) || !empty($previous_post) ) { ?>
        <div class="product-previous-next">
            <?php if ( is_a( $previous_post , 'WP_Post' ) && get_the_title( $previous_post->ID ) != '') { ?>
                <a class="nav-link-prev" href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>"><i class="fa fa-long-arrow-left"></i></a>
            <?php } ?>
            <?php if ( is_a( $next_post , 'WP_Post' ) && get_the_title( $next_post->ID ) != '') { ?>
                <a class="nav-link-next" href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><i class="fa fa-long-arrow-right"></i></a>
            <?php } ?>
        </div>
    <?php }
}

/**
 * Social Icon
 */
function consultio_social_header() {
    $social_facebook_url = consultio_get_opt( 'h_social_facebook_url' );
    $social_twitter_url = consultio_get_opt( 'h_social_twitter_url' );
    $social_inkedin_url = consultio_get_opt( 'h_social_inkedin_url' );
    $social_instagram_url = consultio_get_opt( 'h_social_instagram_url' );
    $social_google_url = consultio_get_opt( 'h_social_google_url' );
    $social_skype_url = consultio_get_opt( 'h_social_skype_url' );
    $social_pinterest_url = consultio_get_opt( 'h_social_pinterest_url' );
    $social_vimeo_url = consultio_get_opt( 'h_social_vimeo_url' );
    $social_youtube_url = consultio_get_opt( 'h_social_youtube_url' );
    $social_yelp_url = consultio_get_opt( 'h_social_yelp_url' );
    $social_tumblr_url = consultio_get_opt( 'h_social_tumblr_url' );
    $social_tripadvisor_url = consultio_get_opt( 'h_social_tripadvisor_url' );

    if(!empty($social_tripadvisor_url)) :
        echo '<a href="'.esc_url($social_tripadvisor_url).'" target="_blank"><i class="fab fac-tripadvisor"></i></a>';
    endif;
    if(!empty($social_facebook_url)) :
        echo '<a href="'.esc_url($social_facebook_url).'" target="_blank"><i class="fab fac-facebook-f"></i></a>';
    endif;
    if(!empty($social_twitter_url)) :
        echo '<a href="'.esc_url($social_twitter_url).'" target="_blank"><i class="fab fac-twitter"></i></a>';
    endif;
    if(!empty($social_inkedin_url)) :
        echo '<a href="'.esc_url($social_inkedin_url).'" target="_blank"><i class="fab fac-linkedin-in"></i></a>';
    endif;
    if(!empty($social_instagram_url)) :
        echo '<a href="'.esc_url($social_instagram_url).'" target="_blank"><i class="fab fac-instagram"></i></a>';
    endif;
    if(!empty($social_google_url)) :
        echo '<a href="'.esc_url($social_google_url).'" target="_blank"><i class="fab fac-google-plus"></i></a>';
    endif;
    if(!empty($social_skype_url)) :
        echo '<a href="'.esc_url($social_skype_url).'" target="_blank"><i class="fab fac-skype"></i></a>';
    endif;
    if(!empty($social_pinterest_url)) :
        echo '<a href="'.esc_url($social_pinterest_url).'" target="_blank"><i class="fab fac-pinterest"></i></a>';
    endif;
    if(!empty($social_vimeo_url)) :
        echo '<a href="'.esc_url($social_vimeo_url).'" target="_blank"><i class="fab fac-vimeo"></i></a>';
    endif;
    if(!empty($social_youtube_url)) :
        echo '<a href="'.esc_url($social_youtube_url).'" target="_blank"><i class="fab fac-youtube"></i></a>';
    endif;
    if(!empty($social_yelp_url)) :
        echo '<a href="'.esc_url($social_yelp_url).'" target="_blank"><i class="fab fac-yelp"></i></a>';
    endif;
    if(!empty($social_tumblr_url)) :
        echo '<a href="'.esc_url($social_tumblr_url).'" target="_blank"><i class="fab fac-tumblr"></i></a>';
    endif;
}

function consultio_social_header_popup() {
    $popup_social_facebook_url = consultio_get_opt( 'h_popup_facebook_url' );
    $popup_social_twitter_url = consultio_get_opt( 'h_popup_twitter_url' );
    $popup_social_inkedin_url = consultio_get_opt( 'h_popup_inkedin_url' );
    $popup_social_instagram_url = consultio_get_opt( 'h_popup_instagram_url' );
    $popup_social_google_url = consultio_get_opt( 'h_popup_google_url' );
    $popup_social_skype_url = consultio_get_opt( 'h_popup_skype_url' );
    $popup_social_pinterest_url = consultio_get_opt( 'h_popup_pinterest_url' );
    $popup_social_vimeo_url = consultio_get_opt( 'h_popup_vimeo_url' );
    $popup_social_youtube_url = consultio_get_opt( 'h_popup_youtube_url' );
    $popup_social_yelp_url = consultio_get_opt( 'h_popup_yelp_url' );
    $popup_social_tumblr_url = consultio_get_opt( 'h_popup_tumblr_url' );
    $popup_social_tripadvisor_url = consultio_get_opt( 'h_popup_tripadvisor_url' );

    if(!empty($popup_social_tripadvisor_url)) :
        echo '<a href="'.esc_url($popup_social_tripadvisor_url).'" target="_blank"><i class="fab fac-tripadvisor"></i></a>';
    endif;
    if(!empty($popup_social_facebook_url)) :
        echo '<a href="'.esc_url($popup_social_facebook_url).'" target="_blank"><i class="fab fac-facebook-f"></i></a>';
    endif;
    if(!empty($popup_social_twitter_url)) :
        echo '<a href="'.esc_url($popup_social_twitter_url).'" target="_blank"><i class="fab fac-twitter"></i></a>';
    endif;
    if(!empty($popup_social_inkedin_url)) :
        echo '<a href="'.esc_url($popup_social_inkedin_url).'" target="_blank"><i class="fab fac-linkedin-in"></i></a>';
    endif;
    if(!empty($popup_social_instagram_url)) :
        echo '<a href="'.esc_url($popup_social_instagram_url).'" target="_blank"><i class="fab fac-instagram"></i></a>';
    endif;
    if(!empty($popup_social_google_url)) :
        echo '<a href="'.esc_url($popup_social_google_url).'" target="_blank"><i class="fab fac-google-plus"></i></a>';
    endif;
    if(!empty($popup_social_skype_url)) :
        echo '<a href="'.esc_url($popup_social_skype_url).'" target="_blank"><i class="fab fac-skype"></i></a>';
    endif;
    if(!empty($popup_social_pinterest_url)) :
        echo '<a href="'.esc_url($popup_social_pinterest_url).'" target="_blank"><i class="fab fac-pinterest"></i></a>';
    endif;
    if(!empty($popup_social_vimeo_url)) :
        echo '<a href="'.esc_url($popup_social_vimeo_url).'" target="_blank"><i class="fab fac-vimeo"></i></a>';
    endif;
    if(!empty($popup_social_youtube_url)) :
        echo '<a href="'.esc_url($popup_social_youtube_url).'" target="_blank"><i class="fab fac-youtube"></i></a>';
    endif;
    if(!empty($popup_social_yelp_url)) :
        echo '<a href="'.esc_url($popup_social_yelp_url).'" target="_blank"><i class="fab fac-yelp"></i></a>';
    endif;
    if(!empty($popup_social_tumblr_url)) :
        echo '<a href="'.esc_url($popup_social_tumblr_url).'" target="_blank"><i class="fab fac-tumblr"></i></a>';
    endif;
}

if(!function_exists('ct_get_term_of_post_to_id')){
    function ct_get_term_of_post_to_id($post_id, $tax = array())
    {
        $term_list = array();
        foreach ($tax as $taxo) {
            $term_of_post = wp_get_post_terms($post_id, $taxo);
            foreach ($term_of_post as $term) {
                $term_list[] = 'ct-term-'.$term->term_id;
            }
        }

        return implode(' ', $term_list);
    }
}

if(!function_exists('ct_get_term_of_post_to_class_id')){
    function ct_get_term_of_post_to_class_id($post_id, $tax = array())
    {
        $term_list = array();
        foreach ($tax as $taxo) {
            $term_of_post = wp_get_post_terms($post_id, $taxo);
            foreach ($term_of_post as $term) {
                $term_list[] = 'filter-category-'.$term->term_id;
            }
        }

        return implode(' ', $term_list);
    }
}

if(!function_exists('consultio_get_post_grid_layout1')){
    function consultio_get_post_grid_layout1($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else {
            $img_size = '600x414';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class_id($post->ID, array_unique($tax));
                $author = get_user_by('id', $post->post_author);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                            $img = ct_get_image_by_size( array(
                                'attach_id'  => $img_id,
                                'thumb_size' => $img_size,
                                'class' => 'disable-lazy no-lazyload',
                            ));
                            $thumbnail = $img['thumbnail'];
                            ?>
                            <div class="entry-featured">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="entry-body">
                            <div class="entry-holder">
                                <?php if($show_date == 'true' || $show_author == 'true' ) : ?>
                                    <ul class="entry-meta">
                                        <?php if($show_date == 'true'): ?>
                                            <li class="item-date"><i class="fac fac-calendar-alt"></i><?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?></li>
                                        <?php endif; ?>
                                        <?php if($show_author == 'true'): ?>
                                            <li class="item-author">
                                                <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><i class="fac fac-user"></i><?php echo esc_html($author->display_name); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                                <h3 class="entry-title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <?php if($show_button == 'true') : ?>
                                    <div class="entry-readmore">
                                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                            <i class="fac fac-angle-double-right space-left"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_post_grid_layout2')){
    function consultio_get_post_grid_layout2($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                if($key == 1) {
                    $img_size = '600x482';
                    $item_class = "grid-item col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12";
                } else {
                    $img_size = '600x414';
                    $item_class = "grid-item col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12";
                }
                $img_id = get_post_thumbnail_id($post->ID);
                $author = get_user_by('id', $post->post_author);
                $video_url = get_post_meta($post->ID, 'video_url', true);
                ?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if($key == 1) { echo 'item-lg'; } ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                            $img = ct_get_image_by_size( array(
                                'attach_id'  => $img_id,
                                'thumb_size' => $img_size,
                                'class' => 'disable-lazy no-lazyload',
                            ));
                            $thumbnail = $img['thumbnail'];
                            ?>
                            <div class="entry-featured">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <?php if($key == 1) { ?>
                                    <div class="ct-video-overlay">
                                        <a class="ct-video-button" href="<?php echo esc_url($video_url); ?>">
                                            <i class="fac fac-play"></i>
                                            <span class="line-video-animation line-video-1"></span>
                                            <span class="line-video-animation line-video-2"></span>
                                            <span class="line-video-animation line-video-3"></span>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <div class="entry-body">
                            <div class="entry-holder">
                                <h3 class="entry-title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <ul class="entry-meta">
                                    <?php if($show_author) : ?>
                                        <li class="item-author"><a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><?php echo esc_html($author->display_name); ?></a></li>
                                    <?php endif; ?>
                                    <?php if($show_date) : ?>
                                        <li class="item-date"><?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?></li>
                                    <?php endif; ?>
                                </ul>
                                <?php if($key == 1) { ?>
                                    <div class="item--content">
                                        <?php echo wp_trim_words( $post->post_excerpt, 30, $more = null ); ?>
                                    </div>
                                <?php } else { ?>
                                    <?php if($show_button) : ?>
                                        <div class="entry-readmore">
                                            <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                                <?php if(!empty($button_text)) {
                                                    echo esc_attr($button_text);
                                                } else {
                                                    echo esc_html__('Read more', 'consultio');
                                                } ?>
                                                <i class="fac fac-angle-double-right space-left"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_post_grid_layout3')){
    function consultio_get_post_grid_layout3($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $img_size = '390x270';
                $img_id = get_post_thumbnail_id($post->ID);
                ?>
                <div class="grid-item <?php if($key == 0) { echo 'item-lg'; } ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false) && $key == 0): 
                            $img = ct_get_image_by_size( array(
                                'attach_id'  => $img_id,
                                'thumb_size' => $img_size,
                                'class' => 'disable-lazy no-lazyload',
                            ));
                            $thumbnail = $img['thumbnail'];
                            ?>
                            <div class="entry-featured">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <span class="entry-date"><?php $date_formart = 'd M Y'; echo get_the_date($date_formart, $post->ID); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="entry-body">
                            <div class="entry-category">
                                <?php the_terms( $post->ID, 'category', '', ' ' ); ?>
                            </div>
                            <h3 class="entry-title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php if($key == 0) { ?>
                                <div class="item--content">
                                    <?php echo wp_trim_words( $post->post_excerpt, 30, $more = null ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_post_grid_layout4')){
    function consultio_get_post_grid_layout4($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else {
            $img_size = '680x410';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class_id($post->ID, array_unique($tax));
                $author = get_user_by('id', $post->post_author);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                            $img = ct_get_image_by_size( array(
                                'attach_id'  => $img_id,
                                'thumb_size' => $img_size,
                                'class' => 'disable-lazy no-lazyload',
                            ));
                            $thumbnail = $img['thumbnail']; ?>
                            <div class="item--featured">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="item--body">
                            <div class="item--holder">
                                <?php if($show_date == 'true' || $show_category == 'true' ) : ?>
                                    <ul class="item--meta">
                                        <?php if($show_date == 'true'): ?>
                                            <li class="item--date"><i class="far fac-calendar-alt"></i><?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?></li>
                                        <?php endif; ?>
                                        <?php if($show_category == 'true'): ?>
                                            <li class="item--category">
                                                <i class="far fac-file-alt"></i><?php the_terms( $post->ID, 'category', '', ', ' ); ?>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                                <h3 class="item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <?php if($show_excerpt == 'true'): ?>
                                    <div class="item--content">
                                        <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($show_button == 'true') : ?>
                                    <div class="item--readmore">
                                        <a class="btn-text2" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                            <i class="flaticonv2 flaticonv2-right-arrow"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_post_grid_layout5')){
    function consultio_get_post_grid_layout5($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else {
            $img_size = 'full';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class_id($post->ID, array_unique($tax));
                $author = get_user_by('id', $post->post_author);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                            $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false);
                            ?>
                            <div class="item--featured">
                                <a class="bg-image" href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>" style="background-image: url(<?php echo esc_url($thumbnail_url[0]); ?>);"></a>
                                <?php if($show_date == 'true'): ?>
                                    <div class="item--date">
                                        <div class="item--date-inner">
                                            <span><?php echo get_the_date('d', $post->ID); ?></span>
                                            <span><?php echo get_the_date('M', $post->ID); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="item--body">
                            <?php if($show_category == 'true') : ?>
                                <div class="item--category">
                                    <?php the_terms( $post->ID, 'category', '', ', ' ); ?>
                                </div>
                            <?php endif; ?>
                            <h3 class="item--title"><a href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo ct_print_html(get_the_title($post->ID)); ?></a></h3>
                            <?php if($show_excerpt == 'true'): ?>
                                <div class="item--content">
                                    <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="item--readmore">
                                    <a class="btn btn-effect" href="<?php if(!empty($external_url)) { echo esc_url($external_url); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                        <span>
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                        </span>
                                        <i class="flaticonv2-right-arrow"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout1')){
    function consultio_get_service_layout1($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '541x600';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_target_link = get_post_meta($post->ID, 'service_target_link', true);
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_popup = '';
                $uqid = uniqid();
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                    $img_id = get_post_thumbnail_id($post->ID);
                    $img = ct_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $img_size,
                        'class' => 'disable-lazy no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                    ?>
                    <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                        <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">
                            <div class="grid-item-over">
                                <div class="item--featured">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                </div>
                                <div class="item--holder">
                                    <div class="item--holder-inner">
                                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                                            <div class="item--icon"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                                        <?php endif; ?>

                                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                                            <div class="item--icon">
                                                <?php echo wp_kses_post($icon_thumbnail); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($show_title == 'true'): ?>
                                            <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                        <?php endif; ?>
                                        <div class="item--gap"></div>
                                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                            <div class="item--content">
                                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($service_icon)) : ?>
                                            <div class="item--icon-abs"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item--meta">
                                    <?php if($show_title == 'true'): ?>
                                        <h3 class="item--title">
                                            <?php if(!empty($service_popup)) { ?>
                                                <a class="z-view" href="#service-popup-<?php echo esc_attr($uqid); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a>
                                            <?php } else { ?>
                                                <a target="<?php echo esc_attr($service_target_link); ?>" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a>
                                            <?php } ?>
                                        </h3>
                                    <?php endif; ?>
                                    <?php if($show_button == 'true'): ?>
                                        <div class="item--readmore">
                                            <?php if(!empty($service_popup)) { ?>
                                                <a class="more-plus z-view" href="#service-popup-<?php echo esc_attr($uqid); ?>">+</a>
                                            <?php } else { ?>
                                                <a class="more-plus" target="<?php echo esc_attr($service_target_link); ?>" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">+</a>
                                            <?php } ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($service_popup)) : ?>
                                        <div id="service-popup-<?php echo esc_attr($uqid); ?>" class="item--popup">
                                            <?php echo esc_attr($service_popup); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php endif;
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout2')){
    function consultio_get_service_layout2($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '450x450';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                    $img_id = get_post_thumbnail_id($post->ID);
                    $img = ct_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $img_size,
                        'class' => 'disable-lazy no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                    ?>
                    <?php if($key < '4') { ?>
                        <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                            <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">
                                <div class="item--featured">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                </div>
                                <div class="item--meta">
                                    <?php if($show_title == 'true'): ?>
                                        <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div class="item--holder">
                                    <div class="item--holder-inner">
                                        <?php if($show_title == 'true'): ?>
                                            <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                        <?php endif; ?>
                                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                            <div class="item--content">
                                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($show_button == 'true') : ?>
                                            <div class="entry-readmore">
                                                <a class="btn btn-default" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                                    <?php if(!empty($button_text)) {
                                                        echo esc_attr($button_text);
                                                    } else {
                                                        echo esc_html__('Read more', 'consultio');
                                                    } ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($key == '4' && !empty($el_title)) { ?>
                        <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                            <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">
                                <div class="item--image">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                </div>
                                <div class="item--body">
                                    <h3><?php echo esc_attr($el_title); ?></h3>
                                    <a href="<?php echo esc_url($el_link['url']); ?>">+</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($key > '3') { ?>
                        <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                            <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">
                                <div class="item--featured">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                </div>
                                <div class="item--meta">
                                    <?php if($show_title == 'true'): ?>
                                        <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                    <?php endif; ?>
                                </div>
                                <div class="item--holder">
                                    <div class="item--holder-inner">
                                        <?php if($show_title == 'true'): ?>
                                            <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                        <?php endif; ?>
                                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                            <div class="item--content">
                                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($show_button == 'true') : ?>
                                            <div class="entry-readmore">
                                                <a class="btn btn-default" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                                    <?php if(!empty($button_text)) {
                                                        echo esc_attr($button_text);
                                                    } else {
                                                        echo esc_html__('Read more', 'consultio');
                                                    } ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endif; ?>      
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout3')){
    function consultio_get_service_layout3($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <div class="grid-item-holder">
                            <div class="item--overlay"></div>
                            
                            <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                                <div class="item--icon"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                            <?php endif; ?>

                            <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                                <div class="item--icon">
                                    <?php echo wp_kses_post($icon_thumbnail); ?>
                                </div>
                            <?php endif; ?>

                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--content">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($service_icon)) : ?>
                                <div class="item--icon-abs"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                            <?php endif; ?>
                            <a class="item--readmore" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout4')){
    function consultio_get_service_layout4($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">
                        <?php if($show_title == 'true'): ?>
                            <h3 class="item--title">
                                <i class="zmdi zmdi-long-arrow-right icon-left"></i>
                                <?php echo esc_attr(get_the_title($post->ID)); ?>
                                <i class="zmdi zmdi-long-arrow-right icon-right"></i>
                            </h3>
                        <?php endif; ?>                
                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                            <div class="item--content">
                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <a class="item--readmore" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout5')){
    function consultio_get_service_layout5($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                            <div class="item--icon"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                        <?php endif; ?>

                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                            <div class="item--icon">
                                <?php echo wp_kses_post($icon_thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        <div class="item--content">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--desc">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a class="item--readmore" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout6')){
    function consultio_get_service_layout6($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '300x215';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" height="60px" class="ct-edge-hide" style="fill:#ffffff"><path stroke-width="0" d="M0 0 C50 100 50 100 100 0 L100 100 0 100"></path></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" height="45px" class="ct-edge-hover" style="fill:#ffffff"> <path stroke-width="0" d="M0 100 C50 0 50 0 100 100 Z"></path> </svg>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="item-readmore">
                                    <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                        <?php if(!empty($button_text)) {
                                            echo esc_attr($button_text);
                                        } else {
                                            echo esc_html__('Learn more', 'consultio');
                                        } ?>
                                        <i class="zmdi zmdi-long-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div> 
                    </div>
                </div>
            <?php endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout7')){
    function consultio_get_service_layout7($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '400x294';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--desc">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="item-readmore">
                                    <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">+</a>
                                </div>
                            <?php endif; ?>
                        </div> 
                    </div>
                </div>
            <?php endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout8')){
    function consultio_get_service_layout8($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                
                if(isset($icon_item[$key])) {
                    $icon_item_image = $icon_item[$key];
                    $service_icon_img['id'] = $icon_item_image['icon_image']['id'];
                }

                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>" data-wow-duration="1.2s">
                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                            <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                        <?php endif; ?>
                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                            <div class="item--icon">
                                <?php echo wp_kses_post($icon_thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($show_title == 'true'): ?>
                            <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                        <?php endif; ?>
                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                            <div class="item--content">
                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>" class="item--readmore"></a>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout9')){
    function consultio_get_service_layout9($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x348';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--desc">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="entry-readmore">
                                    <a class="btn-arrow" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                        <span>
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                        </span>
                                        <i class="fac fac-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div> 
                    </div>
                </div>
            <?php endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout10')){
    function consultio_get_service_layout10($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_style_class = 'flaticonv4-next';
                $btn_style_class = 'btn-line';
                if($style == 'style2') {
                    $icon_style_class = 'zmdi zmdi-long-arrow-right';
                    $btn_style_class = 'btn-arrow-right';
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                            <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                        <?php endif; ?>

                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                            <div class="item--icon">
                                <?php echo wp_kses_post($icon_thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--description">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="item--readmore">
                                    <a class="<?php echo esc_attr($btn_style_class); ?>" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                        <span>
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                        </span>
                                        <i class="<?php echo esc_attr($icon_style_class); ?>"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout11')){
    function consultio_get_service_layout11($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x438';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                    <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                        <div class="grid-item-inner <?php echo esc_attr($ct_animate); if($key == '0') { echo ' first-item'; } ?>">
                            <?php if(!empty($item_image)) : ?>
                                <div class="item--image-bg" style="background-image: url(<?php echo esc_url($item_image['url']); ?>);"></div>
                            <?php endif; ?>
                            <div class="item--featured">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                            <div class="item--holder">
                                <div class="item--holder-inner">
                                    <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                                        <div class="item--icon"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                                    <?php endif; ?>

                                    <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                                        <div class="item--icon">
                                            <?php echo wp_kses_post($icon_thumbnail); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($show_title == 'true'): ?>
                                        <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                    <?php endif; ?>
                                    <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                        <div class="item--desc">
                                            <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div> 
                            <div class="item--readmore">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><span>+</span></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout12')){
    function consultio_get_service_layout12($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div id="service_layout12_1991_0<?php echo esc_attr($key); ?>" class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <?php if(isset($item_color[$key])) : $ct_item_color = $item_color[$key]; ?>
                        <div class="ct-inline-css"  data-css="
                            .ct-service-grid12 #service_layout12_1991_0<?php echo esc_attr($key); ?> .item--icon {
                            color: <?php echo esc_attr($ct_item_color['icon_color']); ?>;
                            background-color: <?php echo esc_attr($ct_item_color['icon_bg_color']); ?>;
                        }
                        .ct-service-grid12 #service_layout12_1991_0<?php echo esc_attr($key); ?> .item--readmore .btn {
                            color: <?php echo esc_attr($ct_item_color['btn_color']); ?>;
                            background-color: <?php echo esc_attr($ct_item_color['btn_bg_color']); ?>;
                        }
                        .ct-service-grid12 #service_layout12_1991_0<?php echo esc_attr($key); ?> .item--readmore .btn:hover {
                            color: <?php echo esc_attr($ct_item_color['btn_color_hover']); ?>;
                            background-color: <?php echo esc_attr($ct_item_color['btn_bg_color_hover']); ?>;
                        }">
                        </div>
                    <?php endif; ?>

                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?> <?php if($show_button == 'true') { echo 'btn-added'; } ?>">
                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                            <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                        <?php endif; ?>

                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                            <div class="item--icon">
                                <?php echo wp_kses_post($icon_thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                <div class="item--description">
                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_button == 'true') : ?>
                                <div class="item--readmore">
                                    <a class="btn btn-mini" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                        <span>
                                            <?php if(!empty($button_text)) {
                                                echo esc_attr($button_text);
                                            } else {
                                                echo esc_html__('Read more', 'consultio');
                                            } ?>
                                        </span>
                                        <i class="flaticonv5-refresh-2"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout14')){
    function consultio_get_service_layout14($posts = [], $settings = []){
        $primary_color = consultio_get_opt( 'primary_color' );
        $gradient_color = consultio_get_opt( 'gradient_color' );
        if ( !empty($gradient_color['from']) && isset($gradient_color['from']) ){
            $gradient_color_from = $gradient_color['from'];
        } else {
            $gradient_color_from = $primary_color;
        }
        if ( !empty($gradient_color['to']) && isset($gradient_color['to']) ){
            $gradient_color_to = $gradient_color['to'];
        } else {
            $gradient_color_to = $primary_color;
        }
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                
                if(isset($icon_item[$key])) {
                    $icon_item_image = $icon_item[$key];
                    $service_icon_img['id'] = $icon_item_image['icon_image']['id'];
                }

                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>" data-wow-duration="1.2s">
                        <?php if($show_title == 'true'): ?>
                        <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                    <?php endif; ?>

                    <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                        <div class="item--icon">
                            <i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i>
                            <i class="icon-hover <?php echo esc_attr($service_icon); ?>"></i>
                        </div>
                    <?php endif; ?>

                    <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                        <div class="item--icon">
                            <?php echo wp_kses_post($icon_thumbnail); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                        <div class="item--content">
                            <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                        </div>
                    <?php endif; ?>
                    <svg class="service-shape" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="137" height="107" viewBox="0 0 137 107">
                        <defs>
                            <linearGradient id="service-gradient1" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:<?php echo esc_attr($gradient_color_from); ?>;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:<?php echo esc_attr($gradient_color_to); ?>;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <g fill="url(#service-gradient1)">
                            <path d="M1,80.938C-1.875,70.8,8.625,56.713,20,50c14.215-8.389,25.449-1.213,38-10,12.582-8.809,9.466-21.678,23-32C86.745,3.619,97.824-2.056,108.783.913c24.1,6.53,36.756,54.393,21.217,80.025C105.2,121.843,8.271,106.591,1,80.938Z"/>
                        </g>
                    </svg>
                    <a class="item--link" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout15')){
    function consultio_get_service_layout15($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x315';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                
                if(isset($icon_item[$key])) {
                    $icon_item_image = $icon_item[$key];
                    $service_icon_img['id'] = $icon_item_image['icon_image']['id'];
                }

                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                        <div class="item--featured">
                            <div class="item--featured-image">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                            <?php if(isset($icon_item[$key])) { 
                                $icon_item_image = $icon_item[$key];
                                $icon_img_custom = consultio_get_image_by_size( array(
                                    'attach_id'  => $icon_item_image['icon_image']['id'],
                                    'thumb_size' => 'full',
                                ));
                                $icon_thumbnail_custom = $icon_img_custom['thumbnail'];
                                ?>
                                <div class="item--icon">
                                    <?php echo wp_kses_post($icon_thumbnail_custom); ?>
                                </div>
                            <?php } else { ?>
                                <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                                    <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                                <?php endif; ?>

                                <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                                    <div class="item--icon">
                                        <?php echo wp_kses_post($icon_thumbnail); ?>
                                    </div>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                    <div class="item--meta">
                        <?php if($show_title == 'true'): ?>
                            <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                        <?php endif; ?>
                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                            <div class="item--desc">
                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($show_button == 'true') : ?>
                            <div class="entry-readmore">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                    <span>
                                        <?php if(!empty($button_text)) {
                                            echo esc_attr($button_text);
                                        } else {
                                            echo esc_html__('Read more', 'consultio');
                                        } ?>
                                    </span>
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 414.165 414.165" xml:space="preserve">
                                        <g>
                                            <polygon points="313.749,106.677 283.584,136.843 332.501,185.76 0,185.76 0,228.427 332.501,228.427 283.584,277.344 
                                                313.749,307.488 414.165,207.093         "/>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout16')){
    function consultio_get_service_layout16($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x352';
        }
        if (is_array($posts)):
            foreach ($posts as $key => $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                
                if(isset($icon_item[$key])) {
                    $icon_item_image = $icon_item[$key];
                    $service_icon_img['id'] = $icon_item_image['icon_image']['id'];
                }

                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                                <div class="item--holder">
                                    <div class="item--holder-inner">
                                        <?php if(!empty($service_icon)) : ?>
                                            <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                                        <?php endif; ?>
                                        <div class="item--primary">
                                            <?php if($show_title == 'true'): ?>
                                                <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                                            <?php endif; ?>
                                            <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                                                <div class="item--content">
                                                    <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($show_button == 'true') : ?>
                                                <div class="item-readmore">
                                                    <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                                        <span>
                                                            <?php if(!empty($button_text)) {
                                                                echo esc_attr($button_text);
                                                            } else {
                                                                echo esc_html__('Read more', 'consultio');
                                                            } ?>
                                                        </span>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="item-overlay"><span></span></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="item--meta">
                            <?php if(!empty($service_icon)) : ?>
                                <div class="item--icon"><i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i></div>
                            <?php endif; ?>
                            <?php if($show_title == 'true'): ?>
                                <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_service_layout17')){
    function consultio_get_service_layout17($posts = [], $settings = []){
        extract($settings);
        if (is_array($posts)):
            foreach ($posts as $post):
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $icon_type = get_post_meta($post->ID, 'icon_type', true);
                $service_icon = get_post_meta($post->ID, 'service_icon', true);
                $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
                if(!empty($service_icon_img['id'])) {
                    $icon_img = ct_get_image_by_size( array(
                        'attach_id'  => $service_icon_img['id'],
                        'thumb_size' => 'full',
                    ));
                    $icon_thumbnail = $icon_img['thumbnail'];
                }
                $service_except = get_post_meta($post->ID, 'service_except', true);
                $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                            <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                        <?php endif; ?>

                        <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                            <div class="item--icon">
                                <?php echo wp_kses_post($icon_thumbnail); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_title == 'true'): ?>
                            <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                        <?php endif; ?>
                        
                        <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                            <div class="item--content">
                                <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_button == 'true') : ?>
                            <div class="item-readmore">
                                <a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                    <span>
                                        <?php if(!empty($button_text)) {
                                            echo esc_attr($button_text);
                                        } else {
                                            echo esc_html__('Read More', 'consultio');
                                        } ?>
                                    </span>
                                    <i class="flaticonv2 flaticonv2-right-arrow"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout1')){
    function consultio_get_portfolio_layout1($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x589';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_id($post->ID, array_unique($tax));
                $portfolio_custom_link = get_post_meta($post->ID, 'portfolio_custom_link', true);
                $portfolio_video_link = get_post_meta($post->ID, 'portfolio_video_link', true);
                if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                    <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                        <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                            <div class="item--holder">
                                <?php if(!empty($portfolio_video_link)) { ?>
                                    <a class="item--video ct-video-button" href="<?php echo esc_url($portfolio_video_link); ?>"><i class="fac fac-play"></i></a>
                                <?php } else { ?>
                                    <div class="item--meta">
                                        <?php if($show_title == 'true'): ?>
                                            <h3 class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                        <?php endif; ?>
                                        <?php if($show_category == 'true'): ?>
                                            <div class="item--category">
                                                <?php the_terms( $post->ID, 'portfolio-category', '', ' ' ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($show_button == 'true'): ?>
                                        <div class="item--readmore">
                                            <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><i class="ct-icon-plus-lg"></i></a>
                                        </div>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout2')){
    function consultio_get_portfolio_layout2($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x600';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">

                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="item--holder">
                            <div class="item--holder-overlay"></div>
                            <div class="item--meta">
                                <?php if($show_title == 'true'): ?>
                                    <<?php ct_print_html($title_tag);?> class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></<?php ct_print_html($title_tag);?>>
                                <?php endif; ?>
                                <?php if($show_category == 'true'): ?>
                                    <div class="item--category">
                                        <?php the_terms( $post->ID, 'portfolio-category', '', ', ' ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if($show_button == 'true'): ?>
                                <div class="item--readmore">
                                    <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">+</a>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout3')){
    function consultio_get_portfolio_layout3($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x589';
        }
        if (is_array($posts)) :
            if(!empty($size_list)) {
                $sizes = explode(',',$size_list);
                $i = 0;
            }
            foreach ($posts as $post):
                if(!empty($size_list)) {
                    $img_size = end($sizes);
                    if(!empty($sizes[$i])){
                        $img_size = $sizes[$i];
                    }
                }
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $portfolio_custom_link = get_post_meta($post->ID, 'portfolio_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <?php if($show_category == 'true'): ?>
                                    <div class="item--category">
                                        <?php the_terms( $post->ID, 'portfolio-category', '', ' ' ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($show_title == 'true'): ?>
                            <h3 class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                        <?php endif; ?>

                    </div>
                </div>
            <?php
            if(!empty($size_list)) { $i++; } endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout4')){
    function consultio_get_portfolio_layout4($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x332';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                $portfolio_custom_link = get_post_meta($post->ID, 'portfolio_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        <div class="item--holder">
                            <?php if($show_button == 'true'): ?>
                                <div class="item--readmore">
                                    <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><i class="fa fa-link"></i></a>
                                </div>
                            <?php endif; ?>
                            <div class="item--meta">
                                <?php if($show_title == 'true'): ?>
                                    <h3 class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout5')){
    function consultio_get_portfolio_layout5($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '640x440';
        }
        if (is_array($posts)):
            if(!empty($size_list)) {
                $sizes = explode(',',$size_list);
                $i = 0;
            }
            foreach ($posts as $post):
                if(!empty($size_list)) {
                    $img_size = end($sizes);
                    if(!empty($sizes[$i])){
                        $img_size = $sizes[$i];
                    }
                }
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_id($post->ID, array_unique($tax));
                $portfolio_custom_link = get_post_meta($post->ID, 'portfolio_custom_link', true);
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="item--holder">
                            <div class="item--meta">
                                <?php if($show_title == 'true'): ?>
                                    <h3 class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <?php endif; ?>
                                <?php if($show_category == 'true'): ?>
                                    <div class="item--category">
                                        <?php the_terms( $post->ID, 'portfolio-category', '', ' ' ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if($show_button == 'true'): ?>
                                <div class="item--readmore">
                                    <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">+</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
            if(!empty($size_list)) { $i++; } endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_portfolio_layout6')){
    function consultio_get_portfolio_layout6($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '600x589';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_id($post->ID, array_unique($tax));
                $portfolio_custom_link = get_post_meta($post->ID, 'portfolio_custom_link', true);
                $portfolio_video_link = get_post_meta($post->ID, 'portfolio_video_link', true);
                if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                    <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                        <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                            <div class="item--featured">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                            <div class="item--holder">
                                <?php if($show_category == 'true'): ?>
                                    <div class="item--category">
                                        <?php the_terms( $post->ID, 'portfolio-category', '', ' ' ); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($show_title == 'true'): ?>
                                    <h3 class="item--title"><a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                                <?php endif; ?>
                                <?php if($show_button == 'true'): ?>
                                    <div class="item--readmore">
                                        <a href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><i class="flaticonv7 flaticonv7-link-1"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a class="item--link" href="<?php if(!empty($portfolio_custom_link)) { echo esc_url($portfolio_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_case_study_layout1')){
    function consultio_get_case_study_layout1($posts = [], $settings = []){
        extract($settings);
        if($thumbnail_size != 'custom'){
            $img_size = $thumbnail_size;
        }
        elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
            $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
        }
        else{
            $img_size = '520x600';
        }
        if (is_array($posts)):
            foreach ($posts as $post):
                $img_id = get_post_thumbnail_id($post->ID);
                $img = ct_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'disable-lazy no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $item_class = "grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
                $filter_class = ct_get_term_of_post_to_class($post->ID, array_unique($tax));
                ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">

                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                            <div class="item--featured">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="item--holder">
                            <div class="item--meta">
                                <?php if($show_category == 'true'): ?>
                                    <div class="item--category">
                                        <?php the_terms( $post->ID, 'case-study-category', '', ', ' ); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($show_title == 'true'): ?>
                                    <<?php ct_print_html($title_tag);?> class="item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></<?php ct_print_html($title_tag);?>>
                                <?php endif; ?>
                                <?php if($show_button == 'true'): ?>
                                    <div class="item--readmore">
                                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">+</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php
            endforeach;
        endif;
    }
}

if(!function_exists('consultio_get_post_grid')){
    function consultio_get_post_grid($posts = [], $settings = []){
        if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
            return false;
        }
        switch ($settings['template_type']) {
            case 'post_grid_layout1':
                consultio_get_post_grid_layout1($posts, $settings);
                break;

            case 'post_grid_layout2':
                consultio_get_post_grid_layout2($posts, $settings);
                break;

            case 'post_grid_layout3':
                consultio_get_post_grid_layout3($posts, $settings);
                break;

            case 'post_grid_layout4':
                consultio_get_post_grid_layout4($posts, $settings);
                break;

            case 'post_grid_layout5':
                consultio_get_post_grid_layout5($posts, $settings);
                break;

            case 'service_layout1':
                consultio_get_service_layout1($posts, $settings);
                break;

            case 'service_layout2':
                consultio_get_service_layout2($posts, $settings);
                break;

            case 'service_layout3':
                consultio_get_service_layout3($posts, $settings);
                break;

            case 'service_layout4':
                consultio_get_service_layout4($posts, $settings);
                break;

            case 'service_layout5':
                consultio_get_service_layout5($posts, $settings);
                break;

            case 'service_layout6':
                consultio_get_service_layout6($posts, $settings);
                break;

            case 'service_layout7':
                consultio_get_service_layout7($posts, $settings);
                break;

            case 'service_layout8':
                consultio_get_service_layout8($posts, $settings);
                break;

            case 'service_layout9':
                consultio_get_service_layout9($posts, $settings);
                break;

            case 'service_layout10':
                consultio_get_service_layout10($posts, $settings);
                break;

            case 'service_layout11':
                consultio_get_service_layout11($posts, $settings);
                break;

            case 'service_layout12':
                consultio_get_service_layout12($posts, $settings);
                break;

            case 'service_layout14':
                consultio_get_service_layout14($posts, $settings);
                break;

            case 'service_layout15':
                consultio_get_service_layout15($posts, $settings);
                break;

            case 'service_layout16':
                consultio_get_service_layout16($posts, $settings);
                break;

            case 'service_layout17':
                consultio_get_service_layout17($posts, $settings);
                break;

            case 'portfolio_layout1':
                consultio_get_portfolio_layout1($posts, $settings);
                break;

            case 'portfolio_layout2':
                consultio_get_portfolio_layout2($posts, $settings);
                break;

            case 'portfolio_layout3':
                consultio_get_portfolio_layout3($posts, $settings);
                break;

            case 'portfolio_layout4':
                consultio_get_portfolio_layout4($posts, $settings);
                break;

            case 'portfolio_layout5':
                consultio_get_portfolio_layout5($posts, $settings);
                break;

            case 'portfolio_layout6':
                consultio_get_portfolio_layout6($posts, $settings);
                break;

            case 'case_study_layout1':
                consultio_get_case_study_layout1($posts, $settings);
                break;

            default:
                return false;
                break;
        }
    }
}

add_action( 'wp_ajax_consultio_load_more_post_grid', 'consultio_load_more_post_grid' );
add_action( 'wp_ajax_nopriv_consultio_load_more_post_grid', 'consultio_load_more_post_grid' );
if(!function_exists('consultio_load_more_post_grid')){
    function consultio_load_more_post_grid(){
        try{
            if(!isset($_POST['settings'])){
                throw new Exception(__('Something went wrong while requesting. Please try again!', 'consultio'));
            }
            $settings = $_POST['settings'];
            set_query_var('paged', $settings['paged']);
            extract(ct_get_posts_of_grid($settings['posttype'], [
                'source' => isset($settings['source'])?$settings['source']:'',
                'orderby' => isset($settings['orderby'])?$settings['orderby']:'date',
                'order' => isset($settings['order'])?$settings['order']:'desc',
                'limit' => isset($settings['limit'])?$settings['limit']:'6',
                'post_ids' => '',
            ]));
            ob_start();
            consultio_get_post_grid($posts, $settings);
            $html = ob_get_clean();
            wp_send_json(
                array(
                    'status' => true,
                    'message' => esc_attr__('Load Successfully!', 'consultio'),
                    'data' => array(
                        'html' => $html,
                        'paged' => $settings['paged'],
                        'posts' => $posts,
                        'max' => $max,
                    ),
                )
            );
        }
        catch (Exception $e){
            wp_send_json(array('status' => false, 'message' => $e->getMessage()));
        }
        die;
    }
}

/**
* Display navigation to next/previous post when applicable.
*/
function consultio_post_nav_default() {
    global $post;
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
        return;
    ?>
    <?php
    $next_post = get_next_post();
    $previous_post = get_previous_post();

    if( !empty($next_post) || !empty($previous_post) ) { 
        ?>
        <div class="entry-navigation">
            <div class="nav-links">
                <?php if ( is_a( $previous_post , 'WP_Post' ) && get_the_title( $previous_post->ID ) != '') { 
                    $prev_img_id = get_post_thumbnail_id($previous_post->ID);
                    $prev_img_url = wp_get_attachment_image_src($prev_img_id, 'thumbnail');
                    ?>
                    <div class="nav-item nav-post-prev">
                        <?php if(!empty($prev_img_id)) : ?>
                            <div class="nav-post-img">
                                <a  href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>"><img src="<?php echo wp_kses_post($prev_img_url[0]); ?>" /></a>
                            </div>
                        <?php endif; ?>
                        <div class="nav-post-meta">
                            <label><?php echo esc_html__('Previous Post', 'consultio'); ?></label>
                            <a  href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>"><?php echo get_the_title( $previous_post->ID ); ?></a>
                        </div>
                    </div>
                <?php } ?>
                <?php if ( is_a( $next_post , 'WP_Post' ) && get_the_title( $next_post->ID ) != '') {
                    $next_img_id = get_post_thumbnail_id($next_post->ID);
                    $next_img_url = wp_get_attachment_image_src($next_img_id, 'thumbnail');
                    ?>
                    <div class="nav-item nav-post-next">
                        <?php if(!empty($next_img_id)) : ?>
                            <div class="nav-post-img">
                                <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><img src="<?php echo wp_kses_post($next_img_url[0]); ?>" /></a>
                            </div>
                        <?php endif; ?>
                        <div class="nav-post-meta">
                            <label><?php echo esc_html__('Next Post', 'consultio'); ?></label>
                            <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>"><?php echo get_the_title( $next_post->ID ); ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div><!-- .nav-links -->
        </div>
    <?php }
}

/**
 * Custom Widget Categories
 */
add_filter('wp_list_categories', 'consultio_cat_count_span');
function consultio_cat_count_span($output) {
    $dir = is_rtl() ? 'left' : 'right';
    $output = str_replace("\t", '', $output);
    $output = str_replace(")\n</li>", ')</li>', $output);
    $output = str_replace('</a> (', '<span class="count '.$dir.'">', $output);
    $output = str_replace(")</li>", "</span></a></li>", $output);
    $output = str_replace("\n<ul", "</span></a>\n<ul", $output);
    return $output;
}


/**
 * Custom Widget Archive
 */
add_filter('get_archives_link', 'consultio_archive_count_span');
function consultio_archive_count_span($links) {
    $dir = is_rtl() ? 'left' : 'right';
    $links = str_replace('</a>&nbsp;(', ' <span class="count '.$dir.'">', $links);
    $links = str_replace(')', '</span></a>', $links);
    return $links;
}

/**
 * Custom Widget Product Categories 
 */
add_filter('wp_list_categories', 'consultio_wc_cat_count_span');
function consultio_wc_cat_count_span($links) {
    $dir = is_rtl() ? 'left' : 'right';
    $links = str_replace('</a> <span class="count">(', ' <span class="count '.$dir.'">', $links);
    $links = str_replace(')</span>', '</span></a>', $links);
    return $links;
}

/* Favicon */
function consultio_site_favicon(){
    
    $favicon = consultio_get_opt( 'favicon' );
    
    if(!empty($favicon['url']))
        echo '<link rel="icon" type="image/png" href="'.esc_url($favicon['url']).'"/>';
}
add_action('wp_head', 'consultio_site_favicon');

/**
 * Add Template Woocommerce
 */
if(class_exists('Woocommerce')){
    require_once( get_template_directory() . '/woocommerce/wc-function-hooks.php' );
}

/**
 * Show Cart Sidebar Hidden
 */
add_action('wp_ajax_nopriv_item_added', 'consultio_addedtocart_sweet_message');
add_action('wp_ajax_item_added', 'consultio_addedtocart_sweet_message');
function consultio_addedtocart_sweet_message() {
    echo isset($_POST['id']) && $_POST['id'] > 0 ? (int) esc_attr($_POST['id']) : false;
    die();
}
add_action('wp_footer', 'consultio_product_count_check');
function consultio_product_count_check() {
    if (class_exists('Woocommerce') && is_checkout())
        return;
    ?>
    <script type="text/javascript">
        jQuery( function($) {
            if ( typeof wc_add_to_cart_params === 'undefined' )
                return false;

            $(document.body).on( 'added_to_cart', function( event, fragments, cart_hash, $button ) {
                var $pid = $button.data('product_id');

                $.ajax({
                    type: 'POST',
                    url: wc_add_to_cart_params.ajax_url,
                    data: {
                        'action': 'item_added',
                        'id'    : $pid
                    },
                    success: function (response) {
                        $('.ct-widget-cart-wrap').addClass('open');
                    }
                });
            });
        });
    </script>
    <?php
}


/**
 * Animate
*/

function consultio_animate() {
    $ct_animate = array(
        '' => 'None',
        'wow bounce' => 'bounce',
        'wow flash' => 'flash',
        'wow pulse' => 'pulse',
        'wow rubberBand' => 'rubberBand',
        'wow shake' => 'shake',
        'wow swing' => 'swing',
        'wow tada' => 'tada',
        'wow wobble' => 'wobble',
        'wow bounceIn' => 'bounceIn',
        'wow bounceInDown' => 'bounceInDown',
        'wow bounceInLeft' => 'bounceInLeft',
        'wow bounceInRight' => 'bounceInRight',
        'wow bounceInUp' => 'bounceInUp',
        'wow bounceOut' => 'bounceOut',
        'wow bounceOutDown' => 'bounceOutDown',
        'wow bounceOutLeft' => 'bounceOutLeft',
        'wow bounceOutRight' => 'bounceOutRight',
        'wow bounceOutUp' => 'bounceOutUp',
        'wow fadeIn' => 'fadeIn',
        'wow fadeInDown' => 'fadeInDown',
        'wow fadeInDownBig' => 'fadeInDownBig',
        'wow fadeInLeft' => 'fadeInLeft',
        'wow fadeInLeftBig' => 'fadeInLeftBig',
        'wow fadeInRight' => 'fadeInRight',
        'wow fadeInRightBig' => 'fadeInRightBig',
        'wow fadeInUp' => 'fadeInUp',
        'wow fadeInUpBig' => 'fadeInUpBig',
        'wow fadeOut' => 'fadeOut',
        'wow fadeOutDown' => 'fadeOutDown',
        'wow fadeOutDownBig' => 'fadeOutDownBig',
        'wow fadeOutLeft' => 'fadeOutLeft',
        'wow fadeOutLeftBig' => 'fadeOutLeftBig',
        'wow fadeOutRight' => 'fadeOutRight',
        'wow fadeOutRightBig' => 'fadeOutRightBig',
        'wow fadeOutUp' => 'fadeOutUp',
        'wow fadeOutUpBig' => 'fadeOutUpBig',
        'wow flip' => 'flip',
        'wow flipInX' => 'flipInX',
        'wow flipInY' => 'flipInY',
        'wow flipOutX' => 'flipOutX',
        'wow flipOutY' => 'flipOutY',
        'wow lightSpeedIn' => 'lightSpeedIn',
        'wow lightSpeedOut' => 'lightSpeedOut',
        'wow rotateIn' => 'rotateIn',
        'wow rotateInDownLeft' => 'rotateInDownLeft',
        'wow rotateInDownRight' => 'rotateInDownRight',
        'wow rotateInUpLeft' => 'rotateInUpLeft',
        'wow rotateInUpRight' => 'rotateInUpRight',
        'wow rotateOut' => 'rotateOut',
        'wow rotateOutDownLeft' => 'rotateOutDownLeft',
        'wow rotateOutDownRight' => 'rotateOutDownRight',
        'wow rotateOutUpLeft' => 'rotateOutUpLeft',
        'wow rotateOutUpRight' => 'rotateOutUpRight',
        'wow hinge' => 'hinge',
        'wow rollIn' => 'rollIn',
        'wow rollOut' => 'rollOut',
        'wow zoomIn' => 'zoomIn',
        'wow zoomInDown' => 'zoomInDown',
        'wow zoomInLeft' => 'zoomInLeft',
        'wow zoomInRight' => 'zoomInRight',
        'wow zoomInUp' => 'zoomInUp',
        'wow zoomOut' => 'zoomOut',
        'wow zoomOutDown' => 'zoomOutDown',
        'wow zoomOutLeft' => 'zoomOutLeft',
        'wow zoomOutRight' => 'zoomOutRight',
        'wow zoomOutUp' => 'zoomOutUp',
        'wow skewIn' => 'skewInLeft',
        'wow skewInRight' => 'skewInRight',
    );
    return $ct_animate;
}

function consultio_animate_case() {
    $ct_animate = array(
        '' => 'None',
        'case-fade-in-up' => 'Case Fade In Up',
        'bounce' => 'bounce',
        'flash' => 'flash',
        'pulse' => 'pulse',
        'rubberBand' => 'rubberBand',
        'shake' => 'shake',
        'swing' => 'swing',
        'tada' => 'tada',
        'wobble' => 'wobble',
        'bounceIn' => 'bounceIn',
        'bounceInDown' => 'bounceInDown',
        'bounceInLeft' => 'bounceInLeft',
        'bounceInRight' => 'bounceInRight',
        'bounceInUp' => 'bounceInUp',
        'bounceOut' => 'bounceOut',
        'bounceOutDown' => 'bounceOutDown',
        'bounceOutLeft' => 'bounceOutLeft',
        'bounceOutRight' => 'bounceOutRight',
        'bounceOutUp' => 'bounceOutUp',
        'fadeIn' => 'fadeIn',
        'fadeInDown' => 'fadeInDown',
        'fadeInDownBig' => 'fadeInDownBig',
        'fadeInLeft' => 'fadeInLeft',
        'fadeInLeftBig' => 'fadeInLeftBig',
        'fadeInRight' => 'fadeInRight',
        'fadeInRightBig' => 'fadeInRightBig',
        'fadeInUp' => 'fadeInUp',
        'fadeInUpBig' => 'fadeInUpBig',
        'fadeOut' => 'fadeOut',
        'fadeOutDown' => 'fadeOutDown',
        'fadeOutDownBig' => 'fadeOutDownBig',
        'fadeOutLeft' => 'fadeOutLeft',
        'fadeOutLeftBig' => 'fadeOutLeftBig',
        'fadeOutRight' => 'fadeOutRight',
        'fadeOutRightBig' => 'fadeOutRightBig',
        'fadeOutUp' => 'fadeOutUp',
        'fadeOutUpBig' => 'fadeOutUpBig',
        'flip' => 'flip',
        'flipInX' => 'flipInX',
        'flipInY' => 'flipInY',
        'flipOutX' => 'flipOutX',
        'flipOutY' => 'flipOutY',
        'lightSpeedIn' => 'lightSpeedIn',
        'lightSpeedOut' => 'lightSpeedOut',
        'rotateIn' => 'rotateIn',
        'rotateInDownLeft' => 'rotateInDownLeft',
        'rotateInDownRight' => 'rotateInDownRight',
        'rotateInUpLeft' => 'rotateInUpLeft',
        'rotateInUpRight' => 'rotateInUpRight',
        'rotateOut' => 'rotateOut',
        'rotateOutDownLeft' => 'rotateOutDownLeft',
        'rotateOutDownRight' => 'rotateOutDownRight',
        'rotateOutUpLeft' => 'rotateOutUpLeft',
        'rotateOutUpRight' => 'rotateOutUpRight',
        'hinge' => 'hinge',
        'rollIn' => 'rollIn',
        'rollOut' => 'rollOut',
        'zoomIn' => 'zoomIn',
        'zoomInDown' => 'zoomInDown',
        'zoomInLeft' => 'zoomInLeft',
        'zoomInRight' => 'zoomInRight',
        'zoomInUp' => 'zoomInUp',
        'zoomOut' => 'zoomOut',
        'zoomOutDown' => 'zoomOutDown',
        'zoomOutLeft' => 'zoomOutLeft',
        'zoomOutRight' => 'zoomOutRight',
        'zoomOutUp' => 'zoomOutUp',
        'pxl-split-text split-in-fade' => 'Slip Text In Fade',
        'pxl-split-text split-in-right' => 'Slip Text In Right',
        'pxl-split-text split-in-left'  => 'Slip Text In Left',
        'pxl-split-text split-in-up'    => 'Slip Text In Up',
        'pxl-split-text split-in-down'  => 'Slip Text In Down',
        'pxl-split-text split-in-rotate'  => 'Slip Text In Rotate',
        'pxl-split-text split-in-scale'  => 'Slip Text In Scale',
    );
    return $ct_animate;
}


/**
 * Demo Bar
 */
function consultio_icon_cart_bar() { 
    $cart_icon_sidebar = consultio_get_opt( 'cart_icon_sidebar', false );
    if(class_exists('Woocommerce') && $cart_icon_sidebar) : ?>
        <div class="ct-cart-bar">
            <div class="ct-cart-option">
                <i class="far fac-shopping-cart"></i>
                <span class="ct-cart-count-sidebar"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'consultio' ), WC()->cart->cart_contents_count ); ?></span>
            </div>
        </div>
<?php endif; }

/**
 * Demo Bar
 */
function consultio_demo_bar() { ?>
    <div class="ct-demo-bar">
        <div class="ct-demo-option">
            <a class="choose-demo" href="javascript:;">
                <span>Choose Demos</span>
                <i class="far fac-cog"></i>
            </a>
            <a href="https://doc.casethemes.net/consultio/" target="_blank">
                <span>Documentation</span>
                <i class="far fac-file-alt"></i>
            </a>
            <a href="https://casethemes.ticksy.com/submit/#100016217" target="_blank">
                <span>Submit a Ticket</span>
                <i class="far fac-life-ring"></i>
            </a>
            <a href="https://1.envato.market/m5DeKO" target="_blank">
                <span>Purchase Theme</span>
                <i class="far fac-shopping-cart"></i>
            </a>
        </div>
        <div class="ct-demo-bar-inner">
            <div class="ct-demo-bar-close ct-icon-close"></div>
            <div class="ct-demo-bar-meta">
                <h4>Pre-Built Demos Collection</h4>
                <p>Consultio comes with a beautiful collection of modern, easily importable, and highly customizable demo layouts. Any of which can be installed via one click.</p>
            </div>
            <div class="ct-demo-bar-list">
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo1.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Finance<label>Main</label></h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo-insurance2.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-insurance2/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-insurance2/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">Insurance 2<label>New</label></h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo-seo1.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-seo/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-seo/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">SEO<label>New</label></h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo-fintech1.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-fintech/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-fintech/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">Fintech<label>New</label></h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo68.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-cryptocurrency/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-cryptocurrency/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">Cryptocurrency</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo65.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-construction/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-construction/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">Business Construction</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo67.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business-coach/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business-coach/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6 class="ct-demo-title">Business Coach</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo19.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Consulting</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo63.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting2" target="_blank">View Demo</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting2/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Consulting 2</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo64.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting3" target="_blank">View Demo</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting3/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Consulting 3</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo17.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance2" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance2/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Finance 2</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo25.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance3/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance3/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Finance 3</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo27.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance4" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance4/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Finance 4</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo29.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance5" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-finance5/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Finance 5</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo26.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-digital-marketing" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-digital-marketing/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Digital Marketing</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo18.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-rtl/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-rtl/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Finance RTL</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo24.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-digital" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-digital/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Digital Agency</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo23.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-immigration" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-immigration/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Immigration</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo22.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-agency" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-agency/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Agency</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo12.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate1/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate1/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Corporate 1</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo13.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate2/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate2/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Corporate 2</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo14.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate3/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corporate3/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Corporate 3</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo2.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Business 1</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo16.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business2/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business2/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Business 2</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo21.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business3/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business3/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Business 3</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo28.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business4" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-business4/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Business 4</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo3.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-law/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-law/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Law</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo4.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-startup/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-startup/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Startup</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo5.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-it/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-it/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>IT Solution</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo6.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-tax/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-tax/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Tax Consulting</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo7.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-hr/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-hr/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Human Resource</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo8.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-coach/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-coach/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Life Coach</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo9.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-marketing/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-marketing/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Marketing</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo10.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-medical/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-medical/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Medical</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo11.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-insurance/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-insurance/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Insurance</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo15.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corona/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-corona/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Corona</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo20.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-software/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-software/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>
                    </div>
                    <h6>Software</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo30.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-marketing-agency/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-marketing-agency/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Marketing Agency</h6>
                </div>
                <div class="ct-demo-bar-item">
                    <div class="ct-demo-bar-item-inner">
                        <img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/home-demo/demo31.jpg'); ?>" alt="Demo" />
                        <div class="ct-demo-bar-holder">
                            
                            <div class="ct-demo-btn-group">
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting-agency/" target="_blank">Multi Pages</a>
                                <a class="btn btn-default" href="http://demo.casethemes.net/consultio-consulting-agency/home-onepage/" target="_blank">One Page</a>
                            </div>
                        </div>

                    </div>
                    <h6 class="ct-demo-title">Consulting Agency</h6>
                </div>
            </div>
        </div>
    </div>
<?php }

/* Post Type Support */
function consultio_add_cpt_support() {
    $cpt_support = get_option( 'elementor_cpt_support' );
    
    if( ! $cpt_support ) {
        $cpt_support = [ 'page', 'post', 'portfolio', 'service', 'case-study', 'footer', 'ct-mega-menu', 'header', 'page-title' ];
        update_option( 'elementor_cpt_support', $cpt_support );
    }
    
    else if( ! in_array( 'portfolio', $cpt_support ) ) {
        $cpt_support[] = 'portfolio';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'service', $cpt_support ) ) {
        $cpt_support[] = 'service';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'case-study', $cpt_support ) ) {
        $cpt_support[] = 'case-study';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'courses', $cpt_support ) ) {
        $cpt_support[] = 'courses';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'footer', $cpt_support ) ) {
        $cpt_support[] = 'footer';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'header', $cpt_support ) ) {
        $cpt_support[] = 'header';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'ct-mega-menu', $cpt_support ) ) {
        $cpt_support[] = 'ct-mega-menu';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'header', $cpt_support ) ) {
        $cpt_support[] = 'header';
        update_option( 'elementor_cpt_support', $cpt_support );
    }

    else if( ! in_array( 'page-title', $cpt_support ) ) {
        $cpt_support[] = 'page-title';
        update_option( 'elementor_cpt_support', $cpt_support );
    }
}
add_action( 'after_switch_theme', 'consultio_add_cpt_support');

/* Mouse Move Animation */
function consultio_mouse_move_animation() { 
    $mouse_move_animation = consultio_get_opt( 'mouse_move_animation', false );
    ?>  
    <div id="ct-mouse-move" <?php if($mouse_move_animation == false) : ?>style="display: none;"<?php endif; ?>>
        <div class="circle-cursor circle-cursor--outer"></div>
        <div class="circle-cursor circle-cursor--inner"></div>
    </div>
<?php }

/* Addd shortcode Video button */
if(function_exists( 'ct_register_shortcode' )) {
    function consultio_text_highlight_shortcode( $atts = array() ) {
        extract(shortcode_atts(array(
         'text' => '',
        ), $atts));

        ob_start();
        if(!empty($text)) : ?>
        <cite class="ct-text-highlight">
            <?php echo esc_attr($text); ?>
        </cite>
        <?php  endif;
        $output = ob_get_clean();

        return $output;
    }
    ct_register_shortcode('highlight', 'consultio_text_highlight_shortcode');
}

if(function_exists( 'ct_register_shortcode' )) {
    function consultio_year() { 
        ob_start(); ?>
        <span class="ct-year"><?php echo esc_attr(date("Y")); ?></span>
        <?php
        $output = ob_get_clean();

        return $output;
    }
    ct_register_shortcode('ct_year', 'consultio_year');
}

/* Button Shortcode  */
if(function_exists( 'ct_register_shortcode' )) {
    function consultio_btn_shortcode( $atts = array() ) {
        extract(shortcode_atts(array(
         'text' => '',
         'style' => 'btn-slider1',
         'icon_class' => '',
        ), $atts));

        ob_start();
        if(!empty($text)) : ?>
            <span class="<?php echo esc_attr($style); ?>">
                <span class="pxl--btn-text" data-text="<?php echo esc_attr($text); ?>">
                    <?php echo esc_attr($text); ?>
                </span>
            </span>
        <?php  endif;
        $output = ob_get_clean();

        return $output;
    }
    ct_register_shortcode('ct_button', 'consultio_btn_shortcode');
}

/* Nesletter Popup */
function consultio_newsletter_popup() { 
    $newsletter_popup = consultio_get_opt( 'newsletter_popup', false );
    $newslette_title = consultio_get_opt( 'newslette_title' );
    $newslette_desc = consultio_get_opt( 'newslette_desc' );
    $newslette_close_text = consultio_get_opt( 'newslette_close_text' );
    $newslette_email_placeholder = consultio_get_opt( 'newslette_email_placeholder', esc_html__('Your mail address', 'consultio') );
    if($newsletter_popup && class_exists('Newsletter')) : 
        wp_enqueue_script('ct-cookie');
        wp_enqueue_script('newsletter-popup', get_template_directory_uri() . '/assets/js/newsletter-popup.js', array('jquery'), 'all', true);
        ?>  
        <div id="ct-newsletter-popup">
            <div class="ct-newsletter-content">
                <div class="ct-newsletter-content-inner">
                    <div class="ct-newsletter-close"><i class="zmdi zmdi-close"></i></div>
                    <div class="ct-newsletter-holder">
                        <div class="ct-newsletter-meta">
                            <h4 class="ct-newsletter-title">
                                <?php if(!empty($newslette_title)) {
                                    echo esc_attr($newslette_title); 
                                } else {
                                    echo esc_html__('Subscribe to our newsletter', 'consultio');
                                } ?>
                            </h4>
                            <div class="ct-newsletter-desc">
                                <?php if(!empty($newslette_desc)) {
                                    echo esc_attr($newslette_desc); 
                                } else {
                                    echo esc_html__('Sign up to receive latest news, updates, promotions, and special offers delivered directly to your inbox.', 'consultio');
                                } ?>
                            </div>
                            <?php echo do_shortcode( '[newsletter_form][newsletter_field name="email" label="'.$newslette_email_placeholder.'"][/newsletter_form]' ); ?>
                            <div class="ct-newsletter-hide"><span>
                                <?php if(!empty($newslette_close_text)) {
                                    echo esc_attr($newslette_close_text); 
                                } else {
                                    echo esc_html__('No, thanks', 'consultio');
                                } ?>
                            </span></div>
                        </div>
                        <div class="ct-newsletter-image"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/newsletter-icon.png'); ?>" alt="<?php echo esc_attr($newslette_title); ?>" /></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php }

/* Support excerpt for page */
add_action('init', 'consultio_add_excerpts_to_pages');
function consultio_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}

/* Custom Archive Post Type Link */
add_filter( 'post_type_archive_link', 'consultio_get_post_type_archive_link', 10, 2 );
function consultio_get_post_type_archive_link($link, $post_type){

    if( $post_type == 'portfolio'){
        $archive_portfolio_link = consultio_get_opt( 'archive_portfolio_link' );
            if( !empty($archive_portfolio_link) ){ 
            $link = get_permalink($archive_portfolio_link);
        }
    }

    if( $post_type == 'service'){
        $archive_service_link = consultio_get_opt( 'archive_service_link' );
            if( !empty($archive_service_link) ){ 
            $link = get_permalink($archive_service_link);
        }
    }

    if( $post_type == 'case-study'){
        $archive_case_study_link = consultio_get_opt( 'archive_case_study_link' );
            if( !empty($archive_case_study_link) ){ 
            $link = get_permalink($archive_case_study_link);
        }
    }

  return $link;
}