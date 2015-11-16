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

//socket server 服务器

class YO_socket_server {

	private $socket = NULL;
	private $server = NULL;
	private $port = null;
	private $eol = "\r\n";
	private $debug = true;
	private $MaxQueue;

	// generic tcp Socket wrapper
	/**
	**/
    public function __construct($server, $port, $eol = NULL) {
	    set_time_limit(0);
        $this->socket = NULL;
        $this->server = $server;
		$this->port = $port;
        $this->MaxQueue = 10;
		if ($eol !== NULL) {
			$this->eol = $eol;
		}
	}

	/**
	 * return eol
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
			$this->quicklog("Socket::establish - Socket already created\n\r");
			return $this->socket;
		}

		$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($s){
		$this->quicklog("Socket::establish - Socket creating success\n\r");
		}else{
		$this->quicklog("Socket::establish - Socket creating fails\n\r");
		}

		$server = $this->server;
		$port = $this->port;

		$serverIP = gethostbyname($server);

		$this->quicklog("Socket::establish - server: $server, port: $port, serverIP: $serverIP\n\r");


       if (($status = socket_bind($s, $serverIP, $port)) < 0) {
		    $this->quicklog("socket_bind() failed: reason: " . socket_strerror($ret) . "\n\r");
         exit;
         }else{
		    $this->socket = $s; //监听属性
            $this->quicklog("Socket::socket_bind ok\n\r ");
        }

        return $this->socket;
	}

  	/**
	 * listen
	**/
  public function listen() {
       if (($ret = socket_listen($this->socket, $this->MaxQueue)) < 0) {
		  $this->quicklog("socket_listen() failed: reason: " . socket_strerror($ret) . "\n\r ");
          exit;
      }else{
		 $this->quicklog("socket_listen ok\n\r");
        }

   }
   	/**
	 * receive data select module
	**/
    public function receive_data_select($bufsize) {
		$this->quicklog("Socket::receive_data - start\n\r");
		if ($this->socket == NULL) {
			$this->quicklog("Socket::receive_data - error in socket\n\r");
			return '';
		}
		$buf = '';
        socket_recv($this->socket, $buf, $bufsize, MSG_WAITALL);
		$this->quicklog("Socket::receive_data - end, $buf");
		return $buf;
	}
	/**
	 * receive data from socket
	 * params
	 * return $buf - string, return data recived from socket
	**/
    public function receive_data() {
		$this->quicklog("Socket::receive_data - start\n\r");
		if ($this->socket == NULL) {
			$this->quicklog("Socket::receive_data - error in socket\n\r");
			return '';
		}
		while (true){
          echo "recept : ".$num."\n\r ";
          if (($msgsock = socket_accept($this->socket)) < 0) {
           echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
          }else{
           $buf =$this-> read($msgsock);
           /* Send instructions. */
		   $this-> write($msgsock , $buf);
           socket_close($msgsock);
           $num++;
         }

	}



	/**
	 * write to socket
	**/

	public function  write($st , $message) {

	  $srt = socket_write($st, $message, strlen($message));
	  if($srt < 0 ){
		$this->quicklog("Socket::write socket error\n\r");
		return false;
	    }
	}


	/**
	 * read data from socket
	**/
	public function  read($st) {
	  if($st < 0 ){
		$this->quicklog("Socket::read socket error\n\r");
	    }else{
       $buf = '';
       $buf = socket_read($st, 20480, PHP_BINARY_READ);
		return $buf;
		}
	}

	/**
	 * recieves one line
	 * params
	 * return
	 * $buf - string, returns one line from socket read
	**/
    public function receive_data_line() {
		$this->quicklog("Socket::receive_data_line - started\n\r");
		if ($this->socket == NULL) {
			$this->quicklog("Socket::receive_data_line - error in socket\n\r");
			return '';
		}

		$cnt = 0;
        $buf = '';

        while (true) {
            $in_byte = $this->receive_data_select(1);
            if ($in_byte == '') {
                return NULL;
			}

            if ($in_byte == "\r") {
                $cnt = 1;
			}
            elseif ($in_byte == "\n" && $cnt == 1) {
                $cnt = 2;
			}
            else {
                $cnt = 0;
			}
			$buf .= $in_byte;
            if ($cnt == 2 || strrpos($buf, $this->eol) !== false) {
				$this->quicklog("Socket::receive_data_line - data: $buf");
                return $buf;
			}
		}


	}

	/**
	 * ccloses socket connection
	 * params
	**/
    public function close() {
		$this->quicklog("Socket::break_ - start\n\r");
		if ($this->socket == NULL) {
			$this->quicklog("Socket::break_ - error in socket\n\r");
			return;
		}
        socket_close($this->socket);
        $this->socket = NULL;
		$this->quicklog("Socket::break_ - end\n\r");
	}


	/**
	 * read data from socket
	 * params
	 * $waitSecs - int, wait for data befre returning
	 * return $buf - string, return data recived from socket
	**/
	public function  read_select($waitSecs = NULL) {
		$this->quicklog("Socket::read - start\n\r");

		$buf = '';
		if ($this->socket == NULL) {
			break;
		}
		$read = array($this->socket);
		$write = array();
		$except = array();

		$updated =socket_select($read, $write, $except, $waitSecs);

		if ($updated > 0){
			$this->receive_data();
		}

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
		print "$message";
	}

	/**
	 * hander a function
	**/
    public function  hander($function ) {

        if($function){
		   call_user_func($function);
		  }


	}

}

//结束
?>
