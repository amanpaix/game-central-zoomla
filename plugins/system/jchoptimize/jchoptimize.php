<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/joomla-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use JchOptimize\Core\Helper;
use JchOptimize\Core\Browser;
use JchOptimize\Core\Optimize;
use JchOptimize\Core\Logger;
use JchOptimize\Platform\Settings;
use JchOptimize\Platform\Cache;
use JchOptimize\Platform\Uri;
use FOF40\Container\Container;
use Joomla\Registry\Registry;

if ( ! defined( 'JCH_PLUGIN_DIR' ) )
{
	define( 'JCH_PLUGIN_DIR', dirname( __FILE__ ) );
}

include_once JPATH_ADMINISTRATOR . '/components/com_jchoptimize/autoload.php';
include_once JPATH_ADMINISTRATOR . '/components/com_jchoptimize/version.php';

class plgSystemJchoptimize extends CMSPlugin
{
	public $bEnabled = true;
	protected $oParams;

	/**
	 * @var   SiteApplication
	 */
	protected $oApp;
	private $oContainer;

	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );

		// Disable if the component is not installed or disabled
		if ( ! ComponentHelper::isEnabled( 'com_jchoptimize' ) )
		{
			$this->bEnabled = false;

			return false;
		}

		// Disable if FOF cannot be loaded
		if ( ! defined( 'FOF40_INCLUDED' ) && ! @include_once( JPATH_LIBRARIES . '/fof40/include.php' ) )
		{
			$this->bEnabled = false;

			return false;
		}

		//Disable if we cannot initialize application
		try
		{
			$this->oApp = Factory::getApplication();
		}
		catch ( Exception $e )
		{
			$this->bEnabled = false;

			return false;
		}

		//Disable if we can't get component's container
		try
		{
			$this->oContainer = Container::getInstance( 'com_jchoptimize' );
		}
		catch ( Exception $e )
		{
			$this->bEnabled = false;

			return false;
		}

		$oApp   = $this->oApp;
		$oInput = $oApp->input;

		//Disable if not on front end
		if ( ! $oApp->isClient( 'site' ) )
		{
			$this->bEnabled = false;

			return false;
		}

		//Disable if jchnooptimize set
		if ( $oInput->get( 'jchnooptimize', '', 'int' ) == 1 )
		{
			$this->bEnabled = false;

			return false;
		}

		//Disable if site offline and user is guest
		$user = Factory::getUser();

		if ( $oApp->get( 'offline', '0' ) && $user->get( 'guest' ) )
		{
			$this->bEnabled = false;

			return false;
		}

		//Get and set component's parameters
		$this->oParams = new Registry( $this->oContainer->params->getParams() );

		if ( ! defined( 'JCH_DEBUG' ) )
		{
			define( 'JCH_DEBUG', ( $this->oParams->get( 'debug', 0 ) && JDEBUG ) );
		}
	}

	public function onAfterRoute()
	{
		//If already disabled return
		if ( ! $this->bEnabled )
		{
			return false;
		}
		//Disable if menu or page excluded
		$menuexcluded    = $this->oParams->get( 'menuexcluded', array() );
		$menuexcludedurl = $this->oParams->get( 'menuexcludedurl', array() );

		if ( in_array( $this->oApp->input->get( 'Itemid', '', 'int' ), $menuexcluded ) ||
			Helper::findExcludes( $menuexcludedurl, Uri::getInstance()->toString() ) )
		{
			$this->bEnabled = false;

			return false;
		}

		//Disable if page being edited
		if ( $this->oApp->input->get( 'layout' ) == 'edit' )
		{
			$this->bEnabled = false;

			return false;
		}
	}

	/**
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function onAfterRender()
	{
		if ( ! $this->bEnabled )
		{
			return false;
		}

		if ( $this->oParams->get( 'debug', 0 ) )
		{
			error_reporting( E_ALL & ~E_NOTICE );
		}

		$sHtml = $this->oApp->getBody();

		//Html invalid
		if ( ! Helper::validateHtml( $sHtml ) )
		{
			return false;
		}

		if ( $this->oApp->input->get( 'jchbackend' ) == '1' )
		{
			return false;
		}

		if ( $this->oApp->input->get( 'jchbackend' ) == '2' )
		{
			echo $sHtml;
			while ( @ob_end_flush() )
			{
				;
			}
			exit;
		}

		try
		{
			$sOptimizedHtml = Optimize::optimize( new Settings( $this->oParams ), $sHtml );
		}
		catch ( Exception $ex )
		{
			Logger::log( $ex->getMessage(), new Settings( $this->oParams ) );

			$sOptimizedHtml = $sHtml;
		}

		$this->oApp->setBody( $sOptimizedHtml );
	}

	/**
	 * Gets the name of the current Editor
	 *
	 * @staticvar string $sEditor
	 * @return string
	 */
	protected function isEditorLoaded()
	{
		$aEditors = JPluginHelper::getPlugin( 'editors' );

		foreach ( $aEditors as $sEditor )
		{
			if ( class_exists( 'plgEditor' . $sEditor->name, false ) )
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Provide a hash for the default page cache plugin's key based on type of browser detected by Google font
	 *
	 *
	 * @return string $hash    Calculated hash of browser type
	 */
	public function onPageCacheGetKey()
	{
		$browser = Browser::getInstance();
		$hash    = $browser->getFontHash();

		return $hash;
	}

	public function onJchCacheExpired()
	{
		return Cache::deleteCache( 'plugin' );
	}


	/**
	 *
	 */
	public function onAfterDispatch()
	{
		//If already disabled return false
		if (! $this->bEnabled )
		{
			return false;
		}

		//Disable if editor loaded
		if ( $this->isEditorLoaded() )
		{
			$this->bEnabled = false;

			return false;
		}

		if ( $this->oParams->get( 'lazyload_enable', '0' ) )
		{
			$oDocument = Factory::getDocument();

			$oDocument->addScript( Uri::base( true ) . '/media/com_jchoptimize/core/js/ls.loader.js' );

			if ( $this->oParams->get( 'pro_lazyload_bgimages', '0' ) || $this->oParams->get( 'pro_lazyload_audiovideo', '0' ) )
			{
				$oDocument->addScript( Uri::base( true ) . '/media/com_jchoptimize/lazysizes/ls.unveilhooks.js' );
			}

			if ( $this->oParams->get( 'pro_lazyload_effects', '0' ) )
			{
				$oDocument->addStyleSheet( Uri::base( true ) . '/media/com_jchoptimize/core/css/ls.effects.css' );
				$oDocument->addScript( Uri::base( true ) . '/media/com_jchoptimize/core/js/ls.loader.effects.js' );
			}

			$oDocument->addScript( Uri::base( true ) . '/media/com_jchoptimize/lazysizes/lazysizes.js' );
		}
	}
}
