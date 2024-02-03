<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';
jimport( 'joomla.application.module.helper' );
$modules = JModuleHelper::getModules( 'popup' );
$active = "active";
$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive()->id;
//exit($menu);
//exit;

if(Validations::$popUpCreated === false) {
    ?>
    <div class="modal fade in <?php echo json_decode($modules[0]->params)->modal_class;?>" id="<?php echo json_decode($modules[0]->params)->modal_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog <?php echo json_decode($modules[0]->params)->modal_type;?>">
            <div class="modal-content">
                <!--                <div class="modal-header" style="border-bottom: none; background: none;">-->
                <!--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">�</span></button>-->
                <!--                </div>-->
                <div class="modal-body">
                    <div class="sp-module-content">
                        <p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <img src="templates/shaper_helix3/images/common/close.png" alt="" />
                            </button>
                        </p>
                        <div class="slider post_login_popup_banner">
                            <?php
                            $count = 0;
                            $d_data = "";
                            $mod_arr = array();
                            foreach ($modules as $module)
                            {
                                if( Session::sessionValidate() )
                                {
                                    //$current_mod_id = json_decode($modules[0]->id);
                                    $mapping = Utilities::getPlayerLoginResponse()->mapping;
                                    //exit(json_encode($mapping));
                                    $mapping = get_object_vars($mapping);

                                    if(isset($mapping[$menu]))
                                    {
                                        $mod_list = $mapping[$menu];
                                        //exit($mod_list);
                                        $current_mod_id = json_decode($module->id);

                                        //if(Utilities::getPlayerId() == "337")
                                        //   exit(json_encode($mod_list));

                                        if(array_search($current_mod_id, $mod_list, false) !== false)
                                        {
                                            if (json_decode($module->params)->is_playerwise == 1)
                                            {
                                                $count++;
                                                if (json_decode($module->params)->prepare_content == 1)
                                                {
                                                    JPluginHelper::importPlugin('content');
                                                    print_r(JHtml::_('content.prepare', json_decode($module->params)->content, '', 'mod_custom.content'));
                                                }
                                                else
                                                {
                                                    print_r(json_decode($module->params)->content);
                                                }
                                            }
                                        }
                                        else
                                        {
                                            if (json_decode($module->params)->is_playerwise == 0)
                                            {
                                                array_push($mod_arr, $module);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(json_decode($module->params)->is_playerwise == 0)
                                        {
                                            if (json_decode($module->params)->prepare_content == 1) {
                                                JPluginHelper::importPlugin('content');
                                                print_r(JHtml::_('content.prepare', json_decode($module->params)->content, '', 'mod_custom.content'));
                                            }
                                            else {
                                                print_r(json_decode($module->params)->content);
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    if(json_decode($module->params)->is_playerwise == 0)
                                    {
                                        if (json_decode($module->params)->prepare_content == 1) {
                                            JPluginHelper::importPlugin('content');
                                            print_r(JHtml::_('content.prepare', json_decode($module->params)->content, '', 'mod_custom.content'));
                                        }
                                        else {
                                            print_r(json_decode($module->params)->content);
                                        }
                                    }
                                }
                            }

                            if($count == 0)
                            {
                                foreach ($mod_arr as $def_mod)
                                {
                                    if(json_decode($def_mod->params)->is_playerwise == 0)
                                    {
                                        if (json_decode($def_mod->params)->prepare_content == 1) {
                                            JPluginHelper::importPlugin('content');
                                            print_r(JHtml::_('content.prepare', json_decode($def_mod->params)->content, '', 'mod_custom.content'));
                                        }
                                        else {
                                            print_r(json_decode($def_mod->params)->content);
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php
        $deviceType = Configuration::getDeviceType();
	//echo json_encode($modules[0]->params).'<br>';
	//exit(json_encode($modules));

	//exit(json_encode(json_decode($modules[0]->params)));
        if($deviceType == Configuration::DEVICE_PC && json_decode($modules[0]->params)->on_desktop == 1) {
	//exit(json_encode($deviceType));
        ?>
        $(document).ready(function () {
            $("#<?php echo json_decode($modules[0]->params)->modal_id;?>").modal({
                show: true
            });
        });
        <?php
        }
        else if($deviceType != Configuration::DEVICE_PC && json_decode($modules[0]->params)->on_non_desktop == 1) {
	//exit(json_encode($deviceType));
        ?>
        $(document).ready(function () {
            $("#<?php echo json_decode($modules[0]->params)->modal_id;?>").modal({
                show: true
            });
        });
        <?php
        }
        ?>
    </script>
    <?php
}
Validations::$popUpCreated = true;
?>
