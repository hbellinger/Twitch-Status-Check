<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("filter_body_to_dom"))
	{
		/*
			http://php.net/manual/en/functions.user-defined.php
		*/
		function filter_body_to_dom($input)
		{
			// var_dump($input);

			/*
				Input validation
			*/
			if(!is_string($input))
			{
				throw new Exception ("The contents of the provided input are invalid - [filter_body_to_dom]");

				return false;
			}

			/*
				Execute "simplehtmldom"'s str_get_html() function.
			*/
			$html = str_get_html($input);

			// var_dump($html);

			return $html;
			
		}
	}

?>