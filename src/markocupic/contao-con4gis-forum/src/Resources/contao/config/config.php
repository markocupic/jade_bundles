<?php
/**
 * PHP version 5
 *
 * LICENSE: Commercial, created for Leif Braun, kreadea.de
 *
 * @category   Contao Bundle
 * @package    Markocupic Contao Con4gis Forum Bundle
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @copyright  2019 Marko Cupic
 * @link       https://github.com/markocupic
 */

if (TL_MODE === 'BE')
{
    // Notification Center
    $arrAllowed = array(
        'sub_new_thread',
        'sub_deleted_thread',
        'sub_moved_thread',
        'sub_new_post',
        'sub_deleted_post',
        'sub_edited_post',
        'mail_new_pm',
    );

    foreach ($arrAllowed as $k)
    {
        // Logged in user data
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'user_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'user_firstname';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'user_lastname';

        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'user_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'user_firstname';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'user_lastname';

        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'user_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'user_firstname';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'user_lastname';

        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_sender_name'][] = 'user_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_sender_name'][] = 'user_firstname';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_sender_name'][] = 'user_lastname';

        // post data
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'post_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'post_subject';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_subject'][] = 'post_text';

        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'post_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'post_subject';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_text'][] = 'post_text';

        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'post_*';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'post_subject';
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['con4gis Forum'][$k]['email_html'][] = 'post_text';
    }
}

// Hooks
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('Markocupic\ContaoCon4gisForumBundle\Contao\Hooks\SendNotificationMessage', 'sendNotificationMessage');

