<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 11.03.2018
 * Time: 09:57
 */

namespace Markocupic\ContaoMemberBundle;


use Contao\Config;
use Contao\Dbafs;
use Contao\FilesModel;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;

/**
 * Class Avatar
 * @package Markocupic\ContaoMemberBundle
 */
class Avatar
{


    /**
     * @param $userId
     * @return string
     * @throws \Exception
     */
    public static function getAvatarUuid($userId)
    {

        $objUser = MemberModel::findByPk($userId);
        if ($objUser !== null)
        {
            // Get root dir
            $rootDir = System::getContainer()->getParameter('kernel.project_dir');

            $arrAvatar = deserialize($objUser->memberImage);
            if(!empty($arrAvatar) && is_array($arrAvatar))
            {
                if(is_file($rootDir . '/' . $arrAvatar[0]))
                {
                    Dbafs::addResource($arrAvatar[0]);
                    return StringUtil::binToUuid(FilesModel::findByPath($arrAvatar[0])->uuid);
                }
            }
        }
        return '';
    }

}