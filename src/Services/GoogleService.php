<?php

namespace App\Services;


class GoogleService
{

    /**
     * @param HttpClient for web service 
     */
    public function __construct()
    {
        
    }

    /**
     * @param string $GoogleService
     * @return mixed
     */
    public function GetLatLong($adresse)
    {
        $api_key = "AIzaSyDP0zWh84W6X9atw_yUTK9Leo1tbLEUBDg" ;
        
        $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($adresse).",+CA&key=".$api_key;

       
     
      $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
        $responseJson = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($responseJson);
    
        return $response ;
        
    
    }


}