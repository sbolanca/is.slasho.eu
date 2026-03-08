<?php
class zahtjev { 

    public static function detect() { 
        $useragent = $_SERVER['HTTP_USER_AGENT'];
		$ip_adresa = $_SERVER['REMOTE_ADDR'];
		$verzija = '';

        // PREGLEDNIK
        if (preg_match('/opera/i', $useragent)) { 
            $preglednik = 'Opera'; 
        } 
        elseif (preg_match('/chrome/i', $useragent)) { 
            $preglednik = 'Chrome'; 
			if (preg_match('/Chrome\/([\d.]+)/i', $useragent, $matches)) $verzija = $matches[1];
        } 
        elseif (preg_match('/safari/i', $useragent)) { 
            $preglednik = 'Safari'; 
        } 
		// Zbog IE 11:
        elseif (preg_match('/trident/i', $useragent)) { 
            $preglednik = 'IE'; 
        } 
        elseif (preg_match('/msie/i', $useragent)) { 
            $preglednik = 'IE'; 
        } 
        elseif (preg_match('/firefox/i', $useragent)) { 
            $preglednik = 'Firefox'; 
        } 
        elseif (preg_match('/mozilla/i', $useragent) && !preg_match('/compatible/i', $useragent)) { 
            $preglednik = 'Mozilla'; 
        } 
        else { 
            $preglednik = 'nepoznat'; 
        } 

        // VERZIJA 
		if (empty($verzija)) {
			if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/i', $useragent, $matches)) $verzija = $matches[1]; 
			else $verzija = 'nepoznata'; 
		}

        // PLATFORMA
        if (preg_match('/macintosh|mac os x/i', $useragent)) { 
            $platforma = 'Mac OS'; 
        } 
        elseif (preg_match('/windows phone/i', $useragent)) { 
            $platforma = 'Windows Phone'; 
        } 
        elseif (preg_match('/windows|win32/i', $useragent)) { 
            $platforma = 'Windows'; 
        } 
        elseif (preg_match('/android/i', $useragent)) { 
            $platforma = 'Android'; 
        } 
        elseif (preg_match('/iPhone|iPad|iPod/i', $useragent)) { 
            $platforma = 'iOS'; 
        } 
        elseif (preg_match('/blackberry/i', $useragent)) { 
            $platforma = 'Blackberry'; 
        } 
        elseif (preg_match('/linux/i', $useragent)) { 
            $platforma = 'Linux'; 
        } 
        else { 
            $platforma = 'nepoznata'; 
        } 


		// INFOSNIPER GEOLOCIRANJE
		// Provjera da li već imam tu adresu u bazi
	
		$res=mysql_query("SELECT drzava, grad, provider FROM ip_data WHERE ip = '".$ip_adresa."' LIMIT 1");

		$broj_rezultata = mysql_num_rows($res);

		if ($broj_rezultata == 1) 
		{ 
			$red_rezultata = mysql_fetch_assoc($res);
			$drzava =    $red_rezultata['drzava'];
			$grad =      $red_rezultata['grad'];
			$provider =  $red_rezultata['provider'];;
		} else {
			$xml_rezultat = @file_get_contents('http://www.infosniper.net/xml.php?k=TO167RFwGCfIRu0FsiOcDb&ip_address='.$ip_adresa);
			$xml_rezultat = new SimpleXMLElement($xml_rezultat);

			$drzava =    $xml_rezultat->result[0]->countrycode;
			$grad =      $xml_rezultat->result[0]->city;
			$provider =  $xml_rezultat->result[0]->provider;
			// Uzimam novu ip adresu jer kad radim lokalno dobijem ::1
			$ip_adresa = $xml_rezultat->result[0]->ipaddress;
			
			mysql_query("INSERT INTO ip_data VALUES('$ip_adresa','$drzava','$grad','$provider')");
		}

		// NIZ SA REZULTATIMA
		return array( 
			'preglednik'  => mysql_real_escape_string($preglednik), 
			'verzija'     => mysql_real_escape_string($verzija), 
			'platforma'   => mysql_real_escape_string($platforma), 
			'useragent'   => mysql_real_escape_string($useragent),
			'ip_adresa'   => mysql_real_escape_string($ip_adresa),
			'drzava'      => mysql_real_escape_string($drzava),
			'grad'        => mysql_real_escape_string($grad),
			'provider'    => mysql_real_escape_string($provider)
		); 
	} 
} 
?>