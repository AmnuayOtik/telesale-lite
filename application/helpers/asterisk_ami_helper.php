<?php

function makeCallHelper($internalPhoneline = null, $target = null)
{
    $ami_host = "192.168.98.150";  // IP ของ Asterisk
    $port = 5038;
    $username = "otikadmin2025";
    $password = "P@ssw0rd##";
    $context = "DLPN_DialPlan" . $internalPhoneline;

    $response = [
        'status' => false,
        'message' => '',
        'channel' => null,
        'raw_response' => ''
    ];

    if (!$internalPhoneline || !$target) {
        $response['message'] = "Missing internal phone line or target number.";
        return $response;
    }

    $socket = @stream_socket_client("tcp://$ami_host:$port", $errno, $errstr, 5);
    if (!$socket) {
        $response['message'] = "Unable to connect to socket: $errstr ($errno)";
        return $response;
    }

    // Login
    $authenticationRequest = "Action: Login\r\n";
    $authenticationRequest .= "Username: $username\r\n";
    $authenticationRequest .= "Secret: $password\r\n";
    $authenticationRequest .= "Events: off\r\n\r\n";

    if (stream_socket_sendto($socket, $authenticationRequest) <= 0) {
        $response['message'] = "Could not write authentication request to socket.";
        return $response;
    }

    usleep(200000);
    $authenticateResponse = fread($socket, 4096);

    if (strpos($authenticateResponse, 'Success') === false) {
        $response['message'] = "Authentication to AMI failed.";
        $response['raw_response'] = $authenticateResponse;
        return $response;
    }

    // Originate call
    $originateRequest = "Action: Originate\r\n";
    $originateRequest .= "Channel: PJSIP/$internalPhoneline\r\n";
    $originateRequest .= "Callerid: Click 2 Call\r\n";
    $originateRequest .= "Exten: $target\r\n";
    $originateRequest .= "Context: $context\r\n";
    //$originateRequest .= "Variable: PJSIP_HEADER(add,Alert-Info)=;answer-after=0\r\n";
    $originateRequest .= "Variable: PJSIP_HEADER(add,Call-Info)=<sip:autoanswer=yes>\r\n";
    //$originateRequest .= "Variable: PJSIP_HEADER=Call-Info: <sip:autoanswer=yes>\r\n";
    $originateRequest .= "Priority: 1\r\n";
    $originateRequest .= "Async: yes\r\n\r\n";

    if (stream_socket_sendto($socket, $originateRequest) <= 0) {
        $response['message'] = "Could not send call request.";
        return $response;
    }

    usleep(200000);
    $originateResponse = fread($socket, 4096);
    $response['raw_response'] = $originateResponse;

    // Extract channel id if available
    preg_match("/Channel: (.+)/", $originateResponse, $matches);
    if (isset($matches[1])) {
        $response['channel'] = trim($matches[1]);
    }

    if (strpos($originateResponse, 'Success') !== false) {
        $response['status'] = true;
        $response['message'] = "Call initiated successfully.";
    } else {
        // Try to extract message line from AMI response
        if (preg_match("/Message: (.+)/", $originateResponse, $msgMatch)) {
            $response['message'] = trim($msgMatch[1]);
        } else {
            $response['message'] = "Could not initiate call.";
        }
    }

    fclose($socket);
    return $response;
}
