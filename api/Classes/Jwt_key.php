<?php
	require_once("./../../libs/JWT.php");

    class JWT_Key {

        private $secret_key = "H8JDA7L4IAL4901J5NKLSFG32104NGSO2";

        public function encodeToken($data) {
        	return JWT::encode($data, $this->secret_key);
        }

        public function decodeToken($token) {
            $tokenArray = [];
            try {
                $tokenArray = JWT::decode($token, $this->secret_key);
                return $tokenArray;
            }catch(Exception $e) {
                return null;
            }
            
        }


    }