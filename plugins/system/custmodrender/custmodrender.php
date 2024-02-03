<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Document\Renderer\Html;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;

/**
 * HTML document renderer for a module position
 *
 * @since  3.5
 */
class ModulesRenderer extends DocumentRenderer
{
    /**
     * Renders multiple modules script and returns the results as a string
     *
     * @param   string  $position  The position of the modules to render
     * @param   array   $params    Associative array of values
     * @param   string  $content   Module content
     *
     * @return  string  The output of the script
     *
     * @since   3.5
     */
    public function render($position, $params = array(), $content = null)
    {
        $renderer = $this->_doc->loadRenderer('module');
        $buffer   = '';

        $app          = \JFactory::getApplication();
        $user         = \JFactory::getUser();
        $frontediting = ($app->isClient('site') && $app->get('frontediting', 1) && !$user->guest);
        $menusEditing = ($app->get('frontediting', 1) == 2) && $user->authorise('core.edit', 'com_menus');

        foreach (ModuleHelper::getModules($position) as $mod)
        {


            require_once JPATH_ROOT . '/components/com_betting' . '/helpers/Utilities.php';
            require_once JPATH_ROOT . '/components/com_betting' . '/helpers/Session.php';
            require_once JPATH_ROOT . '/components/com_betting' . '/helpers/Constants.php';

            $all_modules = \JModuleHelper::getModules($position);
            $player_wise_check_enabled = false;

// && array_search($position, Constants::ALLOWED_POSITION_PLAYERWISE_CONTENT) !== false
            if (\Session::sessionValidate() && isset($app->getMenu()->getActive()->id)) {
                $current_alias_id = $app->getMenu()->getActive()->id;
                $mapping = \Utilities::getPlayerLoginResponse()->mapping;
                //exit(json_encode($mapping));
                $mapping = get_object_vars($mapping);


//			if(array_key_exists($current_alias_id, $mapping) === true) {
//				$player_wise_check_enabled = true;
//			}

                if (isset($mapping[$current_alias_id])) {
                    $player_wise_check_enabled = true;

                    $tmp_ids = array();
                    foreach ($all_modules as $tmp_mod) {
                        array_push($tmp_ids, $tmp_mod->id);
                    }

                    $tmp_check = false;
                    foreach ($mapping[$current_alias_id] as $received_mod) {
                        if (array_search($received_mod, $tmp_ids) !== false) {
                            $tmp_check = true;
                            break;
                        }
                    }
                    $player_wise_check_enabled = $tmp_check;
                }
            }


            if ($player_wise_check_enabled && array_search($mod->id, $mapping[$current_alias_id]) === false) {
                continue;
            }

            if (isset($mod->module) && (array_search($mod->module, \Constants::PLAYER_WISE_MODULES) !== false) && isset($mod->params)) {
                $tmp_params = json_decode($mod->params);
                if (isset($tmp_params->is_playerwise) && $tmp_params->is_playerwise == 1 && $player_wise_check_enabled === false) {
                    continue;
                }
            }

            if (isset($mod->module) && (array_search($mod->module, \Constants::PLAYER_WISE_MODULES) !== false) && isset($mod->params)) {
                $tmp_params = json_decode($mod->params);
                if (isset($tmp_params->is_playerwise) && $tmp_params->is_playerwise == 1) {
                    $tmp_params->moduleclass_sfx = $tmp_params->moduleclass_sfx . " " . \Constants::CLASS_NAME_SLIDE_DIVS;
                    $tmp_params = json_encode($tmp_params);
                    $mod->params = $tmp_params;
                }
            }


            if (isset($mod->module) && $mod->module == 'mod_popup' && \JFactory::getApplication()->getTemplate('template')->id == 9) {
                $shownPages = \Session::getSessionVariable('popUpShownOn');

/*                if (!isset($shownPages[$app->getMenu()->getActive()->id]))
                    $shownPages[$app->getMenu()->getActive()->id] = array();

                if (!isset($shownPages[$app->getMenu()->getActive()->id])) {
                    $shownPages[$app->getMenu()->getActive()->id] = array();
                    array_push($shownPages[$app->getMenu()->getActive()->id], $mod->id);
                    \Session::setSessionVariable('popUpShownOn', $shownPages);
                } else if (isset($shownPages[$app->getMenu()->getActive()->id]) && array_search($mod->id, $shownPages[$app->getMenu()->getActive()->id]) === false) {
                    array_push($shownPages[$app->getMenu()->getActive()->id], $mod->id);
                    \Session::setSessionVariable('popUpShownOn', $shownPages);
                } else {
                    continue;
                }*/

                if( \Session::getSessionVariable('popUpShownOn') === false )
                {
                    \Session::setSessionVariable('popUpShownOn', true);
                }
                else
                {
                    continue;
                }

            }

            $moduleHtml = $renderer->render($mod, $params, $content);

            if ($frontediting && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.' . $mod->id))
            {
                $displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
                LayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }

            $buffer .= $moduleHtml;
        }

        \JEventDispatcher::getInstance()->trigger('onAfterRenderModules', array(&$buffer, &$params));

        return $buffer;
    }
}
