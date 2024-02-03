<?php

defined('_JEXEC') or die;

class mod_customcontentInstallerScript {

    function install($parent) {
        //echo '<p>The module has been installed</p>';        
    }

    function uninstall($parent) {
        echo '<p>The module has been uninstalled</p>';
        $db = JFactory::getDBO();
        $db->setQuery("SELECT id FROM #__modules WHERE `module` = 'mod_customcontent'");
        $ids = $db->loadColumn();
        if (!function_exists('removeDirectoryCustomContent')) {
            function removeDirectoryCustomContent($dir) {
                if ($objs = glob($dir . "/*")) {
                    foreach ($objs as $obj) {
                        is_dir($obj) ? removeDirectoryCustomContent($obj) : unlink($obj);
                    }
                }
                @rmdir($dir);
            }
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
