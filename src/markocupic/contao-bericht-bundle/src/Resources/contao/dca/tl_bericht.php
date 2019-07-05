<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */


/**
 * Table tl_bericht
 */
$GLOBALS['TL_DCA']['tl_bericht'] = array
(

    // Config
    'config'      => array
    (
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
        'onsubmit_callback' => array
        (
            array('tl_bericht', 'storeDateAdded'),
            //array('tl_bericht', 'checkRemoveSession')
        ),
        'ondelete_callback' => array
        (//array('tl_bericht', 'removeSession')
        ),
        'sql'               => array
        (
            'keys' => array
            (
                'id' => 'primary',
                //'username' => 'unique',
                //'email' => 'index',
                //'autologin' => 'unique',
                //'activation' => 'index'
            ),
        ),
    ),

    // List
    'list'        => array
    (
        'sorting'           => array
        (
            'mode'        => 2,
            'fields'      => array('pruefungsdatum DESC'),
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ),
        'label'             => array
        (
            'fields'         => array('pruefungsdatum', 'titel', 'autor'),
            'showColumns'    => true,
            'label_callback' => array('tl_bericht', 'labelCallback'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ),
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bericht']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.svg',
            ),
            'copy'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bericht']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.svg',
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_bericht']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_bericht']['toggle'],
                'icon'            => 'visible.svg',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_bericht', 'toggleIcon'),
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bericht']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.svg',
            ),
        ),
    ),

    // Palettes
    'palettes'    => array
    (
        //'__selector__'                => array('login', 'assignDir'),
        'default' => '{bericht_legend},titel,pruefungsdatum,pruefungsbezirk,autor,bericht',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        //'login'                       => 'username,password',
        //'assignDir'                   => 'homeDir'
    ),


    // Fields
    'fields'      => array
    (
        'id'               => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp'           => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'titel'            => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bericht']['titel'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'pruefungsdatum'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bericht']['pruefungsdatum'],
            'exclude'   => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'pruefungsbezirk'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bericht']['pruefungsbezirk'],
            'search'    => true,
            'sorting'   => true,
            'filter'    => true,
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => $GLOBALS['TL_CONFIG']['bericht_bundeslaender'],
            'reference' => &$GLOBALS['TL_LANG']['BEZIRKE'],
            'eval'      => array('multiple' => false, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'),
            'sql'       => "varchar(32) NOT NULL default ''",
        ),
        'bericht'          => array
        (
            'label'       => &$GLOBALS['TL_LANG']['tl_bericht']['bericht'],
            'exclude'     => true,
            'search'      => true,
            'inputType'   => 'textarea',
            'eval'        => array('allowHtml' => true, 'mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true, 'tl_class' => 'clr long'),
            'explanation' => 'insertTags',
            'sql'         => "text NULL",
        ),
        'autor'            => array
        (
            'label'      => &$GLOBALS['TL_LANG']['tl_bericht']['autor'],
            'exclude'    => true,
            'search'     => true,
            'sorting'    => true,
            'filter'     => true,
            'inputType'  => 'select',
            'foreignKey' => "tl_member.CONCAT(firstname, ' ', lastname)",
            'eval'       => array('mandatory' => true, 'tl_class' => 'w50'),
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => array('type' => 'hasOne', 'load' => 'lazy'),
        ),
        'erstellungsdatum' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bericht']['erstellungsdatum'],
            'exclude'   => true,
            'sorting'   => true,
            'inputType' => 'text',
            'default'   => time(),
            'eval'      => array('mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'       => "varchar(11) NOT NULL default ''",
        ),
        'published'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bericht']['published'],
            'exclude'   => true,
            'sorting'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''",
        ),

    ),
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_bericht extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Label callback
     * @param array $row
     * @param string $label
     * @param DataContainer $dc
     * @param array $args
     *
     * @return array
     */
    public function labelCallback($row, $label, DataContainer $dc, $args)
    {
        $pruefungsdatum = '-';
        if ($row['pruefungsdatum'] > 0)
        {
            $pruefungsdatum = Contao\Date::parse('Y-m-d', $row['pruefungsdatum']);
        }
        $args[0] = $pruefungsdatum;

        // Titel
        $args[1] = \Contao\StringUtil::substr($args[1], 20, ' ...');

        $autorname = '-';
        if ($row['autor'] > 0)
        {
            $objMember = Contao\MemberModel::findByPk($row['autor']);
            if ($objMember !== null)
            {
                $autorname = $objMember->firstname . ' ' . $objMember->lastname;
            }
        }
        $args[2] = $autorname;

        return $args;
    }


    /**
     * Store the date when the account has been added
     *
     * @param DataContainer $dc
     */
    public function storeDateAdded($dc)
    {
        // Front end call
        if (!$dc instanceof DataContainer)
        {
            return;
        }

        // Return if there is no active record (override all)
        if (!$dc->activeRecord || $dc->activeRecord->erstellungsdatum > 0)
        {
            return;
        }

        $time = time();
        $this->Database->prepare("UPDATE tl_bericht SET erstellungsdatum=? WHERE id=?")
            ->execute($time, $dc->id);
    }


    /**
     * Return the "toggle visibility" button
     *
     * @param array $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_vericht::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.svg';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }


    /**
     * Disable/enable a user group
     *
     * @param integer $intId
     * @param boolean $blnVisible
     * @param DataContainer $dc
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_faq']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_faq']['config']['onload_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_faq::published', 'alexf'))
        {
            throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish FAQ ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc)
        {
            $objRow = $this->Database->prepare("SELECT * FROM tl_faq WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows)
            {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_faq', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_bericht']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_bericht']['fields']['published']['save_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (\is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_bericht SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_bericht']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_bericht']['config']['onsubmit_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }
}
