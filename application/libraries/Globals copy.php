<?php

    class Globals {
        public function __construct(){
            
        }

        public function number_to_words($number)
        {
            $before_comma = trim($this->to_word($number));
            $after_comma = trim($this->comma($number));
            return ucwords($results = $before_comma.( $after_comma != "" ?' koma '.$after_comma : ""));
        }
        function to_word($number)
        {
            $words = "";
            $arr_number = array(
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas");
            if($number<12)
            {
                $words = "".$arr_number[$number];
            }
            else if($number<20)
            {
                $words = $this->to_word($number-10)." belas";
            }
            else if($number<100)
            {
                $words = $this->to_word($number/10)." puluh ".$this->to_word($number%10);
            }
            else if($number<200)
            {
                $words = "seratus ".$this->to_word($number-100);
            }
            else if($number<1000)
            {
                $words = $this->to_word($number/100)." ratus ".$this->to_word($number%100);
            }
            else if($number<2000)
            {
                $words = "seribu ".$this->to_word($number-1000);
            }
            else if($number<1000000)
            {
                $words = $this->to_word($number/1000)." ribu ".$this->to_word($number%1000);
            }
            else if($number<1000000000)
            {
                $words = $this->to_word($number/1000000)." juta ".$this->to_word($number%1000000);
            }
            else if($number<1000000000000)
            {
                $words = $this->to_word($number/1000000000)." miliar ".$this->to_word($number%1000000);
            }
            else if($number<1000000000000000)
            {
                $words = $this->to_word($number/1000000000000)." triliun ".$this->to_word($number%1000000);
            }
            else
            {
                $words = "undefined";
            }
            return $words;
        }
        function comma($number)
        {
            $after_comma = stristr($number,'.');
            if ($after_comma != "00000"){
                $arr_number = array(
                    "nol",
                    "satu",
                    "dua",
                    "tiga",
                    "empat",
                    "lima",
                    "enam",
                    "tujuh",
                    "delapan",
                    "sembilan");
                $results = "";
                $length = strlen($after_comma);
                $i = 1;
                while($i<$length)
                {
                    $get = substr($after_comma,$i,1);
                    $results .= " ".$arr_number[$get];
                    $i++;
                }
                return $results;
            }
            
        }

        public function convert_month_to_word($number){
            switch ($number) {
                case 1:
                    return 'Januari';
                    break;
                case 2:
                    return 'Februari';
                    break;
                case 3:
                    return 'Maret';
                    break;
                case 4:
                    return 'April';
                    break;
                case 5:
                    return 'Mei';
                    break;
                case 6:
                    return 'Juni';
                    break;
                case 7:
                    return 'Juli';
                    break;
                case 8:
                    return 'Agustus';
                    break;
                case 9:
                    return 'September';
                    break;
                case 10:
                    return 'Oktober';
                    break;
                case 11:
                    return 'November';
                    break;
                case 12:
                    return 'Desember';
                    break;
                default:
                    return 'undefined';
                    break;
            }
        }

        public function convert_number_to_romawi($number){
            $ret = "";
            if ($number < 1 || $number > 5000000000){
                var_dump("error");
                exit();
            } else {
                while($number >= 1000) {
                    $ret .= "M";
                    $number = $number - 1000;
                }
                if ($number >= 500) {
                    if ($number >= 900){
                        $ret .= "CM";
                        $number = $number - 900;
                    } else {
                        $ret .= "D";
                        $number = $number - 500;
                    }
                }
                while($number >= 100){
                    if ($number >= 400){
                        $ret .= "CD";
                        $number = $number - 400;
                    } else {
                        $ret .= "C";
                        $number = $number - 100;
                    }
                }
                if ($number >= 50){
                    if ($number >= 90){
                        $ret .= "XC";
                        $number = $number - 90;
                    } else {
                        $ret .= "L";
                        $number = $number - 50;
                    }   
                }
                while($number >= 10){
                    if ($number >= 40){
                        $ret .= "XL";
                        $number = $number - 40;
                    } else {
                        $ret .= "X";
                        $number = $number - 10;
                    }
                }
                if ($number >= 5){
                    if ($number == 9){
                        $ret .= "IX";
                        $number = $number - 9;
                    } else {
                        $ret .= "V";
                        $number = $number - 5;
                    }   
                }
                while($number >= 1){
                    if ($number == 4){
                        $ret .= "IV";
                        $number = $number - 4;
                    } else {
                        $ret .= "I";
                        $number = $number - 1;
                    }
                }
            }
            return $ret;
        }


    }
    
?>