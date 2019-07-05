<?php


use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Callbacks
$GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['onload_callback'][] = array('Markocupic\ContaoNewsletterBundle\NewsletterChannelHelper', 'importNewsletterRecipients');
$GLOBALS['TL_DCA']['tl_newsletter_channel']['config']['onsubmit_callback'][] = array('Markocupic\ContaoNewsletterBundle\NewsletterChannelHelper', 'importNewsletterRecipients');


$GLOBALS['TL_DCA']['tl_newsletter_channel']['palettes']['__selector__'][] = 'enableGroupSupport';
$GLOBALS['TL_DCA']['tl_newsletter_channel']['subpalettes']['enableGroupSupport'] = 'groups';


PaletteManipulator::create()
    ->addLegend('groups_legend', 'title_legend', PaletteManipulator::POSITION_AFTER, false)
    ->addField(array('enableGroupSupport'), 'groups_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_newsletter_channel');

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['enableGroupSupport'] = array(

    'label'     => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['enableGroupSupport'],
    'exclude'   => true,
    'filter'    => true,
    'inputType' => 'checkbox',
    'eval'      => array('submitOnChange' => true),
    'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['groups'] = array(
    'label'      => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['groups'],
    'exclude'    => true,
    'filter'     => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_member_group.name',
    'eval'       => array('mandatory' => true, 'multiple' => true),
    'sql'        => "blob NULL",
    'relation'   => array('type' => 'hasMany', 'load' => 'lazy'),
);


