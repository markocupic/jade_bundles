<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Markocupic\ContaoBerichtBundle;

use Contao\BackendTemplate;
use Contao\BerichtModel;
use Contao\MemberModel;
use Contao\Input;
use Contao\Config;
use Patchwork\Utf8;


/**
 * Front end module "Formular Bericht erstellen".
 *
 * @property array $editable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleBerichtReader extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_bericht_reader';

    /**
     * @var
     */
    protected $User;


    /**
     * Return a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['jadeBerichtReader'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // Set the item from the auto_item parameter
        if (!isset($_GET['items']) && Config::get('useAutoItem') && isset($_GET['auto_item']))
        {
            Input::setGet('items', Input::get('auto_item'));
        }


        $objBericht = BerichtModel::findByPk( Input::get('items'));
        if ($objBericht === null)
        {
            return '';
        }

        $this->objBericht = $objBericht;


        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {

        $arrData = $this->objBericht->row();
        foreach($arrData as $key => $value)
        {
            $this->Template->{$key} = $value;
        }

        $objAuthor = MemberModel::findByPk($this->objBericht->autor);
        if($objAuthor !== null)
        {
            $this->Template->autorFirstname = $objAuthor->firstname;
            $this->Template->autorLastname = $objAuthor->lastname;
            $this->Template->autorEmail = $objAuthor->email;
            $this->Template->autorStreet = $objAuthor->street;
            $this->Template->autorPostal = $objAuthor->postal;
            $this->Template->autorCity = $objAuthor->city;
            $this->Template->autorCountry = $objAuthor->country;
            $this->Template->autorId = $objAuthor->id;
            $this->Template->arrAutor = $objAuthor->row();
        }
    }
}
