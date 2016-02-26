<?php

/**
 * Busca CEP correios
 * @author Giovani Paseto giovaniw2@gmail.com
 */
$cep = "03712010";
$action = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaEndereco.cfm";
$ch = curl_init($action);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "CEP=" . $cep . "");
$r = curl_exec($ch);
//Opção 1 - salvar em arquivo
$file = "cep.html";
if (is_writable($file)) {
  $fo = fopen($file, "w+");
  fwrite($fo, $r);
  if (is_file($file)) {
    libxml_use_internal_errors(true); //Disable warnings
    $dom = new DOMDocument;
    $dom->loadHTMLFile($file);
    $cells = $dom->getElementsByTagName('td');
    foreach ($cells as $cell) {
      echo $cell->nodeValue, PHP_EOL;
    }
    unlink($file);
  }
} else {
  //Opção 2 - regex  
  preg_match('/<tr>(.*?)<\/tr>/s', $r, $matches);
  print_r($matches[1]);
}
curl_close($ch);
?>