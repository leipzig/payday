<?PHP 
function make_seed() {
   list($usec, $sec) = explode(' ', microtime());
   return (float) $sec + ((double) $usec * 100000);
}

function rand_hex() {
   mt_srand(make_seed());
   $randval = mt_rand(0,255);
   //convert to hex
   return sprintf("%02X",$randval);
}

function random_color(){
   return "#".rand_hex().rand_hex().rand_hex();
}
?>