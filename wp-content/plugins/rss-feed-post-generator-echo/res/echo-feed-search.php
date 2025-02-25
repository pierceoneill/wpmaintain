<?php
   function echo_feed_search()
   {
   
       $crawled_content = '';
       if(isset($_POST['echo_search_feeds']) && isset($_POST['echo_feed_keywords']))
       {
           $zurl = 'https://cloud.feedly.com/v3/search/feeds?query=' . urlencode($_POST['echo_feed_keywords']);
           if(isset($_POST['echo_search_feeds_type']) && trim($_POST['echo_search_feeds_type']) != '')
           {
               $zurl .= '&locale=' . urlencode(trim($_POST['echo_search_feeds_type']));
           }
           if(isset($_POST['echo_search_feeds_count']) && trim($_POST['echo_search_feeds_count']) != '')
           {
               $zurl .= '&count=' . urlencode(trim($_POST['echo_search_feeds_count']));
           }
           $crawled_content = echo_get_web_page($zurl);
           if($crawled_content === false)
           {
               $crawled_content = esc_html__('Error in feed searching. Please try again/other keyword.', 'rss-feed-post-generator-echo');
           }
           $crawled_contentx = json_decode($crawled_content, true);
           if($crawled_contentx === false)
           {
               $crawled_content = esc_html__('Error in feed decoding: ', 'rss-feed-post-generator-echo') . esc_html($crawled_content);
           }
           else
           {
               $crawled_content = $crawled_contentx;
           }
           if(isset($crawled_content['results']) && count($crawled_content['results']) > 0)
           {
               $crawled_content = $crawled_content['results'];
           }
           else
           {
               $crawled_content = esc_html__('No results found for your query!', 'rss-feed-post-generator-echo');
           }
       }
   ?>
<div class="wp-header-end"></div>
<div class="wrap gs_popuptype_holder seo_pops">
   <div>
      <div>
         <div>
         <h2><?php echo esc_html__("Search RSS Feeds by keyword, website URL or #topic:", 'rss-feed-post-generator-echo');?></h2><hr/>
            <form method="post" onsubmit="return confirm('Are you sure you want to search for feeds using provided query?');">
               <table class="cr_width_full" >
               <tr>
               <td>
               <h3>
                  <?php echo esc_html__("Find feeds based on title, URL or #topic (Required):", 'rss-feed-post-generator-echo');?>
                  <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                     <div class="bws_hidden_help_text cr_min_260px">
                        <?php
                           echo esc_html__("Input the the keyword, URL or #topic fo which you want to search feeds.", 'rss-feed-post-generator-echo');
                           ?>
                     </div>
                  </div>
               </h3>
               </td><td>
               <input name="echo_feed_keywords" type="text" placeholder="keyword, URL, #topic" class="cr_width_full" value="<?php
                     if(isset($_POST['echo_feed_keywords']))
                     {
                         echo esc_attr($_POST['echo_feed_keywords']);
                     }
                     ?>">
               </td></tr><tr><td>
               <h3>
                  <?php echo esc_html__("Feed Locale (en_US, pt_BR):", 'rss-feed-post-generator-echo');?>
                  <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                     <div class="bws_hidden_help_text cr_min_260px">
                        <?php
                           echo esc_html__("Set the locale for which to search feeds.", 'rss-feed-post-generator-echo');
                           ?>
                     </div>
                  </div>
               </h3>
               </td><td>
               <input name="echo_search_feeds_type" type="text" placeholder="en_US, en_GB, pt_BR, ro_RO" class="cr_width_full" value="<?php
                     if(isset($_POST['echo_search_feeds_type']))
                     {
                         echo esc_attr($_POST['echo_search_feeds_type']);
                     }
                     ?>">
               </td></tr><tr><td>
               <h3>
                  <?php echo esc_html__("Maximum Number of Feed Results:", 'rss-feed-post-generator-echo');?>
                  <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
                     <div class="bws_hidden_help_text cr_min_260px">
                        <?php
                           echo esc_html__("Set the maximum number of feed results to show. Default is 20.", 'rss-feed-post-generator-echo');
                           ?>
                     </div>
                  </div>
               </h3>
               </td><td>
               <input name="echo_search_feeds_count" type="number" min="1" step="1" placeholder="Maximum number of feeds to show" class="cr_width_full" value="<?php
                     if(isset($_POST['echo_search_feeds_count']))
                     {
                         echo esc_attr($_POST['echo_search_feeds_count']);
                     }
                     ?>">
               </td></tr></table>
               <br/><br/>
               <input name="echo_search_feeds" type="submit" title="Search feeds" value="Search Feeds" class="cr_width_full">
            </form>
         </div>
         <hr/>
         <?php
            if(isset($_POST['echo_feed_keywords']))
            {
            ?>
         <h3>
            <?php echo esc_html__("Found RSS Feeds:", 'rss-feed-post-generator-echo');?>
            <div class="bws_help_box bws_help_box_right dashicons dashicons-editor-help cr_align_middle">
               <div class="bws_hidden_help_text cr_min_260px">
                  <?php
                     echo esc_html__("Here you can see the found RSS feeds.", 'rss-feed-post-generator-echo');
                     ?>
               </div>
            </div>
         </h3>
         <br/>
         <div>
<?php
            if(is_array($crawled_content))
            {
                $prefix = 'feed/';
                echo '<div class="table-responsive"><table id="mainRules" class="responsive table cr_main_table cr_width_full">';
                echo '<tr><th>'.esc_html__("Feed URL", 'rss-feed-post-generator-echo').'</th><th>' . esc_html__("Website name", 'rss-feed-post-generator-echo').'</th><th>' . esc_html__("Actions", 'rss-feed-post-generator-echo').'</th></tr>';
                foreach($crawled_content as $cc)
                {
                    echo '<tr><td class="tabel">';
                    if (substr($cc['feedId'], 0, strlen($prefix)) == $prefix) {
                        $cc['feedId'] = substr($cc['feedId'], strlen($prefix));
                    } 
                    echo '<a href="' . esc_url_raw($cc['feedId']) . '" target="_blank">' . esc_url_raw($cc['feedId']) . '</a>';
                    echo '</td><td class="tabel"><b>';
                    if(isset($cc['website']))
                    {
                        echo '<a href="' . esc_url_raw($cc['website']) . '" target="_blank">';
                    }
                    if(isset($cc['title']))
                    {
                        echo esc_html($cc['title']);
                    }
                    elseif(isset($cc['websiteTitle']))
                    {
                        echo esc_html($cc['websiteTitle']);
                    }
                    else
                    {
                        $url = $cc['feedId'];
                        $parse = parse_url($url);
                        echo esc_html($parse['host']);
                    }
                    if(isset($cc['website']))
                    {
                        echo '</a>';
                    }
                    echo '</b></td><td class="tabel">';
                    echo '<button data-url="' . esc_url_raw($cc['feedId']) . '" class="echo_create_rule" target="_blank">' . esc_html__("Create A Rule Using RSS URL", 'rss-feed-post-generator-echo') . '</button>';
                    echo '</td></tr>';
                }
                echo '</table><input type="hidden" id="echo_rule_nonce" value="' . wp_create_nonce('echo_rule_nonce') . '"></div>';
            }
            else
            {
                echo esc_html($crawled_content);
            }
?>
         </div>
         <hr/>
         <?php
            }
            ?>
      </div>
   </div>
</div>
<?php
   }
   ?>