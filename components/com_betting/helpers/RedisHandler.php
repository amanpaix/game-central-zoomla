<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Log\Log;

/**
 * Redis cache storage handler for PECL
 *
 * @since  3.4
 */

class RedisHandler
{
    /**
     * Redis connection object
     *
     * @var    \Redis
     * @since  3.4
     */
    protected static $_redis = null;
    protected static $_redis_gameInfo_lifeTime = Configuration::REDIS_KEY_LIFETIME;

    /**
     * Persistent session flag
     *
     * @var    boolean
     * @since  3.4
     */
    protected $_persistent = false;

    /**
     * Constructor
     *
     * @param   array  $options  Optional parameters.
     *
     * @since   3.4
     */
    public function __construct()
    {

        if (static::$_redis === null) {
            $this->getConnection();
        }
    }

    /**
     * Create the Redis connection
     *
     * @return  \Redis|boolean  Redis connection object on success, boolean on failure
     *
     * @since   3.4
     * @note    As of 4.0 this method will throw a JCacheExceptionConnecting object on connection failure
     */
    protected function getConnection()
    {

        $server = array(
            'host' => Configuration::REDIS_SERVER,
            'port' => Configuration::REDIS_PORT,
            'db' => 0,
        );

        // If you are trying to connect to a socket file, ignore the supplied port
        if ($server['host'][0] === '/') {
            $server['port'] = 0;
        }

        static::$_redis = new \Redis;

        $connection = false;

        try
        {
            if ($this->_persistent) {
                $connection = static::$_redis->pconnect($server['host'], $server['port']);
            } else {
                $connection = static::$_redis->connect($server['host'], $server['port']);
            }
        } catch (\RedisException $e) {
            Log::add($e->getMessage(), Log::DEBUG);
        }

        if ($connection == false) {
            static::$_redis = null;

            // Because the application instance may not be available on cli script, use it only if needed
            if (\JFactory::getApplication()->isClient('administrator')) {
                \JError::raiseWarning(500, Errors::REDIS_CONNECTION_ERROR);
            }

            return false;
        }

        $select = static::$_redis->select($server['db']);

        if ($select == false) {
            static::$_redis = null;

            // Because the application instance may not be available on cli script, use it only if needed
            if (\JFactory::getApplication()->isClient('administrator')) {
                \JError::raiseWarning(500, Errors::DATABASE_ERROR);
            }

            return false;
        }

        try
        {
            static::$_redis->ping();
        } catch (\RedisException $e) {
            static::$_redis = null;

            // Because the application instance may not be available on cli script, use it only if needed
            if (\JFactory::getApplication()->isClient('administrator')) {
                \JError::raiseWarning(500, Errors::REDIS_PING_ERROR);
            }

            return false;
        }

        return static::$_redis;
    }

    /**
     * Check if the cache contains data stored by ID and group
     *
     * @param   string  $id     The cache data ID
     * @param   string  $group  The cache data group
     *
     * @return  boolean
     *
     * @since   3.7.0
     */
    public function contains($key)
    {

        if (static::isConnected() == false) {
            return false;
        }

        // Redis exists returns integer values lets convert that to boolean see: https://redis.io/commands/exists
        return (bool) static::$_redis->exists($key);
    }

    /**
     * Get cached data by ID and group
     *
     * @param   string   $id         The cache data ID
     * @param   string   $group      The cache data group
     * @param   boolean  $checkTime  True to verify cache time expiration threshold
     *
     * @return  mixed  Boolean false on failure or a cached data object
     *
     * @since   3.4
     */
    public function get($key, $checkTime = true)
    {
        if (static::isConnected() == false) {
            return false;
        }

        return json_decode(static::$_redis->get($key), true);
    }

    /**
     * Get all cached data
     *
     * @return  mixed  Boolean false on failure or a cached data object
     *
     * @since   3.4
     */
    public function getAll()
    {
        if (static::isConnected() == false) {
            return false;
        }

        $allKeys = static::$_redis->keys('*');
        $data = array();
        $secret = $this->_hash;

        if (!empty($allKeys)) {
            foreach ($allKeys as $key) {
                array_push($data, $key);
            }
        }

        return $data;
    }

    /**
     * Store the data to cache by ID and group
     *
     * @param   string  $id     The cache data ID
     * @param   string  $group  The cache data group
     * @param   string  $data   The data to store in cache
     *
     * @return  boolean
     *
     * @since   3.4
     */
    public function store($key, $data)
    {
        if (static::isConnected() == false) {
            return false;
        }

        static::$_redis->setex($key, static::$_redis_gameInfo_lifeTime, $data);

        return true;
    }

    /**
     * Remove a cached data entry by ID and group
     *
     * @param   string  $id     The cache data ID
     * @param   string  $group  The cache data group
     *
     * @return  boolean
     *
     * @since   3.4
     */
    public function remove($key)
    {
        if (static::isConnected() == false) {
            return false;
        }

        return (bool) static::$_redis->delete($key);
    }

    /**
     * Test to see if the storage handler is available.
     *
     * @return  boolean
     *
     * @since   3.4
     */
    public static function isSupported()
    {
        return class_exists('\\Redis');
    }

    /**
     * Test to see if the Redis connection is available.
     *
     * @return  boolean
     *
     * @since   3.4
     */
    public static function isConnected()
    {
        return static::$_redis instanceof \Redis;
    }

}
