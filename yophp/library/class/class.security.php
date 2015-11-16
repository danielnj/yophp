<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name security.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */
//防止XSS
class YO_security
{
    
	
	/**
     * Remove XSS from user input.
     */
    public static function xss_clean($str)
    {


        // Fix &entity\n;
        $str = str_replace(array(
                '&amp;',
                '&lt;',
                '&gt;'), array(
                '&amp;amp;',
                '&amp;lt;',
                '&amp;gt;'), $str);
        $str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
        $str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
        $str = html_entity_decode($str, ENT_COMPAT, Kohana::$charset);

        // Remove any attribute starting with "on" or xmlns
        $str = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $str);

        // Remove javascript: and vbscript: protocols
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $str);

        // Remove namespaced elements (we do not need them)
        $str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);

        do {
            // Remove really unwanted tags
            $old = $str;
            $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
        } while ($old !== $str);

        return $str;
    }

    /**
     * Remove image tags from a string.
     *
     * @param   string  string to sanitize
     * @return  string
     */
    public static function strip_image_tags($str)
    {
        return preg_replace('#<img\s.*?(?:src\s*=\s*["\']?([^"\'<>\s]*)["\']?[^>]*)?>#is', '$1', $str);
    }

    /**
     * Remove PHP tags from a string.
     *
     * @param   string  string to sanitize
     * @return  string
     */
    public static function encode_php_tags($str)
    {
        return str_replace(array(
                '<?',
                '?>'), array(
                '&lt;?',
                '?&gt;'), $str);
    }

}
?>