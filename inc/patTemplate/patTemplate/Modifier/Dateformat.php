<?PHP
/**
 * patTemplate modfifier Dateformat
 *
 * $Id: Dateformat.php 450 2007-01-11 08:32:32Z gerd $
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate modfifier Dateformat
 *
 * formats dates and times according to a format string.
 *
 * Possible attributes are:
 * - format (string)
 *
 * See the PHP documentation for strftime() for
 * more information.
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 * @link		http://www.php.net/manual/en/function.strftime.php
 */
class patTemplate_Modifier_Dateformat extends patTemplate_Modifier
{
   /**
	* modify the value
	*
	* @access	public
	* @param	string		value
	* @return	string		modified value
	*/
	function modify( $value, $params = array() )
	{
        if (!preg_match('/^[0-9]+$/', $value)) {
        	$value = strtotime($value);
        }
         
        if (isset($params['unix']) && $params['unix'] == 'yes') {
            return $value;
        }
   
        if (!isset($params['format'])) {
            return $value;
        }
		global $lang_dateencoding;
		if (isset($lang_dateencoding)) return iconv($lang_dateencoding, 'UTF-8',strftime($params['format'], $value));
		else return strftime($params['format'], $value);
	}
}
?>