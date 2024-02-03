<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
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
            $moduleHtml = $renderer->render($mod, $params, $content);

            if ($frontediting && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.' . $mod->id))
            {
                $displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
                LayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }


            require_once JPATH_BETTING_COMPONENT.'/helpers/Utilities.php';
            require_once JPATH_BETTING_COMPONENT.'/helpers/Session.php';
            require_once JPATH_BETTING_COMPONENT.'/helpers/Constants.php';
            $all_modules = \JModuleHelper::getModules($position);
            $player_wise_check_enabled = false;

            if(isset($mod->module) && $mod->module == 'mod_popup' && JFactory::getApplication()->getTemplate('template')->id == 9) {
                $shownPages = \Session::getSessionVariable('popUpShownOn');
                if(!isset($shownPages[$app->getMenu()->getActive()->id]))
                    $shownPages[$app->getMenu()->getActive()->id] = array();

                if(!isset($shownPages[$app->getMenu()->getActive()->id])) {
                    $shownPages[$app->getMenu()->getActive()->id] = array();
                    array_push($shownPages[$app->getMenu()->getActive()->id], $mod->id);
                    \Session::setSessionVariable('popUpShownOn', $shownPages);
                }
                else if(isset($shownPages[$app->getMenu()->getActive()->id]) && array_search($mod->id, $shownPages[$app->getMenu()->getActive()->id]) === false) {
                    array_push($shownPages[$app->getMenu()->getActive()->id], $mod->id);
                    \Session::setSessionVariable('popUpShownOn', $shownPages);
                }
                else {
                    continue;
                }
            }

            $buffer .= $moduleHtml;
        }

        \JEventDispatcher::getInstance()->trigger('onAfterRenderModules', array(&$buffer, &$params));

        return $buffer;
    }
}
