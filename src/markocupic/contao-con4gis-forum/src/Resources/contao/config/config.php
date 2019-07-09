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
    require_once TL_ROOT . '/src/markocupic/contao-con4gis-forum/src/Resources/contao/config/nc.php';
}

// Hooks
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('Markocupic\ContaoCon4gisForumBundle\Contao\Hooks\SendNotificationMessage', 'sendNotificationMessage');

