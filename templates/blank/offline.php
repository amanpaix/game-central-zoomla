<?php defined( '_JEXEC' ) or die;

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$tpath = $this->baseurl.'/templates/'.$this->template;

// generator tag
$this->setGenerator(null);

// load sheets and scripts
$doc->addStyleSheet($tpath.'/css/offline.css?v=1'); 

?><!doctype html>

<html lang="<?php echo $this->language; ?>">

<head>
  <jdoc:include type="head" />
   <?php $doc->addStyleDeclaration($config->get('offlineCss')); ?>   
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> <!-- mobile viewport optimized -->
</head>

<body>
    <jdoc:include type="message" />
    <?php echo $config->get('offlineData'); ?>
</body>

</html>