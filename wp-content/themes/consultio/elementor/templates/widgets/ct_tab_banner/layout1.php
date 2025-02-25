<?php
$html_id = ct_get_element_id($settings);
$active_tab = $widget->get_setting('active_tab', 1);
$tabs = $widget->get_setting('tabs', '');

if(isset($settings['tabs']) && !empty($settings['tabs']) && count($settings['tabs'])): ?>
    <div class="ct-tabs ct-tab-banner1">
        <div class="ct-tabs-title">
            <?php foreach ($settings['tabs'] as $key => $value): 
                $tab_title = isset($value['tab_title']) ? $value['tab_title'] : '';
                $tab_content = isset($value['tab_content']) ? $value['tab_content'] : '';
                ?>
                <div class="ct-tab-title <?php if($active_tab == $key + 1) { echo 'active'; } ?>" data-target="#<?php echo esc_attr($html_id.$key); ?>">
                    <h4><?php echo esc_attr($tab_title); ?></h4>
                    <p><?php echo ct_print_html($tab_content); ?></p>
                    <i class="tab-arrow"></i>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="ct-tabs-content">
            <?php foreach ($settings['tabs'] as $key => $value): 
                $banner = isset($value['banner']) ? $value['banner'] : '';
                ?>
                <div id="<?php echo esc_attr($html_id.$key); ?>" class="ct-tab-content" <?php if($active_tab == $key + 1) { ?>style="display: block;"<?php } ?>>
                    <?php if(!empty($banner['url'])) : ?>
                        <div class="ct-tab-image bg-image" style="background-image: url(<?php echo esc_url($banner['url']); ?>);"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>