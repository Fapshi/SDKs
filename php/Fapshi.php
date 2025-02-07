<?php 
class Fapshi {

    const BASEURL = 'https://live.fapshi.com';
    const HEADERS = array(
        'apiuser: replace_me_with_apiuser',
        'apikey: replace_me_with_apikey',
        'Content-Type: application/json'
    );
    const ERRORS = array(
        'invalid type, string expected',
        'invalid type, array expected',
        'amount required',
        'amount must be of type integer',
        'amount cannot be less than 100 XAF',
    );


    public function initiate_pay(array $data) : array {
        if(!is_array($data)){
            $error = array('message'=>Fapshi::ERRORS[1],'statusCode'=>400);
        }
        else if(!array_key_exists('amount', $data)){
            $error = array('message'=>Fapshi::ERRORS[2],'statusCode'=>400);
        }
        else if(!is_int($data['amount'])){
            $error = array('message'=>Fapshi::ERRORS[3],'statusCode'=>400);
        }
        else if($data['amount']<100){
            $error = array('message'=>Fapshi::ERRORS[4],'statusCode'=>400);
        }
        if(isset($error)){
            return $error;
        }

        $url = Fapshi::BASEURL.'/initiate-pay';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }
    

    public function direct_pay(array $data) : array {
        if(!is_array($data)){
            $error = array('message'=>Fapshi::ERRORS[0],'statusCode'=>400);
        }
        else if(!array_key_exists('amount', $data)){
            $error = array('message'=>Fapshi::ERRORS[2],'statusCode'=>400);
        }
        else if(!is_int($data['amount'])){
            $error = array('message'=>Fapshi::ERRORS[3],'statusCode'=>400);
        }
        else if($data['amount']<100){
            $error = array('message'=>Fapshi::ERRORS[4],'statusCode'=>400);
        }
        else if(!array_key_exists('phone', $data)){
            $error = array('message'=>'phone number required','statusCode'=>400);
        }
        else if(!is_string($data['phone'])){
            $error = array('message'=>'phone must be of type string','statusCode'=>400);
        }
        else if(!preg_match('/^6[0-9]{8}$/', $data['phone'])){
            $error = array('message'=>'invalid phone number','statusCode'=>400);
        }
        if(isset($error)){
            return $error;
        }

        $url = Fapshi::BASEURL.'/direct-pay';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }


    public function payment_status(string $transId) : array {
        if(!is_string($transId) || empty($transId)){
            return array('message'=>Fapshi::ERRORS[0],'statusCode'=>400);
        }
        if(!preg_match('/^[a-zA-Z0-9]{8,10}$/', $transId)){
            return array('message'=>'invalid transaction id','statusCode'=>400);
        }

        $url = Fapshi::BASEURL.'/payment-status/'.$transId;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }


    public function expire_pay(string $transId) : array {
        if(!is_string($transId) || empty($transId)){
            return array('message'=>Fapshi::ERRORS[0],'statusCode'=>400);
        }
        if(!preg_match('/^[a-zA-Z0-9]{8,10}$/', $transId)){
            return array('message'=>'invalid transaction id','statusCode'=>400);
        }

        $data = array('transId'=> $transId);
        $url = Fapshi::BASEURL.'/expire-pay';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }


    public function get_user_trans(string $userId) : array {
        if(!is_string($userId) || empty($userId)){
            return array('message'=>Fapshi::ERRORS[0],'statusCode'=>400);
        }
        if(!preg_match('/^[a-zA-Z0-9-_]{1,100}$/', $userId)){
            return array('message'=>'invalid user id','statusCode'=>400);
        }

        $url = Fapshi::BASEURL.'/transaction/'.$userId;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);
        return $response;
    }

    
    public function balance() : array {
        $url = Fapshi::BASEURL.'/balance';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }


    public function payout(array $data) : array {
        if(!is_array($data)){
            $error = array('message'=>Fapshi::ERRORS[0],'statusCode'=>400);
        }
        else if(!array_key_exists('amount', $data)){
            $error = array('message'=>Fapshi::ERRORS[2],'statusCode'=>400);
        }
        else if(!is_int($data['amount'])){
            $error = array('message'=>Fapshi::ERRORS[3],'statusCode'=>400);
        }
        else if($data['amount']<100){
            $error = array('message'=>Fapshi::ERRORS[4],'statusCode'=>400);
        }
        else if(!array_key_exists('phone', $data)){
            $error = array('message'=>'phone number required','statusCode'=>400);
        }
        else if(!is_string($data['phone'])){
            $error = array('message'=>'phone must be of type string','statusCode'=>400);
        }
        else if(!preg_match('/^6[0-9]{8}$/', $data['phone'])){
            $error = array('message'=>'invalid phone number','statusCode'=>400);
        }
        if(isset($error)){
            return $error;
        }

        $url = Fapshi::BASEURL.'/payout';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $response['statusCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }


    public function search(array $params) : array {

        $url = Fapshi::BASEURL.'/search?'.http_build_query($params);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => Fapshi::HEADERS,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);
        return $response;
    }

}
