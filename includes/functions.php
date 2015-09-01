<?php
	function __autoload($class_name) {
		include(LIB_PATH.strtolower($class_name).".php");
	}
	
	function verify_email($email)
	{
		return preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$email);
	}
	
	function verify_username($username)
	{
		return preg_match('/^(?=[^\._]+[\._]?[^\._]+$)[\w\.]{6,15}$/',$username);
	}
	
	function verify_password($password)
	{
		return preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,15}$/',$password);
	}

	function verify_firstname($firstname)
	{
		return preg_match('/^[a-zA-Z]+$/',$firstname);
	}

	function verify_lastname($lastname)
	{
		return preg_match('/^[a-zA-Z]+$/',$lastname) || $lastname == '';
	}
