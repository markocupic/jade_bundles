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
use Contao\Config;
use Contao\Database;
use Contao\Input;
use Contao\PageModel;
use Contao\Environment;
use Contao\Pagination;
use Patchwork\Utf8;


/**
 * Front end module "Formular Bericht erstellen".
 *
 * @property array $editable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleBerichtListe extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_bericht_liste';

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

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['jadeBerichtListe'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }


        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {

        // Generate the filter board
        $arrOptions = array();
        $strSelected = Input::get('filter-region') != '' ? '' : 'selected';
        $arrOptions[] = sprintf('<option value="0"%s>Alle Bezirke</option>', $strSelected);

        foreach (Config::get('bericht_bundeslaender') as $key)
        {
            $strSelected = (Input::get('filter-region') != '' && $key === Input::get('filter-region')) ? 'selected' : '';
            $arrOptions[] = sprintf('<option value="' . $key . '"%s>' . $GLOBALS['TL_LANG']['BEZIRKE'][$key] . '</option>', $strSelected);
        }
        $this->Template->options = implode("\r\n", $arrOptions);


        // 1. query
        $columns = array(
            'tl_bericht.published=?',
        );
        $values = array(
            '1',
        );
        $options = array(
            'order' => 'tl_bericht.pruefungsdatum DESC',
        );

        if (Input::get('filter-region') != '' && Input::get('filter-region') != '0')
        {
            $columns[] = 'tl_bericht.pruefungsbezirk=?';
            $values[] = Input::get('filter-region');
        }

        $objBericht = BerichtModel::findBy($columns, $values, $options);
        if ($objBericht === null)
        {
            // Return here, if there are no items
            return;
        }

        $arrValues = array();
        $items = array();
        while ($objBericht->next())
        {
            $items[] = $objBericht->id;
        }

        $offset = 0;
        $total = \count($items);
        $limit = $total;

        // 2. query & pagination
        if ($this->perPage > 0)
        {
            // Get the current page
            $id = 'page_g' . $this->id;

            $page = (Input::get($id) !== null) ? Input::get($id) : 1;

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > max(ceil($total / $this->perPage), 1))
            {
                throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
            }

            // Set limit and offset
            $offset = ($page - 1) * $this->perPage;
            $limit = min($this->perPage + $offset, $total);

            $objPagination = new Pagination($total, $this->perPage, Config::get('maxPaginationLinks'), $id);
            $this->Template->hasPagination = true;
            $this->Template->pagination = $objPagination->generate("\n  ");

            $objBerichtItems = Database::getInstance()->prepare('SELECT * FROM tl_bericht WHERE id IN(' . implode(',', $items) . ') ORDER BY pruefungsdatum DESC LIMIT ?,?')->execute($offset, $limit);
        }
        else
        {
            $objBerichtItems = Database::getInstance()->prepare('SELECT * FROM tl_bericht WHERE id IN(' . implode(',', $items) . ') ORDER BY pruefungsdatum DESC')->execute();
        }

        // Prepare items for the template
        while ($objBerichtItems->next())
        {
            $row = $objBerichtItems->row();
            $this->Template->hasItems = true;

            $objPage = PageModel::findByPk($this->jumpTo);
            if ($objPage !== null)
            {
                $row['link'] = ampersand($objPage->getFrontendUrl((Config::get('useAutoItem') ? '/' : '/items/') . $row['id']));
            }

            $arrValues[] = $row;
        }


        $this->Template->rows = $arrValues;


    }
}
