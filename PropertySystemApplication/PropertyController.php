<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    class PropertyController {
        public function fetchPropertiesFromMasterLibrary()
        {
            $curlSession = curl_init();
            curl_setopt($curlSession, CURLOPT_URL, "http://trialapi.craig.mtcdevserver.com/api/properties?api_key=3NLTTNlXsi6rBWl7nYGluOdkl2htFHug");
            curl_setopt($curlSession,CURLOPT_HEADER, false); 
            curl_exec($curlSession);
            curl_close($curlSession);
        }
    }
?>