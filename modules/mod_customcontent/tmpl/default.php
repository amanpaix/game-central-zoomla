<?php
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
if($contentType == 1){
?>
<div id="featuresTab-<?php echo $module->id; ?>" class="featuresTab <?php echo $mainClass; ?>">
    <?php if(isset($mainHeading)){ ?>
        <h2 class="section_heading"><?php echo $mainHeading; ?></h2>
    <?php } ?>
    <ul class="nav nav-tabs">
        <?php foreach($contentList as $key=>$value){ ?>
        <li class="<?php echo ($key == 'contentList0') ? 'active ' : ''; ?><?php echo $value->panelHeadingClass; ?>">
            <a data-toggle="tab" href="#<?php echo $key.'-'.$module->id; ?>">
                <span class="icon"><i class="<?php echo $value->panelIcon; ?>"></i></span>
                <?php echo $value->panelHeading; ?>
            </a>
        </li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <?php foreach($contentList as $key=>$value){ ?>
        <div id="<?php echo $key.'-'.$module->id; ?>" class="tab-pane fade <?php echo ($key == 'contentList0') ? 'in active ' : ''; ?>">
            <?php echo $value->panelContent; ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php 
}else{
?>
<div class="panel-group <?php echo $mainClass; ?>" id="accordion-<?php echo $module->id; ?>">
    <?php if(isset($mainHeading)){ ?>
        <h2 class="section_heading"><?php echo $mainHeading; ?></h2>
    <?php } ?>
    <?php foreach($contentList as $key=>$value){ ?>    
    <div class="panel panel-default">
        <div class="panel-heading <?php echo $value->panelHeadingClass; ?>">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion-<?php echo $module->id; ?>" href="#<?php echo $key.'-'.$module->id; ?>">
                    <span class="icon"><i class="<?php echo $value->panelIcon; ?>"></i></span>
                    <?php echo $value->panelHeading; ?>
                </a>
            </h4>
        </div>
        <div id="<?php echo $key.'-'.$module->id; ?>" class="panel-collapse <?php echo ($key == 'contentList0') ? 'in ' : ''; ?>collapse">
            <div class="panel-body">
                <?php echo $value->panelContent; ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div> 
<?php 
}
?>