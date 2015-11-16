<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name shell.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */


class YO_shell {

	/**
	 * Executes the given command from the command line and returns everything that is displayed
	 * from the command line.
	 *
	 * @param string $command
	 */
	public function Execute($command) {

		/* Add redirection so we can get stderr. */
		$command .= ' 2>&1';

		/**
		 * open the handle.
		 */
		$handle = popen($command, 'r');

		/**
		 * initialize the logfile to empty
		 */
		$log = '';

		/**
		 * read all the items from the command line
		 */
		while (!feof($handle)) {
			$line = fread($handle, 1024);
			$log .= $line;
		}

		/**
		 * close the handle
		 */
		pclose($handle);

		/**
		 * return the log
		 */
		return $log;
	}

}
?>