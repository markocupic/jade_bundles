<?php
/**
 * PHP version 5
 *
 * LICENSE: For commercial usage only,
 * Bundle created for Leif Braun, kreadea.de
 *
 *
 * @category   Contao Bundle
 * @package    Markocupic Contao Con4gis Forum Bundle
 * @author     Marko Cupic <m.cupic@gmx.ch>
 * @copyright  2019 Marko Cupic
 * @link       https://github.com/markocupic
 */

namespace Markocupic\ContaoCon4gisForumBundle\Contao\Hooks;

use Contao\FrontendUser;
use Contao\MemberModel;
use Contao\Database;

/**
 * Class SendNotificationMessage
 * @package Markocupic\Con4gisExt\Hooks
 */
class SendNotificationMessage
{
    /**
     * Hook for composer require terminal42/notification_center
     * sendNotificationMessage hook registered in src\Resources\contao\config\config.php
     *
     * Enrich token array with some more information about
     * the logged in user/author and
     * post information
     * @see tags in src\Resources\contao\config\config.php
     *
     * @param $objMessage
     * @param $cpTokens
     * @param $cpLanguage
     * @param $objGatewayModel
     * @return bool
     */
    public function sendNotificationMessage($objMessage, &$cpTokens, &$cpLanguage, $objGatewayModel)
    {

        if (TL_MODE == 'FE' && FE_USER_LOGGED_IN)
        {

            $arrAllowed = array(
                'sub_new_thread',
                'sub_deleted_thread',
                'sub_moved_thread',
                'sub_new_post',
                'sub_deleted_post',
                'sub_edited_post',
                'mail_new_pm',
            );

            if (in_array($objMessage->getRelated('pid')->type, $arrAllowed))
            {
                // Preset tokens
                $cpTokens['post_text'] = ' ';
                $cpTokens['user_firstname'] = ' ';
                $cpTokens['user_lastname'] = ' ';
                $cpTokens['user_*'] = ' ';
                $cpTokens['post_*'] = ' ';



                // Get logged in user/author of post.
                $objUser = FrontendUser::getInstance();
                $objMember = MemberModel::findByPk($objUser->id);
                if ($objMember !== null)
                {
                    // Enrich $cpTokens
                    $arrMember = $objMember->row();
                    foreach ($arrMember as $k => $v)
                    {
                        if ($k === 'password')
                        {
                            continue;
                        }
                        $cpTokens['author_' . $k] = $v;
                    }
                }

                // Get data from latest post
                if ($objMember !== null && $objMessage->getRelated('pid')->type === 'sub_new_post')
                {
                    if ($cpTokens['link'] !== '')
                    {
                        // Get thread id from $cpTokens['link'] --> http://www.mydomain.de/forum.html?state=forum:26;readthread:159
                        if (preg_match('/readthread:(\d+)/', $cpTokens['link'], $matches))
                        {
                            if ($matches[1] > 0)
                            {
                                $objPost = Database::getInstance()->prepare('SELECT * FROM tl_c4g_forum_post WHERE author=? AND pid=? ORDER BY tstamp DESC')
                                    ->limit(1)
                                    ->execute($objMember->id, $matches[1]);
                                // Enrich $cpTokens
                                if ($objPost->numRows)
                                {
                                    foreach ($objPost->fetchAssoc() as $k => $v)
                                    {
                                        if ($k === 'text')
                                        {
                                            $cpTokens['post_' . $k] = strip_tags(html_entity_decode($v));
                                        }
                                        else
                                        {
                                            $cpTokens['post_' . $k] = $v;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Return true, though the message will be sent
        return true;
    }
}
