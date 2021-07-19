<?php

	$config = array(

		/*
			Twitch Configuration
		*/
			"twitch" => array(
				/*
					Set the URL of the Twitch Channel to Check
					The full URL should be provided - including the preceding "https://"
				*/
				"url" => "https://twitch.tv/twitch"
			)

		/*
			PhantomJS Configuration
		*/
			, "phantomjs" => array(
				/*
					Define whether or not to export "OPENSSL_CONF=/dev/null"
					Options:
						true
						false
					Defaults to false.
				*/
				"putenv" => false

				/*
					Script Parameters
				*/
				, "script" => array(
					/*
						Set the path to the PhantomJS script.
						This should not need to be changed.
					*/
					"path" => INC_PATH . "/phantomjs_script.js"

					/*
						Set arguments to pass to the PhantomJS script.
						By default, pass 'output.png "window width 800px, clipped to 800x800"'.
						This should not need to be changed.
					*/
					, "arguments" => 'output.png "window width 800px, clipped to 800x800"'
				)
			)

		/*
			Dependencies
		*/
		, "dependencies" => array(
				/*
					Optional: Specify the full path to the phantomjs client
							  i.e.: /usr/bin/phantomjs
				*/
				"phantomjs" => BIN_PATH . "/phantomjs"
			)

		/*
			Process Locking
		*/
		, "process_lock" => array(
				/*
					Options:
						true
						false
					Default: false
				*/
				"enabled" => false
				/*
					Options:
						true
						false
					Default: true
				*/
				, "deletion" => true
				, "directory" => TEMP_PATH
				, "filename" => "twitch_live_checker.lock"
			)

		/*
			Enable/Disable Debug Mode
			Slighty more verbose output.
		*/
		, "debug_mode" => false

	);

?>
