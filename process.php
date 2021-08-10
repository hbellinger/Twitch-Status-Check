<?php

	/*
		Define named constants.
		http://php.net/manual/en/function.define.php
	*/

	define('BASE_PATH', dirname(__FILE__));
	define('BIN_PATH', BASE_PATH . "/bin");
	define('CLS_PATH', BASE_PATH . "/cls");
	define('INC_PATH', BASE_PATH . "/inc");
	define('LIB_PATH', BASE_PATH . "/lib");
	define('RES_PATH', BASE_PATH . "/res");
	define('TEMP_PATH', sys_get_temp_dir());

	require(RES_PATH . "/config.inc.php");
	require(CLS_PATH . "/simplehtmldom/simple_html_dom.php");

	require(LIB_PATH . "/error/exception_cleanup.php");
	require(LIB_PATH . "/error/exception_error_output.php");
	require(LIB_PATH . "/error/exception_handler.php");
	require(LIB_PATH . "/guid/generate_guid.php");
	require(LIB_PATH . "/lock/obtain_exclusive_lock.php");
	require(LIB_PATH . "/lock/release_exclusive_lock.php");

	require(LIB_PATH . "/phantomjs/phantomjs_verify_install.php");
	require(LIB_PATH . "/phantomjs/phantomjs_get_page.php");

	require(LIB_PATH . "/parse/filter_body_to_dom.php");
	require(LIB_PATH . "/process/process_status_check.php");

	/*
		Generate a GUID.
	*/
	$guid = generate_guid();

	/*
		Open pid file and obtain an exclusive lock.
	*/
	if($config["process_lock"]["enabled"] === true)
	{
		try
		{
			print "[$guid] Obtaining a lock." . PHP_EOL;
			$lock_file_handle = obtain_exclusive_lock($config["process_lock"]["directory"], $config["process_lock"]["filename"]);
		}
		catch (Exception $e)
		{
			if($config["debug_mode"] === true)
			{
				exception_error_output($e, $guid);
			}

			exit();
		}
	}

	/*
		Verify dependencies
	*/
	try
	{
		/*
			Verify that the PhantomJS client is available
		*/
		if(phantomjs_verify_install($config["dependencies"]["phantomjs"]) === false)
		{
			if(empty($config["dependencies"]["phantomjs"]) === false)
			{
				trigger_error("The PhantomJS client does not appear to be installed at the specified location of \"" . $config["dependencies"]["phantomjs"] . "\". Please install the client, or provide the correct full path to the PhantomJS client's location." . PHP_EOL, E_USER_ERROR);
			}
			else
			{
				trigger_error("The PhantomJS client does not appear to be installed. Please install the client, or provide the full path to the PhantomJS client's location." . PHP_EOL, E_USER_ERROR);
			}
		}
	}
	catch (Exception $e)
	{
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Retrieve Twitch User Page for Processing
	*/
	try
	{
		print "[$guid] Retrieving Twitch Page for Processing." . PHP_EOL;
		print "[$guid] URL: " . $config["twitch"]["url"] . PHP_EOL;
		$phantomjs = phantomjs_get_page($config["phantomjs"]["putenv"], $config["dependencies"]["phantomjs"], $config["phantomjs"]["script"]["path"], $config["twitch"]["url"], $config["phantomjs"]["script"]["arguments"]);
		//$phantomjs = file_get_contents(BASE_PATH . "/tst/page_test_live.html");
		//$phantomjs = file_get_contents(BASE_PATH . "/tst/page_test_down.html");

		if($config["debug_mode"] === true)
		{
			// var_dump($phantomjs);
		}
	}
	catch (Exception $e)
	{
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Retrieve Twitch User Page for Processing
	*/
	try
	{
		print "[$guid] Retrieving DOM." . PHP_EOL;
		$dom = filter_body_to_dom($phantomjs);

		if($config["debug_mode"] === true)
		{
			// Never. Ever. var_dump(). The DOM.
		}
	}
	catch (Exception $e)
	{
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Process Twitch User Page for Stream Status
	*/
	try
	{
		print "[$guid] Parsing DOM for Status." . PHP_EOL;
		$status = process_status_check($dom, $config["debug_mode"]);

		if($config["debug_mode"] === true)
		{
			var_dump($status);
		}
	}
	catch (Exception $e)
	{
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}
	finally
	{
		if(isset($status) === true)
		{
			if($status === true)
			{
				print "[$guid] Status: Online" . PHP_EOL;
			}
			elseif($status === false)
			{
				print "[$guid] Status: Offline" . PHP_EOL;
			}
			else
			{
				print "[$guid] Status: Error" . PHP_EOL;
			}
		}
	}

	/*
		Release lock and close pid file.
	*/
	if($config["process_lock"]["enabled"] === true)
	{
		try
		{
			print "[$guid] Releasing lock." . PHP_EOL;
			release_exclusive_lock($config["process_lock"]["directory"], $config["process_lock"]["filename"], $config["process_lock"]["deletion"], $lock_file_handle);
		}
		catch (Exception $e)
		{
			if($config["debug_mode"] === true)
			{
				exception_error_output($e, $guid);
			}

			exit();
		}
	}

	exit();

?>