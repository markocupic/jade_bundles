<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 24.06.2018
 * Time: 17:55
 */

namespace Markocupic\ContaoNewsletterBundle;

use Contao\Database;
use Contao\MemberModel;
use Contao\Message;
use Contao\StringUtil;
use Contao\Validator;

/**
 * Class NewsletterChannelHelper
 * @package Markocupic\ContaoNewsletterBundle
 */
class OldNewsletterChannelHelper
{
    /**
     * import newsletter recipients
     */
    public function importNewsletterRecipients()
    {
        $arrRecipients = array();
        $objNewsletterChannel = Database::getInstance()->execute('SELECT * FROM tl_newsletter_channel');
        while ($objNewsletterChannel->next())
        {
            $count = 0;

            // First deactivate all recipients
            Database::getInstance()->prepare('UPDATE tl_newsletter_recipients SET active=? WHERE pid=?')->execute('', $objNewsletterChannel->id);

            // Find all active members
            $t = 'tl_member';
            $time = time();
            $arrOptions = array();
            $objMember = MemberModel::findBy(array("$t.disable='' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "')"), null, $arrOptions);
            Database::getInstance()->prepare('UPDATE tl_newsletter_recipients SET active=? WHERE pid=?')->execute('', $objNewsletterChannel->id);

            if ($objMember !== null)
            {
                while ($objMember->next())
                {
                    $arrNewsletterAbo = StringUtil::deserialize($objMember->newsletter, true);

                    if (in_array($objNewsletterChannel->id, $arrNewsletterAbo))
                    {
                        // Do not add member to tl_newsletter_recipients if member does not belong
                        // to a member group, that is allowed in tl_newsletter_channel
                        if ($objNewsletterChannel->enableGroupSupport)
                        {

                            $arrGroupFilter = StringUtil::deserialize($objNewsletterChannel->groups, true);
                            $arrMemberGroups = StringUtil::deserialize($objMember->groups, true);

                            if (count(array_intersect($arrGroupFilter, $arrMemberGroups)) < 1)
                            {
                                continue;
                            }
                        }
                        if ($objMember->email != '')
                        {
                            if (Validator::isEmail($objMember->email))
                            {
                                $objUpdateStmt = Database::getInstance()->prepare('UPDATE tl_newsletter_recipients SET active=? WHERE pid=? AND email=?')->execute('1', $objNewsletterChannel->id, $objMember->email);
                                if ($objUpdateStmt->affectedRows)
                                {
                                    $count++;
                                }
                            }
                        }
                    }
                }

                if (TL_MODE == 'BE')
                {
                    // Show message in the backend
                    Message::addInfo(sprintf('Die Empfängerliste für Newsletter "%s" wurde neu erstellt und dem Verteiler wurden %s Empfänger zugeordnet.', $objNewsletterChannel->title, $count), TL_MODE);
                }
            }
        }
    }
}