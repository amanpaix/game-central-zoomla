<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");

require_once( dirname(__FILE__).'/helper.php' );

//JPluginHelper::importPlugin('content');
//$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_custom.content');
//print_r($module->content);

if($params->get('css_style')) {
	?>
	<style>
		<?php echo $params->get('css_style'); ?>
	</style>
	<?php
}

print_r($params->get('content'));
require( JModuleHelper::getLayoutPath( 'mod_Bettingemail' ) );
?>
