<?php
/**
 * @copyright  Marko Cupic 2017 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    Member Bundle
 * @license    LGPL-3.0+
 * @see        https://github.com/markocupic/calendar-event-booking-bundle
 * @see        https://github.com/markocupic/employee-bundle
 *
 */

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
//use con4gis\ForumBundle\con4gisForumBundle;


/**
 * Plugin for the Contao Manager.
 *
 * @author Marko Cupic
 */
class ContaoManagerPlugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            // 1. lokales Plugin in ROOT  src/markocupic/contao-member-bundle
            BundleConfig::create('Markocupic\ContaoMemberBundle\MarkocupicContaoMemberBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                ]),
            // 2. lokales Plugin in ROOT  src/markocupic/contao-member-bundle
            BundleConfig::create('Markocupic\ContaoBerichtBundle\MarkocupicContaoBerichtBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                ]),
            // 3. lokales Plugin in ROOT  src/markocupic/contao-newsletter-bundle
            BundleConfig::create('Markocupic\ContaoNewsletterBundle\MarkocupicContaoNewsletterBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                ]),

            // 4. lokales Plugin in ROOT  src/markocupic/contao-con4gis-forum-bundle
            BundleConfig::create('Markocupic\ContaoCon4gisForumBundle\MarkocupicContaoCon4gisForumBundle')
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    con4gisForumBundle::class
                ]),

        ];
    }
}
