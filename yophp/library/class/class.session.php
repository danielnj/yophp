<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name class.session.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */

  class YO_session
  {


    public static function init()
    {
      if( !isset($_SESSION) )
        @session_start();
    }
    
    public static function startSessionById($sessionId)
    {
      session_start($sessionId);
    }
    
    public static function getByKey($keyName)
    {
      if(isset($_SESSION[$keyName]))
        return $_SESSION[$keyName];
        
      return NULL;
    }

    public static function set($keyName, $keyValue)
    {
      $_SESSION[$keyName] =  $keyValue;
    }

    public static function remove($keyName)
    {
      if( self::isExists($keyName) )
        unset($_SESSION[$keyName]);
    }

    public static function rename($key, $newKey)
    {
      $tmp = $_SESSION[$key];
      unset($_SESSION[$key]);
      self::set($newKey, $tmp);
    }

    public static function setAll(array $newArray)
    {
      $_SESSION = $newArray;
    }

    public static function clear()
    {
      $_SESSION = array();
    }

    public static function isExists($key)
    {
      if(isset($_SESSION[$key]))
        return true;
      else
        return false;
    }
    
    public static function getAll()
    {
      return $_SESSION;
    }
  }
?>
