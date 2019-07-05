<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 11.03.2018
 * Time: 10:32
 */


/**
 * Extend the default palette
 */
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('jade_settings', 'personal_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField(array('statusDerWeiterbildung', 'kurzportrait', 'aufmerksamGewordenDurch'), 'jade_settings', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('bundesland'), 'address_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_member');

if(TL_MODE === 'FE')
{
    $GLOBALS['TL_DCA']['tl_member']['fields']['groups']['eval']['mandatory'] = true;
}

$GLOBALS['TL_DCA']['tl_member']['fields']['statusDerWeiterbildung'] = array(

    'label'     => &$GLOBALS['TL_LANG']['tl_member']['statusDerWeiterbildung'],
    'reference' => &$GLOBALS['TL_LANG']['tl_member'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('weiterbildung_level_1', 'weiterbildung_level_2', 'weiterbildung_level_3', 'weiterbildung_level_4', 'weiterbildung_level_5', 'weiterbildung_level_6', 'weiterbildung_level_7', 'weiterbildung_level_8', 'weiterbildung_level_9', 'weiterbildung_level_10', 'weiterbildung_level_11', 'weiterbildung_level_12'),
    'eval'      => array('mandatory' => true, 'includeBlankOption' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'clr'),
    'sql'       => "varchar(32) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['kurzportrait'] = array(

    'label'     => &$GLOBALS['TL_LANG']['tl_member']['kurzportrait'],
    'exclude'   => true,
    'inputType' => 'textarea',
    'eval'      => array('mandatory' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'maxlength' => 1000, 'tl_class' => 'clr'),
    'sql'       => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['aufmerksamGewordenDurch'] = array(

    'label'     => &$GLOBALS['TL_LANG']['tl_member']['aufmerksamGewordenDurch'],
    'reference' => &$GLOBALS['TL_LANG']['tl_member'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('studium', 'weiterbildung', 'internet', 'privates_umfeld', 'arbeitsplatz', 'andere'),
    'eval'      => array('mandatory' => true, 'chosen' => true, 'multiple' => true, 'includeBlankOption' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'clr'),
    'sql'       => "blob NULL",
);

$GLOBALS['TL_DCA']['tl_member']['fields']['bundesland'] = array(

    'label'     => &$GLOBALS['TL_LANG']['tl_member']['bundesland'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => Contao\Config::get('bundeslaender'),
    'eval'      => array('mandatory' => false, 'includeBlankOption' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'address', 'tl_class' => 'clr'),
    'sql'       => "varchar(32) NOT NULL default ''",
);


