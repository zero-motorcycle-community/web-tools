<?php

/*
The data for the VIN decoding is contained within the 1 and 2-dimensional arrays
within each decode_[field] function.  Most of them are organized by year.
Each year the new codes must be appended to each function.
*/

function decode_vin($zvin){
	$error = 0;

	// verify string is a string, clean it up
	if ( is_string($zvin) === false ) {
		$error = 1;
	} else {
		$zvin = cleanvin($zvin);
	}

	// accept VIN strings 12 to 17 characters (don't care about serial#)
	// less than 17 characters won't "validate" but data can still be returned
	$vinl = strlen($zvin);
	if ( $vinl < 12 || $vinl > 17 ){
		$error = 1;
	}
	
	// check World Manufacturer Index for Zero's codes
	$wmi = substr($zvin, 0, 3);
	if ( $wmi <> '538' && $wmi <> 'A4Z' ) {
		// not a Zero Motorcycles VIN
		$error = 1;
	}
	
	if ( $error == 0 ) {
		// looks like we have a Zero vin, go get info
		$vout['vin']   = $zvin;
		$vout['year']  = decode_year($zvin); // year must be looked up first, before other functions
		$vout['make']  = 'Zero'; // Nothing to look up, WMI = 538 or A4Z
		$vout['model'] = decode_model($zvin);
		$vout['line']  = decode_line($zvin);
		$vout['type']  = decode_type($zvin);
		$vout['hp']    = decode_hp($zvin);
		$vout['plant'] = decode_plant($zvin);
		$vout['color'] = decode_color($zvin);
		$vout['valid'] = validate_vin($zvin);
	} else {
		// not a Zero vin, return empty string
		$vout = '';
	}
    return $vout;
}


function cleanvin($string) {
	$string = strtoupper(trim($string)); // Upper case, remove leading/trailing chars
	$string = str_replace(' ', '', $string); // Remove interior spaces
	return preg_replace('/[^A-Za-z0-9]/', '', $string); // Removes special chars.
}


function decode_year($vinyear){
	global $zyear; //global - needed by other decode_ functions
	
	if( strlen($vinyear) >= 12 ){
		$vinyear = substr($vinyear, 9, 1);
	}
	
	$vinyears['8'] = '2008';
	$vinyears['9'] = '2009';
	$vinyears['A'] = '2010';
	$vinyears['B'] = '2011';
	$vinyears['C'] = '2012';
	$vinyears['D'] = '2013';
	$vinyears['E'] = '2014';
	$vinyears['F'] = '2015';
	$vinyears['G'] = '2016';
	$vinyears['H'] = '2017';
	$vinyears['J'] = '2018';
	$vinyears['K'] = '2019';
	$vinyears['L'] = '2020';
	$vinyears['M'] = '2021';
	$vinyears['N'] = '2022';
	$vinyears['P'] = '2023';
	$vinyears['R'] = '2024';
	$vinyears['S'] = '2025';
	$vinyears['T'] = '2026';
	$vinyears['V'] = '2027';
	$vinyears['W'] = '2028';
	$vinyears['X'] = '2029';
	$vinyears['Y'] = '2030';
	
	$zyear = $vinyears["$vinyear"];
	return $zyear;
}

