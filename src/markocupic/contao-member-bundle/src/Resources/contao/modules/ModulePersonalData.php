<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Markocupic\ContaoMemberBundle;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\Database;
use Contao\Dbafs;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\Folder;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\PageModel;
use Haste\Form\Form;
use Patchwork\Utf8;


/**
 * Front end module "personal data".
 *
 * @property array $editable
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModulePersonalData extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'member_profile_jade';

    /**
     * @var
     */
    protected $User;

    /**
     * @var string
     */
    protected $avatarPath;


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

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['jadePersonalData'][0]) . ' ###';
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

        // Get the avatar path from config.php
        $this->avatarPath = sprintf(Config::get('avatarUploadPath'), $this->User->id);


        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
        // Create Folder
        new Folder($this->avatarPath);
        Dbafs::addResource($this->avatarPath);

        $objForm = new Form('memberProfile', 'POST', function ($objHaste) {
            return Input::post('FORM_SUBMIT') === $objHaste->getFormId();
        });

        $objForm->setFormActionFromUri(Environment::get('uri'));


        $objModel = MemberModel::findByPk($this->User->id);
        $objForm->bindModel($objModel);

    
        $objForm->addFieldsFromDca('tl_member', function (&$strField, &$arrDca) {

            // make sure to skip elements without inputType or you will get an exception
            if (!isset($arrDca['inputType']))
            {
                return false;
            }

            if ($strField === 'gender')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'firstname')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'lastname')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'street')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'postal')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'city')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'bundesland')
            {
                $arrDca['eval']['mandatory'] = false;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'email')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'phone')
            {
                $arrDca['eval']['mandatory'] = false;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'kurzportrait')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'statusDerWeiterbildung')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'aufmerksamGewordenDurch')
            {
                $arrDca['eval']['mandatory'] = true;
                // you must return true otherwise the field will be skipped
                return true;
            }

            if ($strField === 'groups')
            {
                $arrDca['eval']['mandatory'] = true;
                $arrDca['inputType'] = 'checkbox';
                // you must return true otherwise the field will be skipped
                return true;
            }
            return false;

        });


        // Now let's add the avatar form fields:
        $objForm->addFormField('avatar', array(
            'label'     => 'Avatar',
            'inputType' => 'upload',
            'eval'      => array('mandatory' => false),
        ));

        // Add captcha
        $objForm->addCaptchaFormField('captcha');

        // Add submit
        $objForm->addSubmitFormField('submit', 'Speichern');


        // !!!Place this this right before the validation
        $objWidget = $objForm->getWidget('avatar');
        $objWidget->extensions = 'jpg,jpeg,gif,png,svg';
        $objWidget->storeFile = true;

        $objWidget->uploadFolder = FilesModel::findByPath($this->avatarPath)->uuid;
        $objWidget->addAttribute('accept', '.jpg,.jpeg,.gif,.png,.svg');

        // Standardize name
        if (Input::post('FORM_SUBMIT') === 'memberProfile' && !empty($_FILES['avatar']['tmp_name']))
        {
            $objFile = new File($_FILES['avatar']['name']);
            $_FILES['avatar']['name'] = 'avatar.' . strtolower($objFile->extension);
            // Save as con4gis avatar to tl_member.memberImage
            $objModel->memberImage = serialize(array($this->avatarPath . '/' . $_FILES['avatar']['name']));
            Database::getInstance()->prepare('UPDATE tl_member SET memberImage=? WHERE id=?')->execute($objModel->memberImage, $this->User->id);
        }


        if ($objForm->validate())
        {
            if (Input::post('FORM_SUBMIT') === 'memberProfile')
            {
                // Sync username with email
                $objModel->username = $objModel->email;

                // Use Bundesland only if member is from Germany
                if ($objModel->country != 'de')
                {
                    $objModel->bundesland = '';
                }

                // The model will now contain the changes so you can save it
                $objModel->save();


                // HOOK: updated personal data
                if (isset($GLOBALS['TL_HOOKS']['updatePersonalData']) && \is_array($GLOBALS['TL_HOOKS']['updatePersonalData']))
                {
                    foreach ($GLOBALS['TL_HOOKS']['updatePersonalData'] as $callback)
                    {
                        $this->import($callback[0]);
                        $this->{$callback[0]}->{$callback[1]}($objModel, $_SESSION['FORM_DATA'], $this);
                    }
                }

                // Call the onsubmit_callback
                if (\is_array($GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback']))
                {
                    foreach ($GLOBALS['TL_DCA']['tl_member']['config']['onsubmit_callback'] as $callback)
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


        $this->Template->form = $objForm;
        $objForm->addToTemplate($this->Template);

        // Add user model to template
        $this->Template->User = $this->User;


    }
}
