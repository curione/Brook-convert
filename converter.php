<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $video_url = $_POST['video-url'];

  // extrair o ID do vídeo do URL (exemplo: https://www.youtube.com/watch?v=abcd1234)
  $video_id = substr($video_url, strpos($video_url, '?v=') + 3);

  // construir o URL da API do YouTube para obter informações do vídeo
  $api_url = "https://www.googleapis.com/youtube/v3/videos?id={$video_id}&key=AIzaSyDt5Rnbs7kKrlyKEsPKbYYsVP_HXahVZuU&part=snippet&fields=items(snippet(title))";

  // enviar uma solicitação para a API do YouTube para obter informações do vídeo
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $api_response = curl_exec($ch);
  curl_close($ch);

  // decodificar a resposta da API do YouTube e obter o título do vídeo
  $video_info = json_decode($api_response);
  $video_title = $video_info->items[0]->snippet->title;

  // construir o URL de download do vídeo em MP3
  $download_url = "https://www.convertmp3.io/fetch/?format=JSON&video={$video_url}&title={$video_title}";

  // enviar uma solicitação para o serviço de conversão para obter o link de download do MP3
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $download_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $download_response = curl_exec($ch);
  curl_close($ch);

  // decodificar a resposta do serviço de conversão e obter o link de download do MP3
  $download_info = json_decode($download_response);
  $mp3_download_link = $download_info->link;

  // redirecionar para o link de download do MP3
  header('Location: ' . $mp3_download_link);
  exit;
}
?>