function decode_model($zmodel){
	global $zyear;
	if( strlen($zmodel) >= 12 ){
		$zmodel = substr($zmodel, 11, 1);
	}
	
	//2009
	$zmodels['2009']['1'] = '1';
	//2010
	$zmodels['2010']['1'] = '1';
	//2011
	$zmodels['2011']['C'] = 'X';
	$zmodels['2011']['D'] = 'MX';
	$zmodels['2011']['E'] = 'XU';
	$zmodels['2011']['F'] = 'XU-LSM';
	$zmodels['2011']['1'] = 'X';
	$zmodels['2011']['2'] = 'MX';
	//2012
	$zmodels['2012']['A'] = 'S';
	$zmodels['2012']['B'] = 'DS';
	$zmodels['2012']['C'] = 'XD/X';
	$zmodels['2012']['E'] = 'XU';
	//2013
	$zmodels['2013']['A'] = 'S';
	$zmodels['2013']['B'] = 'DS';
	$zmodels['2013']['C'] = 'XD/X/FX';
	$zmodels['2013']['E'] = 'XU';
	//2014
	$zmodels['2014']['A'] = 'S/SP';
	$zmodels['2014']['B'] = 'DS/DSP';
	$zmodels['2014']['C'] = 'XD/X/FX/FXL';
	$zmodels['2014']['G'] = 'SR';
	$zmodels['2014']['H'] = 'FXP/FXLP';
	//2015
	$zmodels['2015']['A'] = 'S/SP';
	$zmodels['2015']['B'] = 'DS/DSP';
	$zmodels['2015']['C'] = 'FX';
	$zmodels['2015']['G'] = 'SR';
	$zmodels['2015']['H'] = 'FXP';
	//2016
	$zmodels['2016']['A'] = 'S/SP';
	$zmodels['2016']['B'] = 'DS/DSP';
	$zmodels['2016']['C'] = 'FX';
	$zmodels['2016']['G'] = 'SR/DSR';
	$zmodels['2016']['H'] = 'FXP';
	$zmodels['2016']['J'] = 'FXS';
	//2017
	$zmodels['2017']['A'] = 'S/SP';
	$zmodels['2017']['B'] = 'DS/DSP';
	$zmodels['2017']['C'] = 'FX';
	$zmodels['2017']['G'] = 'SR/DSR/DSRP';
	$zmodels['2017']['H'] = 'FXP';
	$zmodels['2017']['J'] = 'FXS';
	
	return $zmodels["$zyear"]["$zmodel"];
}

function decode_line($zline){
	global $zyear;
	if( strlen($zline) >= 12 ){
		$zline = substr($zline, 4, 2);
	}
	
	//2009
	$zlines['2009']['2'] = '168 Cell';
	$zlines['2009']['3'] = '2nd Generation';
	$zlines['2009']['4'] = '336 Cell';
	//2010
	$zlines['2010']['2'] = '168 Cell';
	$zlines['2010']['3'] = '2nd Generation';
	$zlines['2010']['4'] = '336 Cell';
	//2011
	$zlines['2011']['M2'] = 'S';
	$zlines['2011']['D2'] = 'DS';
	$zlines['2011']['U1'] = 'XU';
	$zlines['2011']['U2'] = 'XU-LSM';
	$zlines['2011']['X0'] = 'X';
	$zlines['2011']['X1'] = 'X';
	$zlines['2011']['M0'] = 'MX';
	$zlines['2011']['E1'] = 'MX';
	//2012
	$zlines['2012']['M3'] = 'S';
	$zlines['2012']['D3'] = 'DS';
	$zlines['2012']['L2'] = 'XU-M (EU)';
	$zlines['2012']['U2'] = 'XU';
	$zlines['2012']['C2'] = 'XU-LSM (CA)';
	$zlines['2012']['X2'] = 'MX';
	//2013
	$zlines['2013']['M4'] = 'S';
	$zlines['2013']['D4'] = 'DS';
	$zlines['2013']['X3'] = 'FX';
	$zlines['2013']['U3'] = 'XU';
	//2014
	$zlines['2014']['M4'] = 'S/SR/SP 8.5';
	$zlines['2014']['M5'] = 'S/SR/SP 11.4';
	$zlines['2014']['D6'] = 'DS/DSR/DSP 8.5';
	$zlines['2014']['D5'] = 'DS/DSR/DSP 11.4';
	$zlines['2014']['X4'] = 'FX/FXL';
	$zlines['2014']['X5'] = 'FX/FXLP';
	//2015
	$zlines['2015']['M7'] = 'S/SR/SP 9.4';
	$zlines['2015']['M8'] = 'S/SR/SP 12.5';
	$zlines['2015']['D7'] = 'DS/DSR/DSP 9.4';
	$zlines['2015']['D8'] = 'DS/DSR/DSP 12.5';
	$zlines['2015']['X6'] = 'FX/FXS';
	$zlines['2015']['X7'] = 'FXP';
	//2016
	$zlines['2016']['M0'] = 'S/SR/SP 9.8';
	$zlines['2016']['M9'] = 'S/SR/SP 13.0';
	$zlines['2016']['D0'] = 'DS/DSR/DSP 9.8';
	$zlines['2016']['D9'] = 'DS/DSR/DSP 13.0';
	$zlines['2016']['X8'] = 'FX/FXS';
	$zlines['2016']['X9'] = 'FXP';
	//2017
	$zlines['2017']['MB'] = 'S/SP 6.5';
	$zlines['2017']['MC'] = 'S/SR/SP/SRP 13.0';
	$zlines['2017']['MD'] = 'S 13.0 (11kW)';
	$zlines['2017']['DA'] = 'DS 6.5';
	$zlines['2017']['DB'] = 'DS/DSR/DSP/DSRP 13.0';
	$zlines['2017']['XB'] = 'FX/FXS/FXP';
	
	return $zlines["$zyear"]["$zline"];
}

