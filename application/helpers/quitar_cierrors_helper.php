<?php 
	function errorException_handlers($proceso, $errorHandlers='', $exceptionHandlers=''){
		if ($proceso=='eliminar') {
			$errorHandlers=array();
			do {
				$errorHandler=set_error_handler(function (){});
				array_push($errorHandlers, $errorHandler);
				for ($i=0; $i < 2; $i++) { 
					restore_error_handler();
				}
			} while (!is_null($errorHandler));

			$exceptionHandlers=array();
			do {
				$exceptionHandler=set_exception_handler(function (){});
				array_push($exceptionHandlers, $exceptionHandler);
				for ($i=0; $i < 2; $i++) { 
					restore_exception_handler();
				}
			} while (!is_null($exceptionHandler));

			return array($errorHandlers, $exceptionHandlers);
		}else{
			foreach ($errorHandlers as $errorHandler) {
				if (isset($errorHandler)) {
					set_error_handler($errorHandler);
				}
			}

			foreach ($exceptionHandlers as $exceptionHandler) {
				if (isset($exceptionHandler)) {
					set_exception_handler($exceptionHandler);
				}
			}
		}
	}