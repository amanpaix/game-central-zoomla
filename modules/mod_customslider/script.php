<?php

defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');

class mod_customsliderInstallerScript {

    function install($parent) {
        //echo '<p>The module has been installed</p>';
        $path = JPATH_SITE . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "customslider";
        $mode = 0755;
        JFolder::create($path, $mode);
    }

    function uninstall($parent) {
        echo '<p>The module has been uninstalled</p>';
        $db = JFactory::getDBO();
        $db->setQuery("SELECT id FROM #__modules WHERE `module` = 'mod_customslider'");
        $ids = $db->loadColumn();
        if (!function_exists('removeDirectoryCustomSlider')) {
            function removeDirectoryCustomSlider($dir) {
                if ($objs = glob($dir . "/*")) {
                    foreach ($objs as $obj) {
                        is_dir($obj) ? removeDirectoryCustomSlider($obj) : unlink($obj);
                    }
                }
                @rmdir($dir);
            }
        }
        foreach ($ids as $id) {
            $dir = JPATH_ROOT . '/images/customslider';
            removeDirectoryCustomSlider($dir);
        }
    }

    /**
     * Method to update the extension
     * $parent is the class calling this method
     * @return void
     */
    function update($parent) {
        //echo '<p>The module has been updated to version' . $parent->get('manifest')->version) . '</p>';        
    }

    /**
     * Method to run before an install/update/uninstall method
     * $parent is the class calling this method
     * $type is the type of change (install, update or discover_install)
     * @return void
     */
    function preflight($type, $parent) {
        //echo '<p>Anything here happens before the installation/update/uninstallation of the module</p>';
    }

    /**
     * Method to run after an install/update/uninstall method
     * $parent is the class calling this method
     * $type is the type of change (install, update or discover_install)
     * @return void
     */
    function postflight($type, $parent) {
        //echo '<p>Anything here happens after the installation/update/uninstallation of the module</p>';
    }

}
