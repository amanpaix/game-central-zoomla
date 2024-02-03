<?php
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.view');

class BettingViewIframegames extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{ 

            $app = JFactory::getApplication();
//            $dbValues = $this->get('Items');
//            $formvalues = json_decode($dbValues[0]->jsonData);
//            if(
//                ($this->getLayout() == 'drawgames' && $formvalues->enableDge == 1) ||
//                ($this->getLayout() == 'sportslottery' && $formvalues->enableSle == 1) ||
//                ($this->getLayout() == 'sportsbetting' && $formvalues->enableSbs == 1) ||
//                ($this->getLayout() == 'bingo' && $formvalues->enableBingo == 1)
//            ){
                parent::display($tpl);
//            }else{
//                $app->enqueueMessage('Invalid Url Access', 'error');
//                $app->redirect(JUri::base());
//            }
            
	}
}
