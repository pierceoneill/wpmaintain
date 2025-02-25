<?php
function echo_rss_generator()
{
?>
<div class="wp-header-end"></div>
<div class="wrap gs_popuptype_holder seo_pops">
    <div>
        <form id="myForm" method="post" action="admin.php?page=echo_rss_generator">
<?php
    wp_nonce_field( 'echo_save_rules', '_echor_nonce' );
    if( isset($_GET['settings-updated']) ) 
{ 
?>
<div>
<p class="cr_saved_notif"><strong><?php echo esc_html__("Settings saved.", 'rss-feed-post-generator-echo');?></strong></p>
</div>
<?php 
}
?>
<div>
                    <div class="hideMain">
                    <hr/>              
        <div class="table-responsive">
                    <table id="mainRules" class="responsive table cr_main_table">
				<thead>
					<tr>
                    <th><?php echo esc_html__("ID", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("This is the ID of the rule. ", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
						<th class="cr_100"><?php echo esc_html__("Generated Feed Type", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("Select if you want to create a RSS feed or an Atom feed.", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
                    <th><?php echo esc_html__("Generated RSS Feed Name", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("Insert your feed name. This will be also part of the feed URL. Ex: yourdomain.com/?feed=feedname. Another URL that will be generated for your feed is: yourdomain.com/feedname. If you do not specify a value for this field, the 'echo-feed' default value will be used.", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
						<th class="cr_80_2"><?php echo esc_html__("Feed Post Count", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("How many posts do you want to show in your feed?", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
                    <th class="cr_80_2"><?php echo esc_html__("Full Content", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("Choose if you want to show full post content in feeds or only the post excerpt.", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
                    <th class="cr_max_width_70"><?php echo esc_html__("Feed Update Period", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("Choose the update period for the generated feed.", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
                    <th><?php echo esc_html__("Advanced Feed Query (for advanced users only!)", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo sprintf( wp_kses( __( "Warning! This is for advanced users only! If used improperly, this can break your feed! Do not set this if you do not know what you are doing. Set the advanced query parameters for what posts to show. Learn more about these parameters <a href='%s' target='_blank'>here</a>. Example: to show in feed only posts from a specific category, insert: &category_name=<PUT_YOU_CATEGORY_HERE>", 'rss-feed-post-generator-echo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url_raw( 'https://developer.wordpress.org/reference/classes/wp_query/' ) );
?>
                        </div>
                    </div></th>
                    <th><?php echo esc_html__("More Options", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__( "More settings.", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
                    <th class="cr_max_width_20"><?php echo esc_html__("Del", 'rss-feed-post-generator-echo');?><div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                        <div class="bws_hidden_help_text cr_min_260px">
                                            <?php
    echo esc_html__("Do you want to delete this rule?", 'rss-feed-post-generator-echo');
?>
                        </div>
                    </div></th>
					</tr>
				</thead>
				<tbody>
					<?php echo echo_expand_rules_rss(); ?>
					<tr>
                        <td class="cr_short_td">-</td>
                        <td class="cr_max_width_70 cr_centered"><select class="cr_width_full" id="feed_type" name="echo_RSS_Settings[feed_type][]" >
                                  <option value="rss" selected><?php echo esc_html__("RSS 2.0", 'rss-feed-post-generator-echo');?></option>
                                  <option value="atom"><?php echo esc_html__("Atom 1.0", 'rss-feed-post-generator-echo');?></option>
                        </select>   </td>
						<td class="cr_max_width_150 cr_centered"><input class="cr_width_full" type="text" name="echo_RSS_Settings[feed_name][]" value="" placeholder="Please insert your desired feed name"></td>
                        <td class="cr_max_width_50 cr_centered"><input class="cr_50" type="number" step="1" min="0" name="echo_RSS_Settings[post_count][]" value="50" placeholder="Please insert the number of posts to show in feed"></td>
                        <td class="cr_max_width_50 cr_centered"><input type="checkbox" id="full_content" name="echo_RSS_Settings[full_content][]"></td>
                        <td class="cr_max_width_70 cr_centered"><select class="cr_width_full" id="update_period" name="echo_RSS_Settings[update_period][]" >
                                  <option value="hourly" selected><?php echo esc_html__("Hourly", 'rss-feed-post-generator-echo');?></option>
                                  <option value="daily"><?php echo esc_html__("Daily", 'rss-feed-post-generator-echo');?></option>
                                  <option value="weekly"><?php echo esc_html__("Weekly", 'rss-feed-post-generator-echo');?></option>
                                  <option value="monthly"><?php echo esc_html__("Monthly", 'rss-feed-post-generator-echo');?></option>
                                  <option value="yearly"><?php echo esc_html__("Yearly", 'rss-feed-post-generator-echo');?></option>
                    </select>   </td>
                    <td class="cr_max_width_150 cr_centered"><input class="cr_width_full" type="text" name="echo_RSS_Settings[feed_query][]" value="" placeholder="Please insert your desired feed query (optional)"></td>
                    <td class="cr_width_70">
                           <input type="button" id="mybtnfzr" value="Settings">
                           <div id="mymodalfzr" class="codemodalfzr">
                              <div class="codemodalfzr-content">
                                 <div class="codemodalfzr-header">
                                    <span id="echo_close" class="codeclosefzr">&times;</span>
                                    <h2><span class="cr_color_white"><?php echo esc_html__("New Rule", 'rss-feed-post-generator-echo');?></span> <?php echo esc_html__("Advanced Settings", 'rss-feed-post-generator-echo');?></h2>
                                 </div>
                                 <div class="codemodalfzr-body">
                                    <div class="table-responsive">
                                       <table class="responsive table cr_main_table_nowr">
                                          <tr>
                                             <td colspan="2">
                                                <h3><?php echo esc_html__("Posting Options:", 'rss-feed-post-generator-echo');?></h3>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td class="cr_min_240">
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Set the URL parameter to be added to each items from the RSS feeds.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Add URL Parameter to Feed Items:", 'rss-feed-post-generator-echo');?></b>&nbsp;
                                             </td>
                                             <td>
                                             <input type="text" name="echo_RSS_Settings[url_param_feeds][]" value="" placeholder="Additional URL parameter" class="cr_width_full">
                                             </div>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td>
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Run regex on feed content. To disable this feature, leave this field blank. You can add multiple Regex expressions, each on a different line.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Run Regex On Feed Content:", 'rss-feed-post-generator-echo');?></b>
                                             </td>
                                             <td>
                                             <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[strip_by_regex][]" placeholder="regex expression" class="cr_width_full"></textarea>
                                             </div>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td>
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Replace the above regex matches with this regex expression. If you want to strip matched content, leave this field blank. You can add multiple replacement expressions, each on a different line (in this case, each will match the corresponding expression from the above Regex field.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Replace Matches From Regex:", 'rss-feed-post-generator-echo');?></b>
                                             </td>
                                             <td>
                                             <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[replace_regex][]" placeholder="regex replacement" class="cr_width_full"></textarea>
                                             </div>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td>
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Run regex on post title. To disable this feature, leave this field blank. You can add multiple Regex expressions, each on a different line.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Run Regex On Title:", 'rss-feed-post-generator-echo');?></b>
                                             </td>
                                             <td>
                                             <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[strip_by_regex_title][]" placeholder="regex expression" class="cr_width_full"></textarea>
                                             </div>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td>
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Replace the above regex matches with this regex expression. If you want to strip matched content, leave this field blank. You can add multiple replacement expressions, each on a different line (in this case, each will match the corresponding expression from the above Regex field.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Replace Title Matches From Regex:", 'rss-feed-post-generator-echo');?></b>
                                             </td>
                                             <td>
                                             <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[replace_regex_title][]" placeholder="regex replacement" class="cr_width_full"></textarea>
                                             </div>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td>
                                                <div>
                                                   <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                                      <div class="bws_hidden_help_text cr_min_260px">
                                                         <?php
                                                            echo esc_html__("Select if you don't want to add the WordPress RSS header to the created feed.", 'rss-feed-post-generator-echo');
                                                            ?>
                                                      </div>
                                                   </div>
                                                   <b><?php echo esc_html__("Don't Add WordPress Feed Header to The Created RSS Feed:", 'rss-feed-post-generator-echo');?></b>
                                             </td>
                                             <td>
                                             <input type="checkbox" id="full_content" name="echo_RSS_Settings[no_head][]">
                                             </div>
                                             </td>
                                          </tr>
                                       </table>
                                    </div>
                                 </div>
                                 <div class="codemodalfzr-footer">
                                    <br/>
                                    <h3 class="cr_inline">Echo RSS Feed Generator</h3>
                                    <span id="echo_ok" class="codeokfzr cr_inline">OK&nbsp;</span>
                                    <br/><br/>
                                 </div>
                              </div>
                           </div>
                        </td>
                        <td class="cr_shrt_td2"><span class="cr_gray20">X</span></td>
					</tr>
				</tbody>
			</table>
            </div>
        
        </div>
        </div>
        <hr/> 
<div><p class="crsubmit"><input type="submit" name="btnSubmit" id="btnSubmit" class="button button-primary" onclick="unsaved = false;" value="<?php echo esc_html__("Save Settings", 'rss-feed-post-generator-echo');?>"/></p></div><div>
<?php echo esc_html__("New! AI generated shortcodes supported, click for details:", 'rss-feed-post-generator-echo');?>&nbsp;<a href="https://coderevolution.ro/knowledge-base/faq/how-to-create-ai-generated-content-from-any-plugin-built-by-coderevolution/" target="_blank"><img src="https://i.ibb.co/gvTNWr6/artificial-intelligence-badge.png" alt="artificial-intelligence-badge" title="AI content generator support, when used together with the Aiomatic plugin"></a><br/><br/><a href="https://www.youtube.com/watch?v=5rbnu_uis7Y" target="_blank"><?php echo esc_html__("Nested Shortcodes also supported!", 'rss-feed-post-generator-echo');?></a><br/><?php echo esc_html__("Confused about rule running status icons?", 'rss-feed-post-generator-echo');?> <a href="http://coderevolution.ro/knowledge-base/faq/how-to-interpret-the-rule-running-visual-indicators-red-x-yellow-diamond-green-tick-from-inside-plugins/" target="_blank"><?php echo esc_html__("More info", 'rss-feed-post-generator-echo');?></a><br/>
<div class="cr_none" id="midas_icons">
<table><tr><td><img id="run_img" src="<?php echo esc_url_raw(plugin_dir_url(dirname(__FILE__)) . 'images/running.gif');?>" alt="Running" title="status"></td><td><?php echo esc_html__("In Progress", 'rss-feed-post-generator-echo');?> - <b><?php echo esc_html__("Importing is Running", 'rss-feed-post-generator-echo');?></b></td></tr>
<tr><td><img id="ok_img" src="<?php echo esc_url_raw(plugin_dir_url(dirname(__FILE__)) . 'images/ok.gif');?>" alt="OK"  title="status"></td><td><?php echo esc_html__("Success", 'rss-feed-post-generator-echo');?> - <b><?php echo esc_html__("New Posts Created", 'rss-feed-post-generator-echo');?></b></td></tr>
<tr><td><img id="fail_img" src="<?php echo esc_url_raw(plugin_dir_url(dirname(__FILE__)) . 'images/failed.gif');?>" alt="Faield" title="status"></td><td><?php echo esc_html__("Failed", 'rss-feed-post-generator-echo');?> - <b><?php echo esc_html__("An Error Occurred.", 'rss-feed-post-generator-echo');?> <b><?php echo esc_html__("Please check 'Activity and Logging' plugin menu for details.", 'rss-feed-post-generator-echo');?></b></td></tr>
<tr><td><img id="nochange_img" src="<?php echo esc_url_raw(plugin_dir_url(dirname(__FILE__)) . 'images/nochange.gif');?>" alt="NoChange" title="status"></td><td><?php echo esc_html__("No Change - No New Posts Created", 'rss-feed-post-generator-echo');?> - <b><?php echo esc_html__("Possible reasons:", 'rss-feed-post-generator-echo');?></b></td></tr><tr><td></td><td><ul><li>&#9658; <?php echo esc_html__("Already all posts are published that match your search and posts will be posted when new content will be available", 'rss-feed-post-generator-echo');?></li><li>&#9658; <?php echo esc_html__("Some restrictions you defined in the plugin's 'Main Settings'", 'rss-feed-post-generator-echo');?> <i>(<?php echo esc_html__("example: 'Minimum Content Word Count', 'Maximum Content Word Count', 'Minimum Title Word Count', 'Maximum Title Word Count', 'Banned Words List', 'Reuired Words List', 'Skip Posts Without Images'", 'rss-feed-post-generator-echo');?>)</i> <?php echo esc_html__("prevent posting of new posts.", 'rss-feed-post-generator-echo');?></li></ul></td></tr>
</table>
</div>
</div>
    </form>
    <p><?php echo esc_html__("Where can I find the generated feeds? Check out:", 'rss-feed-post-generator-echo');?> <strong>yourdomain.com/?feed=<i>feedname</i></strong> <?php echo esc_html__("to find them!", 'rss-feed-post-generator-echo');?>
</div>
</div>
<?php
}
if (isset($_POST['echo_RSS_Settings'])) {
	add_action('admin_init', 'echo_save_rules_rss');
}

        function echo_save_rules_rss($data2) {
            check_admin_referer( 'echo_save_rules', '_echor_nonce' );
			
            $data2 = $_POST['echo_RSS_Settings'];
			$rules = array();
			$cont = 0;
            if(isset($data2['feed_name'][0]))
            {
                for($i = 0; $i < sizeof($data2['feed_name']); ++$i) {
                    $bundle = array();
                    if($data2['feed_name'][$i] != '')
                    {
                        $bundle[] = trim( sanitize_text_field( $data2['feed_name'][$i] ) );
                        $bundle[] = trim( sanitize_text_field( $data2['post_count'][$i] ) );
                        $bundle[] = trim( sanitize_text_field( $data2['full_content'][$i] ) );
                        $bundle[] = trim( sanitize_text_field( $data2['update_period'][$i] ) );
                        $bundle[] = trim( $data2['feed_query'][$i] );
                        $bundle[] = trim( sanitize_text_field( $data2['feed_type'][$i] ) );
                        $bundle[] = trim( $data2['url_param_feeds'][$i] );
                        $bundle[] = trim( $data2['strip_by_regex'][$i] );
                        $bundle[] = trim( $data2['replace_regex'][$i] );
                        $bundle[] = trim( $data2['strip_by_regex_title'][$i] );
                        $bundle[] = trim( $data2['replace_regex_title'][$i] );
                        $bundle[] = trim( $data2['no_head'][$i] );
                        $rules[$cont] = $bundle;
                        $cont++;
                    }
                }
            }
            update_option('echo_RSS_Settings', $rules, false);
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
		}
        function echo_expand_rules_rss() {
			$rules = get_option('echo_RSS_Settings');
			$output = '';
            $cont = 0;
			if (!empty($rules)) {
				foreach ($rules as $request => $bundle[]) {
                    $bundle_values = array_values($bundle); 
                    $myValues = $bundle_values[$cont];
                    $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}} 
                    $feed_name = $array_my_values[0];
                    $post_count = $array_my_values[1];
                    $full_content = $array_my_values[2];
                    $update_period = $array_my_values[3];
                    $feed_query = $array_my_values[4];
                    $feed_type = $array_my_values[5];
                    $url_param_feeds = $array_my_values[6];
                    $strip_by_regex = $array_my_values[7];
                    $replace_regex = $array_my_values[8];
                    $strip_by_regex_title = $array_my_values[9];
                    $replace_regex_title = $array_my_values[10];
                    $no_head = $array_my_values[11];
                    wp_add_inline_script('echo-footer-script', 'createAdmin(' . esc_html($cont) . ');', 'after');
					$output .= '<tr>
                        <td class="cr_short_td">' . esc_html($cont) . ' - <a href="' . esc_url_raw(get_bloginfo('url') . '/?feed=' . esc_html($feed_name)) . '" target="_blank">link</a></td>
                        <td class="cr_max_width_70 cr_centered"><select class="cr_width_full" id="feed_type" name="echo_RSS_Settings[feed_type][]" >
                                  <option value="rss"';
                    if($feed_type == 'rss')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("RSS 2.0", 'rss-feed-post-generator-echo') . '</option>
                                  <option value="atom"';
                    if($feed_type == 'atom')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Atom 1.0", 'rss-feed-post-generator-echo') . '</option>
                        </select>   </td>
						<td class="cr_max_width_150 cr_centered"><input class="cr_width_full" type="text" name="echo_RSS_Settings[feed_name][]" value="'. esc_attr($feed_name) . '" placeholder="Please insert your desired feed name" required></td>
						<td class="cr_max_width_50 cr_centered"><input class="cr_50" type="number" step="1" min="0" name="echo_RSS_Settings[post_count][]" value="'. esc_attr($post_count) . '" placeholder="Please insert the number of posts to show in feed" required></td>
                        <td class="cr_max_width_50 cr_centered"><input type="checkbox" id="full_content" name="echo_RSS_Settings[full_content][]"';
                        if($full_content === '1')
                        {
                            $output .= ' checked';
                        }
                        $output .= '></td>
                        <td class="cr_max_width_70 cr_centered"><select class="cr_width_full" id="update_period" name="echo_RSS_Settings[update_period][]" >
                                  <option value="hourly"';
                    if($update_period == 'hourly')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Hourly", 'rss-feed-post-generator-echo') . '</option>
                                  <option value="daily"';
                    if($update_period == 'daily')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Daily", 'rss-feed-post-generator-echo') . '</option>
                                  <option value="weekly"';
                    if($update_period == 'weekly')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Weekly", 'rss-feed-post-generator-echo') . '</option>
                                  <option value="monthly"';
                    if($update_period == 'monthly')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Monthly", 'rss-feed-post-generator-echo') . '</option>
                                  <option value="yearly"';
                    if($update_period == 'yearly')
                    {
                        $output .= ' selected';
                    }
                    $output .= '>' . esc_html__("Yearly", 'rss-feed-post-generator-echo') . '</option>
                    </select>   </td>  </td>
                    <td class="cr_max_width_150 cr_centered"><input class="cr_width_full" type="text" name="echo_RSS_Settings[feed_query][]" value="'. stripslashes($feed_query) . '" placeholder="Please insert your desired feed query (optional)"></td>
                    <td class="cr_width_70">
                       <input type="button" id="mybtnfzr' . esc_html($cont) . '" value="Settings">
                       <div id="mymodalfzr' . esc_html($cont) . '" class="codemodalfzr">
     <div class="codemodalfzr-content">
       <div class="codemodalfzr-header">
         <span id="echo_close' . esc_html($cont) . '" class="codeclosefzr">&times;</span>
         <h2>' . esc_html__('Rule', 'rss-feed-post-generator-echo') . ' <span class="cr_color_white">ID ' . esc_html($cont) . '</span> ' . esc_html__('Advanced Settings', 'rss-feed-post-generator-echo') . '</h2>
       </div>
       <div class="codemodalfzr-body">
       <div class="table-responsive">
         <table class="responsive table cr_main_table_nowr">
         <tr><td colspan="2"><h3>' . esc_html__('Posting Options:', 'rss-feed-post-generator-echo') . '</h3></td></tr>
       <tr><td class="cr_min_240">
       <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Set the URL parameter to be added to each items from the RSS feeds.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Add URL Parameter to Feed Items", 'rss-feed-post-generator-echo') . ':</b>&nbsp;
                       
                       </td><td>
                       <input type="text" name="echo_RSS_Settings[url_param_feeds][]" value="' . esc_attr($url_param_feeds) . '" placeholder="Addition URL param" class="cr_width_full">
                           
           </div>
           </td></tr><tr><td>
           <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Run regex on post content. To disable this feature, leave this field blank. You can add multiple Regex expressions, each on a different line.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Run Regex On Content", 'rss-feed-post-generator-echo') . ':</b>
                       
                       </td><td>
                       <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[strip_by_regex][]" placeholder="regex" class="cr_width_full">' . esc_textarea($strip_by_regex) . '</textarea>
                           
           </div>
           </td></tr><tr><td>
           <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Replace the above regex matches with this regex expression. If you want to strip matched content, leave this field blank. You can add multiple replacement expressions, each on a different line (in this case, each will match the corresponding expression from the above Regex field.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Replace Matches From Regex", 'rss-feed-post-generator-echo') . ':</b>
                       
                       </td><td>
                       <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[replace_regex][]" placeholder="regex replacement" class="cr_width_full">' . esc_textarea($replace_regex) . '</textarea>
                           
           </div>
           </td></tr><tr><td>
           <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Run regex on post title. To disable this feature, leave this field blank. You can add multiple Regex expressions, each on a different line.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Run Regex On Title", 'rss-feed-post-generator-echo') . ':</b>
                       
                       </td><td>
                       <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[strip_by_regex_title][]" placeholder="regex" class="cr_width_full">' . esc_textarea($strip_by_regex_title) . '</textarea>
                           
           </div>
           </td></tr><tr><td>
           <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Replace the above regex matches with this regex expression. If you want to strip matched content, leave this field blank. You can add multiple replacement expressions, each on a different line (in this case, each will match the corresponding expression from the above Regex field.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Replace Title Matches From Regex", 'rss-feed-post-generator-echo') . ':</b>
                       
                       </td><td>
                       <textarea rows="1" class="cr_width_full" name="echo_RSS_Settings[replace_regex_title][]" placeholder="regex replacement" class="cr_width_full">' . esc_textarea($replace_regex_title) . '</textarea>
                           
           </div>
           </td></tr><tr><td>
           <div>
           <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                                           <div class="bws_hidden_help_text cr_min_260px">' . esc_html__("Select if you don't want to add the WordPress RSS header to the created feed.", 'rss-feed-post-generator-echo') . '
                           </div>
                       </div>
                       <b>' . esc_html__("Don't Add WordPress Feed Header to The Created RSS Feed", 'rss-feed-post-generator-echo') . ':</b>
                       
                       </td><td>
                       <input type="checkbox" id="no_head" name="echo_RSS_Settings[no_head][]"';
                        if($no_head === '1')
                        {
                            $output .= ' checked';
                        }
                        $output .= '>                           
           </div>
           </td></tr></table></div> 
       </div>
       <div class="codemodalfzr-footer">
         <br/>
         <h3 class="cr_inline">Echo RSS Feed Generator</h3><span id="echo_ok' . esc_html($cont) . '" class="codeokfzr cr_inline">OK&nbsp;</span>
         <br/><br/>
       </div>
     </div>
   
   </div>     
                       </td>
                       <td class="cr_shrt_td2"><span class="wpecho-delete">X</span></td>
					</tr>';
                    $cont = $cont + 1;
				}
			}
			return $output;
		}
?>