<?php
class IndexControl extends Control
{
	/**
	 * 主页
	*/
	public function main(){

		$this->view->display('index',$this->view->data);

	}

	/**
	 * 系统登录
	*/
	public function login(){

		$this->view->display('login','');
	}

  	/**
	 * 用户注册
	*/
	public function register(){

		$this->view->display('register','');
	}
}