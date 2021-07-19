<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("process_status_check"))
	{
		function process_status_check($input)
		{
			/*
				Input validation.
			*/
			if(!is_object($input))
			{
				throw new Exception ("Invalid input provided to function. - [process_status_check]");

				return false;
			}

			/*
				Construct Array for Usage
			*/
			$dom_objects = array();

			/*
				https://stackoverflow.com/a/37627269
				https://stackoverflow.com/questions/15761115/find-div-with-class-using-php-simple-html-dom-parser
			*/
			
			/*
				This object is the avatar. Below it, Twitch shows "LIVE" if the channel is actively streaming.
			*/
			// $i = 0;
			foreach($input->find("div[class=sc-AxjAm hBZJQK]") as $row)
			{
				// print "[$i] - \"" . $row->plaintext . "\"" . PHP_EOL;
				array_push($dom_objects, $row->plaintext);
				// ++$i;
			}

			/*
				When a Twitch channel is offline, but hosting another, Twitch presents two elements:
				1) A <div> element with a class of "sc-AxjAm idVdfv channel-status-info channel-status-info--hosting".
				   This <div> element contains a <p> element with a class of "sc-AxirZ hWxZyu" and the text "Hosting".
				2) A <div> element with a class of "ScChannelStatusTextIndicator-sc-1f5ghgf-0 YoxeW tw-channel-status-text-indicator".
				   This <div> element contains a <p> element with a class of "sc-AxirZ gOlXkb" and the text "LIVE".
				Because the second noted <div> element is a sub element of the <div> element with a class of "sc-AxjAm hBZJQK", it will be found in the list
				of DOM objects. Accordingly, this will be a secondary check to see if the channel is hosting, or even explitly offline.
			*/
			// $i = 0;
			foreach($input->find("div[class=sc-AxjAm qTZAo]") as $row)
			{
				// print "[$i] - \"" . $row->plaintext . "\"" . PHP_EOL;
				array_push($dom_objects, $row->plaintext);
				// ++$i;
			}

			// print_r($dom_objects);
			// var_dump($dom_objects);

			if(in_array("Offline", $dom_objects))
			{
				// Channel is Live!
				$status = (bool) false;
			}
			elseif(in_array("Hosting", $dom_objects))
			{
				// Channel is Not Live.
				$status = (bool) false;
			}
			elseif(in_array("LIVE", $dom_objects))
			{
				// Channel is Live.
				$status = (bool) true;
			}
			else
			{
				// Channel is Not Live.
				$status = (bool) false;
			}

			return $status;

		}
	}
		
?>