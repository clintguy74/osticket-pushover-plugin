<?php

require_once(INCLUDE_DIR.'class.signal.php');
require_once(INCLUDE_DIR.'class.plugin.php');
require_once('config.php');

class PushoverPlugin extends Plugin {
    var $config_class = "PushoverPluginConfig";
	
	function bootstrap() {		
		Signal::connect('model.created', array($this, 'onTicketCreated'), 'Ticket');		
    }
	
	function onTicketCreated($ticket){
		try {			

			global $ost;
			
			$url = 'https://api.pushover.net/1/messages.json';
			$token = $this->getConfig()->get('pushover-api-token');
			$user = $this->getConfig()->get('pushover-user-key');
			$payload = array( 'token' => $token,
			                  'user' => $user,
							  'title' => "New Ticket <#" . $ticket->getNumber() . "> created",
							  'message' => "Ticket created by " . $ticket->getName() . " (" . $ticket->getEmail() . ")",
							  'url' => $ost->getConfig()->getUrl() . "scp/tickets.php?id=" . $ticket->getId(),
							  'url_title' => 'View full ticket',
			);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
			
			if(curl_exec($ch) === false){
				throw new Exception($url . ' - ' . curl_error($ch));
			}
			else{
				$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if($statusCode != '200'){
					throw new Exception($url . ' Http code: ' . $statusCode);					
				}				
			}
			curl_close($ch);
		}
		catch(Exception $e) {
			error_log('Error posting to Pushover. '. $e->getMessage());
		}
	}	
}