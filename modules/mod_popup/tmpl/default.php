<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
require_once JPATH_BETTING_COMPONENT . '/helpers/Includes.php';
jimport('joomla.application.module.helper');
$modules = JModuleHelper::getModules('popup');
$deviceType = Configuration::getDeviceType();
$active = "active";
$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive()->id;


if (Validations::$popUpCreated === false) {
    ?>
     <div class="modal fade in <?php echo json_decode($modules[0]->params)->modal_class; ?>" id="<?php echo json_decode($modules[0]->params)->modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-lg <?php echo json_decode($modules[0]->params)->modal_type; ?>">
            <div class="modal-content">
                <div class="modal-body">
                <div class="closeWrap text-right" data-dismiss="modal"><a href="javascript:void(0);"><span class="closeBTN">X</span></a></div>
                    <div class="sp-module-content">
                        <div class="slider post_login_popup_banner">
                            <?php
                            $show_count = 0;
                            $count = 0;
                            $d_data = "";
                            $mod_arr = array();
                            foreach ($modules as $module) {
                                
                                if ((json_decode($module->params)->platform == 1)) {
                                    $mapping = Utilities::getPlayerLoginResponse()->mapping;
                                    $mapping = get_object_vars($mapping);                                        
//                                    if (json_decode($module->params)->prepare_content == 1) {
                                        ++$show_count;
                                        JPluginHelper::importPlugin('content');
                                        print_r(JHtml::_('content.prepare', json_decode($module->params)->content, '', 'mod_custom.content'));
//                                    } else {
//                                        ++$show_count;
//                                        print_r(json_decode($module->params)->content);
//                                    }                                    
                                }
                            }                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 

if ($show_count) { ?>
        <script>


            
            // $('#home_welcome').modal({backdrop:'static', keyboard:false});
//            function getCookie(cname) {
//                var name = cname + "=";
//                var decodedCookie = decodeURIComponent(document.cookie);
//                var ca = decodedCookie.split(';');
//                for(var i = 0; i <ca.length; i++) {
//                    var c = ca[i];
//                    while (c.charAt(0) == ' ') {
//                        c = c.substring(1);
//                    }
//                    if (c.indexOf(name) == 0) {
//                        return c.substring(name.length, c.length);
//                    }
//                }
//                return false;
//            }
            $(document).ready(function () {
                var back = parseInt("<?php echo json_decode($modules[0]->params)->backdrop; ?>");
                var backdrop = Boolean(back);
                if(backdrop == true){
                    $("#<?php echo json_decode($modules[0]->params)->modal_id; ?>").modal({
                        show: true
                    });
                }else{
                    $("#<?php echo json_decode($modules[0]->params)->modal_id; ?>").modal({
                        backdrop:'static',
                        keyboard:false,
                        show: true
                    });
                } 
                //$('.post_login_popup_banner').slick();
//                var c_name = 'ithuba-popup-prelogin' + "=";
//                if(getCookie(c_name) !== false){
//                    $("#<?php echo json_decode($modules[0]->params)->modal_id; ?>").modal({
//                        show: false
//                    });
//                }else{
                                    
//                    document.cookie = "ithuba-popup-prelogin = true; expires=Thu, 31 Dec 2020 12:00:00 UTC; path=/";
//                }
//                $(window).on('beforeunload', function(e) {
//                    document.cookie = "ithuba-popup-prelogin = true; expires=Thu, 31 Dec 2017 12:00:00 UTC; path=/";
//                });
            });
        </script>
    <?php }
}
Validations::$popUpCreated = true;
?>
