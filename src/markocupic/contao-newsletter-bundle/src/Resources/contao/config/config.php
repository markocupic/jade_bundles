<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:22
 */


$GLOBALS['TL_HOOKS']['activateRecipient'][] = array('Markocupic\ContaoNewsletterBundle\NewsletterChannelHelper', 'importNewsletterRecipients');
$GLOBALS['TL_HOOKS']['removeRecipient'][] = array('Markocupic\ContaoNewsletterBundle\NewsletterChannelHelper', 'importNewsletterRecipients');

