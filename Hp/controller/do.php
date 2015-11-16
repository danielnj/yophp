<?php
class DoControl extends Control
{

    public function test(){

      print_c('测试主页');

	}

	public function php(){

		$this->view = $this->load->load_view('php');
        $this->view->data['name']="chenxiang";
		$this->view->display('test',$this->view->data);

	}
	public function smarty(){

		$this->view = $this->load->load_view('smarty');
		$test_array = array('1','2','3','4','5','6','7');
        $this->view->assign('value', $test_array);
        $this->view->display('test.html');
	}

    public function mysql(){

      print_c('Mysql数据库连接测试');
      $this->db = $this->load->load_model('do');
	  $this->db->getuser();
	}

}