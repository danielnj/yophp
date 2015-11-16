<?php
/**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name socekt_server.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */


class YO_socket_client {

	private $socket = NULL;
	private $server = NULL;
	private $port = null;
	private $eol = "\r\n";
	private $debug = true;

	// generic tcp Socket wrapper
    public function __construct($server, $port, $eol = NULL) {
        $this->socket = NULL;
        $this->server = $server;
		$this->port = $port;

		if ($eol !== NULL) {
			$this->eol = $eol;
		}
	}

	/**
	 * return eol
	 * params
	 * return end of line
	**/
	public function getEol() {
		return $this->eol;
	}
	/**
	 * connect to socket
	 * params
	 * return socket reference
	**/
    public function establish() {
        if ($this->socket !== NULL) {
			return $this->socket;
		}

		$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if(!$s){
		$this->quicklog("Socket::establish - Socket creating fails");
		}
        $this->server = gethostbyname($this->server);
		$this->socket = $s;

        return $this->socket;
	}

  public function connect() {

		$result = socket_connect($this->socket, $this->server, $this->port);
         if ($result < 0) {
		  $this->quicklog("socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n<br>");
          exit;
         }

   }


	/**
	 * send data to socket
	**/
    public function send_data($string) {
		if ($this->socket == NULL) {
			$this->quicklog("Socket::socket error");
			return false;
		}
		$fwrite = 0;
        $this-> write($this->socket , $string);
		return $sent;
	}
 	/**
	 * write data to socket
	**/
	public function  write($st , $message) {

	  $srt = socket_write($st, $message, strlen($message));
	  if($srt < 0 ){
		$this->quicklog("Socket::write socket -error");
		return false;
	    }else{
		return true;
		}
	}


	/**
	 * receive data from socket
	 * params
	 * $bufsize - int, length of data to recieve from socket
	 * return $buf - string, return data recived from socket
	**/
    public function receive_data() {
		if ($this->socket == NULL) {
			$this->quicklog("Socket::receive_data - error in socket");
			return '';
		}
          while ($out =  $this->read($this->socket) ) {
           echo $out;
         }

	}

	/**
	 * send data to socket
	 * params
	 * $buf - string, checks for data sent
	 * return $sent, length of data sent to socket
	**/
    public function send_data_all($buf) {

		if ($this->socket == NULL) {
			$this->quicklog("Socket::send_data_all - error in socket");
			return 0;
		}

        $total = strlen($buf);
        $sent = 0;

        while ($sent < $total) {
            $sent = $sent + $this->send_data(substr($buf, $sent));
		}
        return $sent;
	}

	/**
	 * send data line to socket, concatenate eol to data
	**/
    public function send_data_line($line) {
		if ($this->socket == NULL) {
			$this->quicklog("Socket::send_data_line - error in socket, $line");
			return '';
		}

        $sent = $this->send_data_all($line . $this->eol);
		return $sent;
	}


	/**
	 * read data from socket
	**/
	public function  read($st) {
	  if($st < 0 ){
		$this->quicklog("Socket::read socket error");
	    }else{
       $buf = '';
       $buf = socket_read($st, 2048, PHP_BINARY_READ);
		return $buf;
		}
	}


	/**
	 * ccloses socket connection
	**/
    public function close() {
		$this->quicklog("Socket::break_ - start");
		if ($this->socket == NULL) {
			$this->quicklog("Socket::break_ - error in socket");
			return;
		}
        socket_close($this->socket);
        $this->socket = NULL;
	}

	/**
	 * log Socket messages
	 * params
	 * $message - string, debug message
	**/
	private function quicklog($message) {
		if ($this->debug !== true) {
			return;
		}
		print "$message<br>";
	}
}

//结束
?>
