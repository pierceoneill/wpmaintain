<?php
/*
 * This document is intentionally not translatable, as it is intended to be for US citizens, and should therefore always be in English
 *
 * This document is based on the privacy statement for the CA
 *
 * */
defined('ABSPATH') or die("you do not have access to this page!");

$this->pages['au']['privacy-statement-children']['document_elements'] = array(
    'last-updated' => array(
        'content' => '<i>' . cmplz_sprintf('This Privacy Statement was last changed on %s, was last checked on %s and applies to citizens of Australia.', '[publish_date]', '[checked_date]') . '</i><br><br>',
    ),

    'inleiding' => array(
        'content' =>
            '<p>'. cmplz_sprintf('In this Privacy Statement, we explain what we do with the data we obtain about children via %s. We recommend you carefully read this statement. In our processing we comply with the requirements of Australian privacy legislation. That means, among other things, that:', '[domain]') .'</p>'.
            '<ul>
                <li>'.'we clearly state the purposes for which we process personal data. We do this by means of this Privacy Statement;'.'</li>
                <li>'.'we aim to limit our collection of personal data to only the personal data required for legitimate purposes;'.'</li>
                <li>'.'we first request consent from parents to process the personal data in cases requiring parental consent;'.'</li>
                <li>'.'we take appropriate security measures to protect the personal data of children and also require this from parties that process personal data on our behalf;'.'</li>
                <li>'.'we respect the right to access children’s personal data or have it corrected or deleted, at the request of a parent or guardian.'.'</li>
            </ul>' .
            '<p>'.'If you have any questions, or want to know exactly what data we keep of you or your child, please contact us.'.'</p>',
    ),



    array(
        'title' => 'Purposes',
        'content' => 'We use the personal data from children for one or more of the following purposes:'.'[children-what-purposes]',
    ),
    array(
        'title' => 'Registration',
        'content' => 'Sometimes children need to register on our website in order to play games or to view content. For this purpose we use the following data: '.'[children-what-information-registration]',
        'condition' => array('children-what-purposes' => 'registration')
    ),
    array(
        'title' => 'Content created by a child and publicly shared',
        'content' => 'Sometimes children are creating content on our website, and sometimes personal information is inserted by the child in the created content. Where possible we try to delete that personal information or ask verifiable consent for the parents or guardians.'.'<br><br>'.
                     'We will also ask for consent when we plan to post content publicly. For this purpose we might use the following data: '.'[children-what-information-content]',
        'condition' => array('children-what-purposes' => 'content-created-by-child')
    ),
    array(
        'title' => 'Chat/messageboard',
        'content' => 'There are games or activities that allow children to communicate with each other through a chatsystem or a messageboard. To protect children we employ filters , and recommend that parents supervise their children.'.'<br><br>'.
                     'For this purpose we use the following data: '.'[children-what-information-chat]',
        'condition' => array('children-what-purposes' => 'chat')
    ),

    array(
        'title' => 'Email contact',
        'content' => 'Sometimes it is necessary that we ask for an email address. We will do this in order to respond to a request or question from a child.'.'[children-what-information-email]',
        'condition' => array('children-what-purposes' => 'email')
    ),

    array(
        'title' => 'Verifiable Parental Consent',
        'content' => 'We search consent from a parent or guardian if we wish to collect personal data from a child. We use the following method(s):'.'[children-parent-consent-type]',
    ),

    array(
        'content' => 'Parents and guardians can refuse their consent, and can request that we delete any personal information we might have already collected. This might also mean that an account or membership will be terminated.',
    ),

    array(
        'title' => 'When verifiable parental consent is not required',
        'p' => false,
        'content' => '<p>'.'Verifiable parental consent is not required in the case of:'.'</p>'.
                     '<ol class="alphabetic">
                        <li>'.'online contact information collected from a child that is used only to respond directly on a one-time basis to a specific request from the child and is not used to recontact the child and is not maintained in retrievable form by the operator;'.'</li>
                        <li>'.'a request for the name or online contact information of a parent or child that is used for the sole purpose of obtaining parental consent or providing notice and where such information is not maintained in retrievable form by the operator if parental consent is not obtained after a reasonable time;'.'</li>
                        <li>'.'online contact information collected from a child that is used only to respond more than once directly to a specific request from the child and is not used to recontact the child beyond the scope of that request'.'
                            <ol>
                                <li>'.'if, before any additional response after the initial response to the child, the operator uses reasonable efforts to provide a parent notice of the online contact information collected from the child, the purposes for which it is to be used, and an opportunity for the parent to request that the operator make no further use of the information and that it not be maintained in retrievable form;'.'</li>
                            </ol>
                        </li>
                        <li>'.'the name of the child and online contact information (to the extent reasonably necessary to protect the safety of a child participant on the site)'.'
                            <ol>
                                <li>'.'used only for the purpose of protecting such safety;'.'</li>
                                <li>'.'not used to recontact the child or for any other purpose; and'.'</li>
                                <li>'.'not disclosed on the site,'.'</li>
                             </ol>
                             '.'if the operator uses reasonable efforts to provide a parent notice of the name and online contact information collected from the child, the purposes for which it is to be used, and an opportunity for the parent to request that the operator make no further use of the information and that it not be maintained in retrievable form; or'.'
                        </li>
                        <li>'.'the collection, use, or dissemination of such information by the operator of such a website or online service necessary'.'
                            <ol>
                                <li>'.'to protect the security or integrity of its website;'.'</li>
                                <li>'.'to take precautions against liability;'.'</li>
                                <li>'.'to respond to judicial process; or'.'</li>
                                <li>'.'to the extent permitted under other provisions of law, to provide information to law enforcement agencies or for an investigation on a matter related to public safety'.'</li>
                             </ol>
                        </li>
                    </ol>'
    ),

    //In the privacy-policy page the first paragraph containing purpose and data retention period is generated in the dynamic documents file
    array(
        'title' => 'Sharing with other parties',
        'content' => 'We only share this data with Service Providers and with the following categories of third-party persons or entities:',
        'condition' => array('share_data_other' => '1'),
    ),

    array(
        'title' => 'Sharing with other parties',
        'content' => 'We only share or disclose this data to other recipients for the following purposes:',
        'condition' => array('share_data_other' => '3'),
    ),

    array(
        'title' => 'Sharing with other parties',
        'content' => 'We do not share data with third parties.',
        'condition' => array('share_data_other' => '2'),
    ),

    array(
        'numbering' => false,
        'content' =>
            '<b>'.'Purpose of the data transfer:'.'</b>&nbsp;[purpose]<br>
             <b>'.'Country or state in which this processor is located:'.'</b>&nbsp;[country]<br>',
        'condition' => array(
            'processor' => 'loop',
            'share_data_other' => 'NOT 2',
        ),
    ),

    array(
        'numbering' => false,
        'content' =>
            "<b>Purpose of the data transfer:</b>&nbsp;[purpose]<br>
                <b>Country or state in which this third party is located:</b>&nbsp;[country]<br>",
        'condition' => array(
            'thirdparty' => 'loop',
            'share_data_other' => '1',
        ),
    ),

	array(
		'title' => 'Disclosure practices',
		'content' =>
			'<p>' . 'We disclose personal information if we are required by law or by a court order, in response to a law enforcement agency, or if we believe disclosure may facilitate an investigation related to protect the safety of a child.' . "</p>" .
			'<p>' . 'If our website or organisation is taken over, sold, or involved in a merger or acquisition, your details may be disclosed to our advisers and any prospective purchasers and will be passed on to the new owners.' . "</p>",
	),

    array(
        'title' => 'How we respond to Do Not Track signals & Global Privacy Control ',
        'content' => 'Our website responds to and supports the Do Not Track (DNT) header request field. If you turn DNT on in your browser, those preferences are communicated to us in the HTTP request header, and we will not track your browsing behavior.',
        'condition' => array('respect_dnt' => 'yes'),
    ),

    array(
        'title' => 'How we respond to Do Not Track signals & Global Privacy Control',
        'content' => 'Our website does not respond to and does not support the Do Not Track (DNT) header request field.',
        'condition' => array('respect_dnt' => 'no'),
    ),

    array(
        'title' => 'Cookies',
        'content' => cmplz_sprintf('Our website uses cookies. For more information about cookies, please refer to our %sCookie Policy%s.', '<a href="[cookie-statement-url]">', '</a>')."&nbsp;",
    ),

    array(
        'title' => 'Security',
        'content' => 'We are committed to the security of personal data. We take appropriate security measures to limit abuse of and unauthorized access to personal data. This ensures that only the necessary persons have access to your data, that access to the data is protected, and that our security measures are regularly reviewed.'
    ),

    array(
        'content' => 'The security measures we use consist of:',
        'condition' => array('secure_personal_data' => 'NOT 1'),
    ),

    array(
        'content' => '[which_personal_data_secure]',
        'condition' => array('secure_personal_data' => 'NOT 1'),
    ),

    array(
        'title' => 'Contact details',
        'content' => 'Please contact us at the address below if you have any questions about this Children’s Privacy Statement or about our collection and use practices:'.
        '<br>
        [organisation_name]<br>
        [address_company]<br>
        [country_company]<br>
        '._x('Website:', 'Legal document privacy statement', 'complianz-gdpr').' [domain] <br />
        '._x('Email:', 'Legal document privacy statement', 'complianz-gdpr').' [email_company] <br />
        [free_phonenr] <br>
        [telephone_company]',
    ),



// End privacy statement array
);
