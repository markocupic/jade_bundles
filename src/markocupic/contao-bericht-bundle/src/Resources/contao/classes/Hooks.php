<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:17
 */

namespace Markocupic\ContaoBerichtBundle;

use Contao\Date;
use NotificationCenter\Model\Notification;

class Hooks
{
    /**
     * @param $objModel
     * @param $objMember
     * @param $arrFormData
     * @param $objModule
     */
    public function insertJadeBericht($objModel, $objMember, $arrFormData, $objModule)
    {
        if ($objModule->notification)
        {
            $objNotification = Notification::findByPk($objModule->notification);
            if ($objNotification !== null)
            {
                $arrTokens = array(
                    'titel'           => html_entity_decode($objModel->titel),
                    'bericht'         => html_entity_decode($objModel->bericht),
                    'pruefungsdatum'  => Date::parse('d.m.Y', $objModel->pruefungdatum),
                    'pruefungsbezirk' => html_entity_decode($objModel->pruefungsbezirk),
                    'firstname'       => html_entity_decode($objMember->firstname),
                    'lastname'        => html_entity_decode($objMember->lastname),
                    'email'           => html_entity_decode($objMember->email),
                );

                $objNotification->send($arrTokens, 'de');

                return true;
            }
        }
    }
}