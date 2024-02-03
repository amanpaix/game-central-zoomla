<?php defined( '_JEXEC' ) or die; 
include_once JPATH_THEMES.'/'.$this->template.'/logic.php';
require_once JPATH_BETTING_COMPONENT.'/helpers/Includes.php';

?>

<!doctype html>

<html lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo JUri::base();?>" />
</head>
<body class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('site')).' '.$active->alias.' '.$pageclass; ?> <?php if(Session::sessionValidate()){ echo "post-login";}else {echo "pre-login";}?>" >
	<jdoc:include type="modules" name="body" />
</body>

</html>
