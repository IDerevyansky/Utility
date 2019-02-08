<?php


function rand_pass(){


$rand = array('A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm',	'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r', 'S', 's',	'T', 't', 'U', 'u', 'V', 'v',	'W', 'w', 'X', 'x',	'Y', 'y', 'Z', 'z','1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

$coun = count($rand);

for($i=0; $i<=20; $i++){

$r = rand(0, $coun);
$row[$i] = $rand[$r];


};
$row = implode('', $row);
return $row;
};


$wr = array('$LOG=', "xxxxxxxxxx", '$PAS_1=', rand_pass(), '$PAS_2=', rand_pass());

$crlf = "\r\n";

$write = "<?php ".$crlf.$wr[0]."'".$wr[1]."'; ".$crlf.$wr[2]."'".$wr[3]."'; ".$crlf.$wr[4]."'".$wr[5]."'; ";


$handle = fopen("pass.php", "w");
fwrite($handle, $write);
fclose($handle);



//$write_mail = $wr[0]." ".$wr[1]."  ".$crlf.$wr[2]." ".$wr[3]."  ".$crlf.$wr[4]." ".$wr[5]."  ";

include('Tamplate_mail.php');

$to = 'xxxxxxx@gmail.com';
$subject = 'new password';
$message = "$write_mail";


// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


// Отправляем
mail($to, $subject, $message, $headers);





?>
