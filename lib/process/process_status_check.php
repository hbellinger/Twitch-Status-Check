<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("process_status_check"))
	{
		function process_status_check($input, $debug)
		{
			/*
			 * Input validation.
			 */
			if(!is_object($input))
			{
				throw new Exception ("Invalid input provided to function. - [process_status_check]");

				return false;
			}

			/*
			 * Construct Array for Usage
			 */
			$dom_objects = array();

			/*
			 * https://stackoverflow.com/a/31796119/5812026
			 * This is a check to look for elements containing a specific string of text.
			 */
			$page_scripts = $input->find("script");
			foreach($page_scripts as $row)
			{
				if(strpos($row->innertext, '"@type":"VideoObject"') == true)
				{
					array_push($dom_objects, $row->innertext);
				}
				if(strpos($row->innertext, '"isLiveBroadcast":true') == true)
				{
					array_push($dom_objects, $row->innertext);
				}
			}

			/*
			 * Having trouble?
			 * Amend the configuration to set ["debug_mode"] to true.
			 */
			if($debug === true)
			{
				var_dump($dom_objects);
				// print_r($dom_objects);
			}

			if((count($dom_objects) > (int) 0) === true)
			{
				// Channel is Live.
				$status = (bool) true;
				return $status;
			}
			else
			{
				/*
				 * Channel Status is Indeterminate.
				 * Fallback to less accurate, extremely inconsistent measures.
				 */
			}

			/*
			 * These need to be wiped.
			 * Unset them and start over.
			*/
			unset($page_scripts);
			unset($dom_objects);
			unset($row);

			/*
			 * Construct Array for Usage
			 * (Try again)
			*/
			$dom_objects = array();

			/*
			 * https://stackoverflow.com/a/37627269
			 * https://stackoverflow.com/questions/15761115/find-div-with-class-using-php-simple-html-dom-parser
			 *
			 * This object is the avatar. Below it, Twitch shows "LIVE" if the channel is actively streaming.
			*/
			foreach($input->find("div[class*=ScChannelStatusTextIndicator]") as $row)
			{
				array_push($dom_objects, $row->innertext);
			}

			foreach($input->find("div[class*=live-indicator-container]") as $row)
			{
				array_push($dom_objects, $row->innertext);
			}
			foreach($input->find("div[class*=tw-channel-status-text-indicator]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}
			foreach($input->find("div[class*=channel-status-info]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			/*
			 * When a Twitch channel is offline, but hosting another, Twitch presents two elements:
			 * 1) A <div> element with a class of "sc-AxjAm idVdfv channel-status-info channel-status-info--hosting".
			 *    This <div> element contains a <p> element with a class of "sc-AxirZ hWxZyu" and the text "Hosting".
			 * 2) A <div> element with a class of "ScChannelStatusTextIndicator-sc-1f5ghgf-0 YoxeW tw-channel-status-text-indicator".
			 *    This <div> element contains a <p> element with a class of "sc-AxirZ gOlXkb" and the text "LIVE".
			 * Because the second noted <div> element is a sub element of the <div> element with a class of "sc-AxjAm hBZJQK", it will be found in the list
			 * of DOM objects. Accordingly, this will be a secondary check to see if the channel is hosting, or even explitly offline.
			 */
			foreach($input->find("div[class*=channel-status-info--hosting]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			foreach($input->find("div[class=sc-AxjAm qTZAo]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			/*
			 * This is an explicit check for an element containing "channel-status-info".
			 * When a channel is actually offline, such tends to show here.
			 */
			foreach($input->find("div[class*=channel-status-info]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}
			foreach($input->find("div[class=channel-status-info]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			/*
			 * This object is the entire channel info section, and represents a far more aggresive check.
			 */
			foreach($input->find("div[class=channel-info-content]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			/*
			 * 2025-06-29: Twitch modified their design.
			 */
			foreach($input->find("div[class=liveIndicator]") as $row)
			{
				array_push($dom_objects, $row->plaintext);
			}

			/*
			 * Having trouble?
			 * Amend the configuration to set ["debug_mode"] to true.
			 */
			if($debug === true)
			{
				var_dump($dom_objects);
				// print_r($dom_objects);
			}

			if(in_array("Offline", $dom_objects) || in_array("OFFLINE", $dom_objects))
			{
				// Channel is Offline.
				$status = (bool) false;
			}
			elseif(in_array("Hosting", $dom_objects))
			{
				// Channel is Not Live.
				$status = (bool) false;
			}
			elseif(in_array("Live", $dom_objects) || in_array("LIVE", $dom_objects))
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
