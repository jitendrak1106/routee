<?php
class Current {
    private $weatherApiKey;
    private $appID;
    private $secret;

    public function __construct($weatherKey, $appID, $secret) {
        $this->weatherApiKey = $weatherKey;
        $this->appID = $appID;
        $this->secret = $secret;
    }

    /**
     * get the wather data from API
     */
    public function getWeather($city) {
        $temp = null;

        $weatherApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$city&units=metric&appid=".$this->weatherApiKey;
        
        // Call API and get the weather data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $weatherApiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        if(isset($data->main->temp)) {
            $temp = $data->main->temp;
        }

        return $temp;
    }

    /**
     * Call Routee SMS API
     */
    public function routeeApi($url, $postFields, $header) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
                                        CURLOPT_URL => $url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "POST",
                                        CURLOPT_POSTFIELDS => $postFields,
                                        CURLOPT_HTTPHEADER => $header,
                                    )
                        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response);
            return $data;
        }
    }


    /**
     * Get the access token
     */
    public function getAccessToken() {
        $token = "";

        $data = $this->routeeApi("https://auth.routee.net/oauth/token",
                                "grant_type=client_credentials",
                                array(
                                    "authorization: Basic NWY5MTM4Mjg4YjcxZGUzNjE3YTg3Y2QzOlJTajY5akxvd0o=",
                                    "content-type: application/x-www-form-urlencoded"
                                ));

        if($data){
            $token = $data->access_token;
        }

        return $token;
    }

    /**
     * Send SMS
     */
    public function deliverSMS($message) {
        $token = $this->getAccessToken();

        if($token) {
            $data = $this->routeeApi("https://connect.routee.net/sms",
                                        "{ \"body\": \"$message\",\"to\" : \"+306978745957\",\"from\": \"amdTelecom\"}",
                                        array(
                                            "authorization: Bearer $token",
                                            "content-type: application/json"
                                        ));
            if($data && $data->trackingId) {
                echo 'SMS Sent with tracking ID '.$data->trackingId;
            }
        }

    }

    /**
     * Send SMS based on temperature value
     */
    public function sendSMS($temp) {
        if($temp > 20){
            $message = "Jitendra K and Temperature more than 20C. ".$temp;            
        } else {
            $message = "Jitendra K and Temperature less than 20C. ".$temp;
        }

        $this->deliverSMS($message);
    }

}

$object = new Current('b385aa7d4e568152288b3c9f5c2458a5', '5f9138288b71de3617a87cd3', 'RSj69jLowJ');

$temp = $object->getWeather('Thessaloniki');

if(!empty($temp)) {
    $object->sendSMS($temp);
}
