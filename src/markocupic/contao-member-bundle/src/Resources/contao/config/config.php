<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:22
 */


// Frontend Modules
if(!is_array($GLOBALS['FE_MOD']['jade']))
{
    $GLOBALS['FE_MOD']['jade'] = array();
}
$GLOBALS['FE_MOD']['jade']['jadePersonalData'] = 'Markocupic\ContaoMemberBundle\ModulePersonalData';


// Hooks
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('Markocupic\ContaoMemberBundle\Hooks', 'createNewUser');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('Markocupic\ContaoMemberBundle\Hooks', 'replaceInsertTags');


// Avatar
$GLOBALS['TL_CONFIG']['avatarUploadPath'] = 'files/userimages/user_%s';
// Save the default avatar of your choice in files/avatar
$GLOBALS['TL_CONFIG']['defaultAvatarSRC'] = 'files/userimages/userdefault/avatar.jpg';


// Bundesländer
$GLOBALS['TL_CONFIG']['bundeslaender'] = array(
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

