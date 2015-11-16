<?php
 /**
 * @Copyright (C) 2009 - 2010 http://www.yophp.com All rights reserved
 * @License http://www.apache.org/licenses/LICENSE-2.0
 *
 * @Name soap.php
 *
 * @Author Chen Xiang <chaily208@163.com> Initial.
 * @Since 2011/2/1
 * @Version $Id:$
 */


class YO_shell {



  /* Constractor */
  function __construct() {}

  //SOAP 专用接口
  function Httpsoap($server, $port, $url, $namespace, $action, $data) {
	$fp = @fsockopen($server, $port);
	if (!$fp) {
		return FALSE;
	} else {
		$soapData = ConstructData($namespace, $action, $data);
		$length = strlen($soapData);

		$out = "POST $url HTTP/1.1\r\n";
		$out .= "Host: $server\r\n";
		$out .= "Content-Type: text/xml; charset=utf-8\r\n";
		$out .= "Content-Length: $length\r\n";
		$out .= "SOAPAction: \"$namespace$action\"\r\n\r\n";
		$out .= $soapData;
		$out .= "\r\n\r\n";

		fputs($fp, $out);
		stream_set_timeout($fp, 2);

		$header = "";
		while($line = trim(fgets($fp))) {
			$header .= $line."\n";
		}
		$dataPos = strpos($header, "Content-Length: ") + 16;
		$dataEnd = strpos($header, "\n", $dataPos);
		$dataLength = substr($header, $dataPos, $dataEnd - $dataPos);
		$data = "";
		if($dataLength > 0) {
			$data = fread($fp, $dataLength);
		}
		fclose($fp);
		if(strlen($data) != $dataLength || $dataLength <= 0) {
			return FALSE;
		}
		return $data;
	}
}



function Constructdata($namespace, $action, $data) {
	$soapData = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	$soapData .= "<soap:Envelope
xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n";
	$soapData .= " <soap:Body>\r\n";
	$soapData .= " <$action xmlns=\"$namespace\">\r\n";
	foreach($data as $name => $value) {
		$name = iconv("GBK","UTF-8",$name);
		$value= iconv("GBK","UTF-8",$value);
		$soapData .= " <$name>$value</$name>\r\n";
	}
	$soapData .= " </$action>\r\n";
	$soapData .= " </soap:Body>\r\n";
	$soapData .= "</soap:Envelope>";

	return $soapData;
}

}
?>