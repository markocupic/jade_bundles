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
use Contao\PageModel;
use Contao\Environment;
use Contao\FrontendUser;
use Contao\Input;
use Haste\Form\Form;
use Patchwork\Utf8;


/**
 * Front end module "Formular Bericht erstellen".
 *
 * @property array $editable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleFormBericht extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'form_bericht_jade';

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

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['jadeFormBericht'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        if (!FE_USER_LOGGED_IN)
        {
            return '';
        }

        // Get the FE User object
        $this->User = FrontendUser::getInstance();


        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {

        $objForm = new Form('jadeBericht', 'POST', function ($objHaste) {
            return Input::post('FORM_SUBMIT') === $objHaste->getFormId();
        });

        $objForm->setFormActionFromUri(Environment::get('uri'));


        $objModel = new BerichtModel();
        $objForm->bindModel($objModel);


        $objForm->addFieldsFromDca('tl_bericht', function (&$strField, &$arrDca) {

            // make sure to skip elements without inputType or you will get an exception
            if (!isset($arrDca['inputType']))
            {
                return false;
            }

            if ($strField === 'titel')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'pruefungsdatum')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'pruefungsbezirk')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'bericht')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            return false;

        });


        // Add captcha
        $objForm->addCaptchaFormField('captcha');

        // Add submit
        $objForm->addSubmitFormField('submit', 'Bericht hochladen');







        if ($objForm->validate())
        {
            if (Input::post('FORM_SUBMIT') === 'jadeBericht')
            {
                // Sync username with email
                $objModel->autor = $this->User->id;
                $objModel->tstamp = time();
                $objModel->erstellungsdatum = time();
                $objModel->published = '1';



                // The model will now contain the changes so you can save it
                $objModel->save();

                // Allow html tags
                $objModel->bericht = html_entity_decode($objModel->bericht);
                $objModel->save();


                // HOOK: updated personal data
                if (isset($GLOBALS['TL_HOOKS']['insertJadeBericht']) && \is_array($GLOBALS['TL_HOOKS']['insertJadeBericht']))
                {
                    foreach ($GLOBALS['TL_HOOKS']['insertJadeBericht'] as $callback)
                    {
                        $this->import($callback[0]);
                        $this->{$callback[0]}->{$callback[1]}($objModel, $this->User, $_SESSION['FORM_DATA'], $this);
                    }
                }

                // Call the onsubmit_callback
                if (\is_array($GLOBALS['TL_DCA']['tl_bericht']['config']['onsubmit_callback']))
                {
                    foreach ($GLOBALS['TL_DCA']['tl_bericht']['config']['onsubmit_callback'] as $callback)
                    {
                        if (\is_array($callback))
                        {
                            $this->import($callback[0]);
                            $this->{$callback[0]}->{$callback[1]}($objModel, $this);
                        }
                        elseif (\is_callable($callback))
                        {
                            $callback($objModel, $this);
                        }
                    }
                }

                // Check whether there is a jumpTo page
                if (($objJumpTo = $this->objModel->getRelated('jumpTo')) instanceof PageModel)
                {
                    $this->jumpToOrReload($objJumpTo->row());
                }

                $this->reload();
            }
        }


        $objForm->addToTemplate($this->Template);

        // Add user model to template
        $this->Template->User = $this->User;


    }
}
