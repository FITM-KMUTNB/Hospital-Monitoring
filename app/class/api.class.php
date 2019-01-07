<?php
class Api{
	
	// Error Message on Verify Fail or System Error.
	public function errorMessage($message){
		$data = array(
	      "apiVersion" 	=> "1.0",
	      "message" 	=> $message,
	      "execute" 	=> round(microtime(true)-StTime,4)."s"
	     );
	    
	    // JSON Encode and Echo.
	    echo json_encode($data);
	}

	// Success Message
	public function successMessage($message,$return,$dataset){
		$data = array(
	      "apiVersion" 	=> "1.0",
	      "message" 	=> $message,
	      "return"		=> $return,
	      "execute" 	=> round(microtime(true)-StTime,4)."s",
	      "data" 		=> array(
	      	'items' 		=> array($dataset),
	      ),
	    );
	    
	    // JSON Encode and Echo.
	    echo json_encode($data);
	}

	// Export to json
	public function exportJson($message,$dataset){
		$data = array(
			"apiVersion" => "1.0",
			"data" => array(
				// "update" => time(),
				"time_now" => date('Y-m-d H:i:s'),
				"message" => $message,
				"execute" => round(microtime(true)-StTime,4)."s",
				"totalFeeds" => floatval(count($dataset)),
				"items" => $dataset,
			),
		);

	    // JSON Encode and Echo.
	    echo json_encode($data);
	}
}
?>