<?php
class DoModel extends DB
{


	public function __construct()
	{
		parent::__construct();

	}

    public function getuser(){
    /*
      $fieldarr = array();
	  $fieldarr['mg_id'] = '2';
	  $fieldarr['mg_name'] = 'chenxaing';
      echo $this->orm->find($fieldarr,'manager', 'mg_id','','3,10');
    */
      $result  = $this->db->query('select * from passport_users');
      $ac = array();
      while ($res =  $this->db->fetchrow( $result )){
       dump($res);
       }
      $this->db->freeresult($result);

	}

}