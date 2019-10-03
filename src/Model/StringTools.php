<?php
namespace App\Model;

class StringTools
{

    static public function noyes($number)
    {
        if ($number)
            return 'Yes';
        else
            return 'No';
    }

    static public function shortNameFrom($inputText)
    {
        $latName = self::latinOnly(trim($inputText));
        
        if (strlen($latName) > 4)
            $latpart = substr($latName, 0, 4);
        else 
            if (strlen($latName) > 3)
                $latpart = substr($latName, 0, 3);
            else 
                if (strlen($latName) > 2)
                    $latpart = substr($latName, 0, 2);
                else 
                    if (strlen($latName) > 1)
                        $latpart = substr($latName, 0, 1);
        $latpart = strtoupper($latpart);
        
        return $latpart;
    }

    /**
     * Encode array to utf8 recursively
     *
     * @param
     *            $dat
     * @return array|string
     */
    static public function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (! is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = self::array_utf8_encode($d);
        return $ret;
    }

    static public function latinOnly($inputText)
    {
        $okChars = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $inputText = strtolower($inputText);
        // $inputText = str_replace(' ', '_', $inputText);
        
        $output = '';
        
        $tLen = strlen($inputText);
        for ($i = 0; $i < $tLen; $i ++) {
            $poz = strpos($okChars, $inputText[$i]);
            if ($poz > 0) {
                $output = $output . $okChars[$poz];
            }
        }
        return $output;
    }

    static public function alphaOnly($inputText)
    {
        $okChars = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-1234567890';
        $inputText = strtolower($inputText);
        $inputText = str_replace(' ', '_', $inputText);
        
        $output = '';
        
        $tLen = strlen($inputText);
        for ($i = 0; $i < $tLen; $i ++) {
            $poz = strpos($okChars, $inputText[$i]);
            if ($poz > 0) {
                $output = $output . $okChars[$poz];
            }
        }
        return $output;
    } 

    static public function alphaOnly2($inputText)
    {
        $okChars = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_1234567890';
        $inputText = strtolower($inputText);
        $inputText = str_replace(' ', '_', $inputText);
        $inputText = str_replace('.', '_', $inputText);
        
        $output = '';
        
        $tLen = strlen($inputText);
        for ($i = 0; $i < $tLen; $i ++) {
            $poz = strpos($okChars, $inputText[$i]);
            if ($poz > 0) {
                $output = $output . $okChars[$poz];
            }
        }
        
        $output = str_replace('__', '_', $output);
        return $output;
    }
    
    
    static public function arrayOfNames2CommaSeparatedString($inputArray)
    {
        $commaString = '';
        if (count($inputArray)) {
            foreach ($inputArray as $one) {
                if ($commaString)
                    $commaString .= ',';
                $commaString .= "'" . StringTools::latinOnly($one) . "'"; // anti SQL injection
            }
        }
        return $commaString;
    }
    
        
    static public function lengthLimit($inputString, $maxLen=32)
    {
        $strLen = strlen($inputString);
        if ($strLen<=$maxLen) return $inputString;
        $newString = substr($inputString, 0, $maxLen);
        return $newString;
    }

    static public function arrayOfInt2CommaSeparatedInt($inputArray)
    {
        $commaString = '';
        if (count($inputArray)) {
            foreach ($inputArray as $one) {
                if ($commaString)
                    $commaString .= ',';
                $commaString .= intval($one); // anti SQL injection
            }
        }
        return $commaString;
    }

}