function decode_type($ztype){
	global $zyear;
	if( strlen($ztype) >= 12 ){
		$ztype = substr($ztype, 3, 1);
	}
	
	//2009
	$ztypes['2009']['S'] = 'Street';
	$ztypes['2009']['C'] = 'Commuter';
	//2010
	$ztypes['2010']['S'] = 'Street';
	$ztypes['2010']['C'] = 'Commuter';
	//2011
	$ztypes['2011']['S'] = 'S/DS Platform';
	$ztypes['2011']['X'] = 'X/MX Platform';
	//2012
	$ztypes['2012']['S'] = 'S/DS Platform';
	$ztypes['2012']['X'] = 'X/MX Platform';
	//2013
	$ztypes['2013']['S'] = 'S/DS Platform';
	$ztypes['2013']['X'] = 'X/MX Platform';
	//2014
	$ztypes['2014']['S'] = 'S/SR/DS Platform';
	$ztypes['2014']['X'] = 'X Platform';
	//2015
	$ztypes['2015']['S'] = 'S/SR/DS Platform';
	$ztypes['2015']['X'] = 'X Platform';
	//2016
	$ztypes['2016']['S'] = 'S/SR/DS/DSR Platform';
	$ztypes['2016']['X'] = 'X Platform';
	//2017
	$ztypes['2017']['S'] = 'S/SR/DS/DSR Platform';
	$ztypes['2017']['X'] = 'X Platform';
	
	return $ztypes["$zyear"]["$ztype"];
}

function decode_hp($zhp){
	global $zyear;
	if ( strlen($zhp) >= 12 ) {
		$zhp = substr($zhp, 6, 2);
	}
	
	// HP codes seem unique across years, only single dimensional array needed, year not needed
	
	$zhps['05'] = '0 - 50 hp';
	$zhps['51'] = '50 - 100 hp';
	$zhps['10'] = '100+ hp';
	$zhps['M1'] = 'Type M/16.2 - 19.8 HP';
	$zhps['A1'] = 'Type A/ 26.1 - 31.9 HP';
	$zhps['M3'] = '9.1 kW (12.2 HP)';
	$zhps['ZA'] = '11kW 75-5';
	$zhps['ZB'] = '11kW 75-7';
	$zhps['Z1'] = '13kw';
	$zhps['Z2'] = '16kw 75-7';
	$zhps['Z3'] = '16kw 75-7R';
	$zhps['Z4'] = '17kW 75-5';
	$zhps['Z5'] = '21kW 75-7';
	$zhps['Z6'] = '21kw 75-7R';
	
	return $zhps["$zhp"];
}

