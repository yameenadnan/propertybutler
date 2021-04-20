<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bms_encryption extends CI_Controller {
    
    function __construct () { 
        parent::__construct ();
        
    }
    
    function test () {
        $str1 = 'Nagarajan';
        $str2 = 'Perumal';
        
        // place this function into a hepler file and call from there
        function generate_random_password($length = 32) {
            $alphabets = range('A', 'Z');
            $numbers = range('0', '9');
            $additional_characters = array('_', '.');
            $final_array = array_merge($alphabets, $numbers, $additional_characters);
            $passwordnew = '';
            while ($length--) {
                $key = array_rand($final_array);
                $passwordnew .= $final_array[$key];
            }
            return $passwordnew;
        }
        // pre setup
        echo "<br />".$key = generate_random_password(32) ;        
        echo "<br />".$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        echo "<br />".$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        // encrypt str 1
        $ciphertextstr1 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$str1, MCRYPT_MODE_CBC, $iv);
        $ciphertextstr1 = $iv . $ciphertextstr1;
        echo "<br />str 1=> ".$str1_data = base64_encode($ciphertextstr1);
        
        // encrypt str 2
        $ciphertextstr2 = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$str2, MCRYPT_MODE_CBC, $iv);
        $ciphertextstr2 = $iv . $ciphertextstr2;
        echo "<br />str 2=> ".$str2_data = base64_encode($ciphertextstr2);
        
        /** // should store the below variables in DB for decryption 
        'keytype'=>$key,               
               'ivtype'=>$iv,
               'ivsize'=>$iv_size,
        */
        
        // decrypt str 1
        $ciphertext_str1 = base64_decode($str1_data);
        $iv_str1 = substr($ciphertext_str1, 0, $iv_size);
        $ciphertext_str1 = substr($ciphertext_str1, $iv_size); 
        echo "<br /><br />str 1=> ".$plaintext_str1 = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_str1, MCRYPT_MODE_CBC, $iv_str1);
        
        // decrypt str 2
        $ciphertext_str2 = base64_decode($str2_data);
        //$iv_str2 = substr($ciphertext_str2, 0, $iv_size); // second time not required
        $ciphertext_str2 = substr($ciphertext_str2, $iv_size); 
        echo "<br />str 2=> ".$plaintext_str2 = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_str2, MCRYPT_MODE_CBC, $iv_str1);
        
        
    }
}