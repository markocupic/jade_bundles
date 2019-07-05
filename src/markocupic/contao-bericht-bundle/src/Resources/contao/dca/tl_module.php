<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 10.03.2018
 * Time: 22:53
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['jadeFormBericht'] = '{title_legend},name,headline,type;{notification},notification;{jumpTo_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['jadeBerichtListe'] = '{title_legend},name,headline,type;{jumpTo_legend},jumpTo,perPage;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['jadeBerichtReader'] = '{title_legend},name,headline,type;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';


$GLOBALS['TL_DCA']['tl_module']['fields']['notification'] = array(
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['notification'],
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_nc_notification.title',
    'eval'       => array('mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'),
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => array('type' => 'hasOne', 'load' => 'lazy'),
);