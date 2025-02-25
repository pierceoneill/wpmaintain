"use strict";
jQuery(document).ready(function() 
{
    jQuery('body').on('click', '.echo_create_rule', function() 
    {
        var feedurl = jQuery(this).attr('data-url');
        var nonce = jQuery('#echo_rule_nonce').val();
        var ajaxurl = mycustomsettings.ajaxurl;
        var data = {
            action: 'echo_create_rule',
            feedurl: feedurl,
            nonce: nonce
        };
        jQuery.post(ajaxurl, data, function(response) 
        {
            if(response.trim() == 'ok')
            {
                alert('New rule created!');
            }
            else
            {
                alert('Error while creating the rule: ' + response);
            }
        }).fail( function(xhr) 
        {
            alert('Exception while creating the rule: ' + xhr.statusText);
        });
    });
});