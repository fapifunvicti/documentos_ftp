<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include  __DIR__ . DIRECTORY_SEPARATOR . "PDF2Dir.class.php";




set_time_limit(0);


if(headers_sent()){
    http_response_code(403);
    die;
    //throw new Exception('ops! houve um problema. teste mais tarde');
	//die('ops! houve um problema. teste mais tarde');
}

if (ini_get('zlib.output_compression')) {
    ini_set('zlib.output_compression', 'Off');
}

//http://10.0.3.239:8086/pagina_ftp/pdf/Dimens%C3%A3o%201%20%E2%80%93%20Organiza%C3%A7%C3%A3o%20Did%C3%A1tico-Pedag%C3%B3gica/Indicador%201.2%20Objetivos%20do%20curso/LEI_n_10861%20%28copiar%202%29.pdf

$pasta =  filter_input(INPUT_GET,'pasta', FILTER_SANITIZE_ENCODED);
$arquivo= filter_input(INPUT_GET,'arquivo', FILTER_SANITIZE_ENCODED);
$ext =    filter_input(INPUT_GET,'mimetype', FILTER_SANITIZE_ENCODED);



if(!is_dir( __DIR__ . DIRECTORY_SEPARATOR . $pasta)){
	http_response_code(404);
	$bdir = basename(__DIR__ . DIRECTORY_SEPARATOR . $pasta);
	die("{$bdir} Arquivo Não Encontrado!");
}


$fulldir = __DIR__ . DIRECTORY_SEPARATOR . urldecode($pasta) . '/' .urldecode($arquivo);
//"/home/ti04/programacao/php/pagina_ftp/pdf/pdf/Dimensão 1 – Organização Didático-Pedagógica/Indicador 1.2 Objetivos do curso/LEI_n_10861%20%28copiar%202%29.pdf"

if(!is_file($fulldir)){
	http_response_code(404);
	$arquivo_nome = basename($fulldir);
	die('arquivo inexistente');
}


$size = stat($fulldir)['size'];

$request_mime = PDF2Dir::buscarMimeType($fulldir);



if($request_mime['ext'] !== $ext){
	http_response_code(404);
	die('extensao incorreta');
}


header('Content-Description: File Transfer');
header('Content-Type: ' . $request_mime['mime']); // Generic MIME type for force download

$filename = basename($fulldir);

switch($request_mime['ext']){
	case 'mp4':
	case 'xls':
	case 'xlsx':
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		break;
}

//header('Content-Disposition: attachment; filename="' . basename($fulldir) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size); // Provide file size


$download_rate = 1024 * 6;
$sleep_time = 1000000;


ob_start();
$fp = fopen($fulldir, 'rb');
while(!feof($fp) && connection_status() == 0 ){
	$start_time = microtime(true);
	echo fread($fp, round($download_rate * 1024));
	flush();

	$elapsed = microtime(true) - $start_time;
	$sleep = $sleep_time - ($elapsed * 1000000);

	if($sleep > 0){
		usleep($sleep);
	}
}
ob_end_flush();
fclose($fp);
exit;
