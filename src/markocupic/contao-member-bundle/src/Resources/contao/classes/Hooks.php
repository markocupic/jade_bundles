<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:17
 */

namespace Markocupic\ContaoMemberBundle;


use Contao\MemberModel;

/**
 * Class Hooks
 * @package Markocupic\ContaoMemberBundle
 */
class Hooks
{
    /**
     * @param $intId
     * @param $arrData
     */
    public function createNewUser($intId, $arrData)
    {
        $objMember = MemberModel::findByPk($intId);
        if ($objMember !== null)
        {
            $objMember->username = $objMember->email;
            $objMember->save();
        }
    }


    /**
     * @param $strTag
     * @return bool|string
     */
    public function replaceInsertTags($strTag)
    {

        // Return avatar file uuid


        return false;
    }

}