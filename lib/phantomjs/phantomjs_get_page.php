<?php

	/*
		This function is used to obtain a listing from the specified Amazon S3 Bucket
	*/

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("phantomjs_get_page"))
	{
		function phantomjs_get_page($putenv, $phantomjs_path, $phantomjs_script, $url, $arguments)
		{
			/*
				Test Input.
			*/
			if(empty($putenv) === true && $putenv !== false)
			{
				trigger_error("Missing Declaration for Environmental Variable. Defaulting to \"false\". - [phantomjs_get_page]", E_USER_WARNING);

				/*
					Set $putenv to false
				*/
				$putenv = (bool) false;
			}
			else
			{
				if(is_bool($putenv) === false)
				{
					throw new Exception("Invalid environmental variable declaration. - [phantomjs_get_page]");
				}
			}
			if(empty($phantomjs_script) === true)
			{
				throw new Exception("The path to the Phantom JS script does not appear to have been provided. - [phantomjs_get_page]");
			}
			else
			{
				if(is_string($phantomjs_script) === false)
				{
					throw new Exception("Phantom JS Script: invalid input. - [phantomjs_get_page]");
				}
				if(is_readable($phantomjs_script) === false)
				{
					throw new Exception("Phantom JS Script: Script not readable. - [phantomjs_get_page]");
				}
			}
			if(empty($url) === true)
			{
				throw new Exception("The URL for Phantom JS to check does not appear to have been provided. - [phantomjs_get_page]");
			}
			else
			{
				if(is_string($url) === false)
				{
					throw new Exception("Phantom JS Script: invalid url. - [phantomjs_get_page]");
				}
			}
			if(empty($arguments) === true)
			{
				throw new Exception("The arguments to be passed to the Phantom JS script does not appear to have been provided. - [phantomjs_get_page]");
			}
			else
			{
				if(is_string($arguments) === false)
				{
					throw new Exception("Phantom JS Script: invalid argument format. - [phantomjs_get_page]");
				}
			}

			/*
				As the path to the phantomjs client is optional, it will only be checked if the parameter is not empty.
				Note that the behavior of empty() differs from isset().
				isset() will return as true on a set variable, even if that variable is null.
				empty() will return as true on a set variable, even if it is null or is an empty string.

				https://www.php.net/manual/en/function.empty.php
			*/
			if(empty($phantomjs_path) === false)
			{
				if(is_string($phantomjs_path) === false)
				{
					throw new Exception("The input for the user-provided path to the phantomjs client does not appear to be correct. - [phantomjs_get_page]");
				}
			}
			else
			{
				/*
					Default to "phantomjs" and rely on system $PATH
				*/
				$phantomjs_path = "phantomjs";
			}

			$command = "$phantomjs_path $phantomjs_script '$url' '$arguments'";

			
			/*
				"export OPENSSL_CONF=/dev/null"
				https://www.php.net/manual/en/function.putenv.php
			*/
			if($putenv === true)
			{
				putenv("OPENSSL_CONF=/dev/null"); 
			}

			// trigger_error("Executing PhantomJS - [phantomjs_get_page]", E_USER_NOTICE);
			/*
				Test for the path and confirm whether or not the result is executable.

				https://www.php.net/manual/en/function.escapeshellcmd.php
				https://www.php.net/manual/en/function.shell-exec.php
				https://www.php.net/manual/en/function.is-executable.php
			*/
			$result = shell_exec(escapeshellcmd("$command"));
			// $result = file_get_contents("/data/2019-06.txt");
			// $result = file_get_contents("/data/2019-07.txt");
			// $result = file_get_contents("/data/2019-08.txt");

			/*
				Check to see if the result of "shell_exec" is null.
				The choice to do a type check for NULL is stylistic only.
				is_null is perfectly valid as well.

				https://www.php.net/manual/en/function.is-null.php
				https://www.php.net/manual/en/function.is-null.php#84161
			*/
			if($result === NULL)
			{
				throw new Exception("Returned no result from PhantomJS - [phantomjs_get_page]");
			}
			else
			{
				// trigger_error("Retrieved Page from PhantomJS - [phantomjs_get_page]", E_USER_NOTICE);
				/*
					Return the result from PhantomJS.

					https://www.php.net/manual/en/function.return.php
				*/
				return $result;
			}
		}
	}

?>
