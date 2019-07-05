<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 24.06.2018
 * Update: 07.03.2019
 * Time: 17:55
 */

namespace Markocupic\ContaoNewsletterBundle;

use Contao\Database;
use Contao\MemberModel;
use Contao\Message;
use Contao\NewsletterChannelModel;
use Contao\StringUtil;
use Contao\Validator;

/**
 * Class NewsletterChannelHelper
 * @package Markocupic\ContaoNewsletterBundle
 */
class NewsletterChannelHelper
{
    /**
     * import newsletter recipients
     */
    public function importNewsletterRecipients()
    {

        $objNewsletterChannel = Database::getInstance()->execute('SELECT * FROM tl_newsletter_channel');
        while ($objNewsletterChannel->next())
        {
            $count = 0;
            // First delete all recipients of all newsletter channels if tl_newsletter_channel.enableGroupSupport is set to true
            // This will overwrite tl_member.newsletter
            if ($objNewsletterChannel->enableGroupSupport)
            {
                Database::getInstance()->prepare('DELETE FROM tl_newsletter_recipients WHERE pid=?')->execute($objNewsletterChannel->id);
            }

            // Find all active members
            $time = time();
            $objMember = Database::getInstance()->prepare('SELECT * FROM tl_member WHERE disable=? AND (start=? OR start<=?) AND (stop=? OR stop > ?) ')->execute('', '', $time, '', $time + 60);


            if ($objMember !== null)
            {
                while ($objMember->next())
                {

                    // Do not add member to tl_newsletter_recipients if member does not belong
                    // to a member group, that is allowed in tl_newsletter_channel
                    if ($objNewsletterChannel->enableGroupSupport)
                    {
                        // Remove this newsletter from tl_member
                        $this->removeNewsletterFromMember($objMember->id, $objNewsletterChannel->id);

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
                            $objUpdateStmt1 = Database::getInstance()->prepare('SELECT * FROM tl_newsletter_recipients WHERE pid=? AND email=?')->execute($objNewsletterChannel->id, $objMember->email);

                            if (!$objUpdateStmt1->numRows)
                            {
                                // Add member to newsletter channel
                                $this->addMemberToNewsletter($objMember->id, $objNewsletterChannel->id);
                                // Add newsletter to tl_member
                                $this->addNewsletterToMember($objMember->id, $objNewsletterChannel->id);
                                $count++;
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


    /**
     * @param $memberId
     * @param $newsletterChannelId
     */
    public function addMemberToNewsletter($memberId, $newsletterChannelId)
    {
        $objNewsletterChannel = NewsletterChannelModel::findByPk($newsletterChannelId);
        $objMember = MemberModel::findByPk($memberId);
        if ($objNewsletterChannel !== null && $objMember !== null)
        {
            $objRecipient = Database::getInstance()->prepare('SELECT * FROM tl_newsletter_recipients WHERE email=? AND pid=?')->execute($objMember->email, $objNewsletterChannel->id);
            if (!$objRecipient->numRows)
            {
                $set = array(
                    'pid'       => $objNewsletterChannel->id,
                    'tstamp'    => time(),
                    'email'     => $objMember->email,
                    'active'    => '1',
                    'addedOn'   => time(),
                    'confirmed' => '',
                    'ip'        => '',
                    'token'     => ''
                );
                $objUpdateStmt = Database::getInstance()->prepare('INSERT INTO tl_newsletter_recipients %s')->set($set)->execute();
                if ($objUpdateStmt->affectedRows)
                {
                    //Message::addInfo(sprintf('Die Empfängerliste für Newsletter "%s" wurde um den Empfänger mit E-Mail-Adress "%s" erweitert.', $objNewsletterChannel->title, $objMember->email), TL_MODE);
                }
            }
        }
    }


    /**
     * @param $memberId
     * @param $newsletterChannelId
     */
    public function removeNewsletterFromMember($memberId, $newsletterChannelId)
    {

        $objMember = MemberModel::findByPk($memberId);
        $objNewsletterChannel = NewsletterChannelModel::findByPk($newsletterChannelId);
        if ($objMember !== null && $objNewsletterChannel !== null)
        {
            $arrNewsletters = StringUtil::deserialize($objMember->newsletter, true);
            if (in_array($objNewsletterChannel->id, $arrNewsletters))
            {
                $arrNewslettersNew = array_diff($arrNewsletters, array($objNewsletterChannel->id));
                $set = array('newsletter' => serialize($arrNewslettersNew));
                Database::getInstance()->prepare('UPDATE tl_member %s WHERE id=?')->set($set)->execute($objMember->id);
            }
        }
    }


    /**
     * @param $memberId
     * @param $newsletterChannelId
     */
    public function addNewsletterToMember($memberId, $newsletterChannelId)
    {

        $objMember = MemberModel::findByPk($memberId);
        $objNewsletterChannel = NewsletterChannelModel::findByPk($newsletterChannelId);
        if ($objMember !== null && $objNewsletterChannel !== null)
        {
            $arrNewsletters = StringUtil::deserialize($objMember->newsletter, true);
            if (!in_array($objNewsletterChannel->id, $arrNewsletters))
            {
                $arrNewsletters[] = $objNewsletterChannel->id;
                $set = array('newsletter' => serialize($arrNewsletters));
                Database::getInstance()->prepare('UPDATE tl_member %s WHERE id=?')->set($set)->execute($objMember->id);
            }
        }
    }
}