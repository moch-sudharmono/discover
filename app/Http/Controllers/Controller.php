<?php

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

    use DispatchesCommands, ValidatesRequests;

}

function toMoney($val ,$symbol='$',$r=0)
{
    $n = preg_replace( '/[^a-zA-Z0-9\']/', '',  $val ); 
	
	
		
	
    $c = is_float($n) ? 1 : number_format($n,$r);
	
    $d = '.';
    $t = ',';
    $sign = ($n < 0) ? '-' : '';

    
    $i = $n = number_format(abs($n),$r); 
    $j = 3;//(($j = $i.length) > 3) ? $j % 3 : 0; 
	$price = ($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j));
	//if( $price <= 9 ){
		$price  = $price.".00"; 
	//}
	
    return  $symbol.$sign . $price;
}