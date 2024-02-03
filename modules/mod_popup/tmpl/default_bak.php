<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
jimport( 'joomla.application.module.helper' );
$modules = JModuleHelper::getModules( 'popup' );
$active = "active";
if(Validations::$popUpCreated === false) {
    ?>
    <div class="modal fade in <?php echo json_decode($modules[0]->params)->modal_class;?>" id="<?php echo json_decode($modules[0]->params)->modal_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog <?php echo json_decode($modules[0]->params)->modal_type;?>">
            <div class="modal-content">
<!--                <div class="modal-header" style="border-bottom: none; background: none;">-->
<!--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
<!--                </div>-->
                <div class="modal-body">
                    <div class="sp-module-content">
                        <?php
                        if(count($modules) === 1) {
                            if (json_decode($modules[0]->params)->prepare_content == 1) {
                                JPluginHelper::importPlugin('content');
                                print_r(JHtml::_('content.prepare', json_decode($modules[0]->params)->content, '', 'mod_custom.content'));
                            } else {
                                print_r(json_decode($modules[0]->params)->content);
                            }
                        }
                        else {
                            $slider_lis = "<ol class='carousel-indicators'>";
                            $slider_divs = "<div class='carousel-inner' role='listbox'>";
                            $i = 0;
                            foreach ($modules as $module) {
                                $slider_lis .= "<li data-target='#myPopUpCarousel' data-slide-to='".$i."' class='".$active."'></li>";
                                $slider_divs .= "<div class='item ".$active."'>";

                                if (json_decode($module->params)->prepare_content == 1) {
                                    JPluginHelper::importPlugin('content');
                                    $slider_divs .= JHtml::_('content.prepare', json_decode($module->params)->content, '', 'mod_custom.content');
                                } else {
                                    $slider_divs .= json_decode($module->params)->content;
                                }

                                $slider_divs .= "</div>";
                                $active = "";
                                $i++;
                            }
                            $slider_lis .= "</ol>";
                            $slider_divs .= "</div>";
                            ?>
                            <div id="myPopUpCarousel" class="carousel slide" data-ride="carousel">
                                <?php
                                echo $slider_lis;
                                echo $slider_divs;
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php
            $deviceType = Configuration::getDeviceType();
            if($deviceType == Configuration::DEVICE_PC && json_decode($modules[0]->params)->on_desktop == 1) {
                ?>
                $(document).ready(function () {
                    $("#<?php echo json_decode($modules[0]->params)->modal_id;?>").modal({
                        show: true
                    });
                });
                <?php
            }
            else if($deviceType != Configuration::DEVICE_PC && json_decode($modules[0]->params)->on_non_desktop == 1) {
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