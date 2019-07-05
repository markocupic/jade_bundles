<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:22
 */


// Frontend Modules
if (!is_array($GLOBALS['FE_MOD']['jade']))
{
    $GLOBALS['FE_MOD']['jade'] = array();
}
$GLOBALS['FE_MOD']['jade']['jadeFormBericht'] = 'Markocupic\ContaoBerichtBundle\ModuleFormBericht';
$GLOBALS['FE_MOD']['jade']['jadeBerichtListe'] = 'Markocupic\ContaoBerichtBundle\ModuleBerichtListe';
$GLOBALS['FE_MOD']['jade']['jadeBerichtReader'] = 'Markocupic\ContaoBerichtBundle\ModuleBerichtReader';



// Hooks
$GLOBALS['TL_HOOKS']['insertJadeBericht'][] = array('Markocupic\ContaoBerichtBundle\Hooks', 'insertJadeBericht');

// BE Module
$GLOBALS['BE_MOD']['jade']['bericht'] = array(
    'tables'      => array('tl_bericht'),
);



// Bundesländer
$GLOBALS['TL_CONFIG']['bericht_bundeslaender'] = array(
    'baden-wuertemberg',
    'bayern',
    'berlin',
    'brandenburg',
    'bremen',
    'hamburg',
    'hessen',
    'mecklenburg-vorpommern',
    'niedersachsen',
    'nordrhein-westfalen',
    'rheinland-pfalz',
    'saarland',
    'sachsen',
    'sachsen-anhalt',
    'schleswig-holstein',
    'sachsen-anhalt',
    'thueringen',
);

// notification_center_config.php
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['jade_bericht'] = array
(
    // Type
    'neuer_bericht' => array
    (
        // Field in tl_nc_language
        //'email_sender_name' => array(),
        //'email_sender_address' => array(),
        //'recipients'    => array('participant_email'),
        //'email_replyTo' => array(),
        'email_recipient_cc' => array('email'),
        'email_subject' => array('titel'),
        'email_text'    => array('pruefungsdatum','pruefungsbezirk','titel', 'bericht', 'firstname', 'lastname', 'email'),
        'email_html'    => array('pruefungsdatum','pruefungsbezirk','titel', 'bericht', 'firstname', 'lastname', 'email'),
    ),
);