function decode_color($zmodel){
	global $zyear;
	if( strlen($zmodel) >= 12 ){
		$zmodel = substr($zmodel, 11, 1);
	}

	// Color isn't technically in the VIN, but we have an easy lookup table by *model* (using same lookup table, different data)
	
	//2012
	$zcolors['2012']['A'] = 'Red or Black'; //'S'
	$zcolors['2012']['B'] = 'Black or white'; //'DS'
	$zcolors['2012']['C'] = 'White'; //'XD/X'
	$zcolors['2012']['E'] = 'White'; //'XU'
	//2013
	$zcolors['2013']['A'] = 'Yellow or Black'; //'S'
	$zcolors['2013']['B'] = 'Orange or Green'; //'DS'
	$zcolors['2013']['C'] = 'Black'; //'XD/X/FX'
	$zcolors['2013']['E'] = 'Red'; //'XU'
	//2014
	$zcolors['2014']['A'] = 'Yellow or Black'; //'S/SP'
	$zcolors['2014']['B'] = 'Orange or White'; //'DS/DSP'
	$zcolors['2014']['C'] = 'Black'; //'XD/X/FX/FXL'
	$zcolors['2014']['G'] = 'Red'; //'SR'
	$zcolors['2014']['H'] = 'Black or White'; //'FXP/FXLP'
	//2015
	$zcolors['2015']['A'] = 'Yellow or Black'; //'S/SP'
	$zcolors['2015']['B'] = 'Orange or White'; //'DS/DSP'
	$zcolors['2015']['C'] = 'Black'; //'FX'
	$zcolors['2015']['G'] = 'Red'; //'SR'
	$zcolors['2015']['H'] = 'Black or White'; //'FXP'
	//2016
	$zcolors['2016']['A'] = 'Yellow'; //'S/SP'
	$zcolors['2016']['B'] = 'Orange'; //'DS/DSP'
	$zcolors['2016']['C'] = 'Black'; //'FX'
	$zcolors['2016']['G'] = 'Red (SR) or Black (DSR)'; //'SR/DSR'
	$zcolors['2016']['H'] = 'Black or White'; //'FXP'
	$zcolors['2016']['J'] = 'Black'; //'FXS'
	//2017
	$zcolors['2017']['A'] = 'Yellow'; //'S/SP'
	$zcolors['2017']['B'] = 'Orange'; //'DS/DSP'
	$zcolors['2017']['C'] = 'Black'; //'FX'
	$zcolors['2017']['G'] = 'Red (SR), or Black (DSR)'; //'SR/DSR/DSRP'
	$zcolors['2017']['H'] = 'Black or White'; //'FXP'
	$zcolors['2017']['J'] = 'Black'; //'FXS'
	
	return $zcolors["$zyear"]["$zmodel"];
}

function decode_plant($zplant){
	global $zyear;
	if ( strlen($zplant) >= 12 ) {
		$zplant = substr($zplant, 10, 1);
	}
	
	// there's only one, for now.
	$plants['C'] = 'California, Scotts Valley';
	
	return $plants[$zplant];
}


function validate_vin($vin) {
    $vin = strtolower($vin);
    if (!preg_match('/^[^\ioq]{17}$/', $vin)) { 
		// not valid if not 17 characters or has I, O, or Q in VIN
        return false;
    }
    $weights = array(8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2);
    $transliterations = array(
		"a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5, "f" => 6, "g" => 7, "h" => 8,
		"j" => 1, "k" => 2, "l" => 3, "m" => 4, "n" => 5, "p" => 7, "r" => 9, "s" => 2,
		"t" => 3, "u" => 4, "v" => 5, "w" => 6, "x" => 7, "y" => 8, "z" => 9
    );
    $sum = 0;
    for($i = 0 ; $i < strlen($vin) ; $i++ ) { // loop through characters of VIN
        // add transliterations * weight of their positions to get the sum
        if(!is_numeric($vin{$i})) {
            $sum += $transliterations[$vin{$i}] * $weights[$i];
        } else {
            $sum += $vin{$i} * $weights[$i];
        }
    }
    // find check digit by taking the mod of the sum
    $checkdigit = $sum % 11;
    if($checkdigit == 10) { // check digit of 10 is represented by "X"
        $checkdigit = "x";
    }
	
	//will return true or false, based on if calculated check digit matches 9th digit of VIN
    return var_export($checkdigit == $vin{8}, true);
}

?>
