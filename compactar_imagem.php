<?php

/**
 * compactador de img
 * @author Thyago H. Pacher <thyago.pacher@gmail.com>
 */
$extensoesSobreImg = array('png', 'jpg', 'jpeg');

// Lista todos os arquivos e diretórios no diretório
$diretorio = './img';
$arquivos = scandir($diretorio);
$caminhoArquivo = '';
// Loop para excluir os diretórios . e ..
foreach ($arquivos as $arquivo) {

    doLog(" == Laço de verificação sobre arquivos == ". $arquivo);

    $separaNomeArquivo = explode(".", $arquivo);
    $extensaoArquivo = $separaNomeArquivo[count($separaNomeArquivo) - 1];
    
    if(
        in_array($extensaoArquivo, $extensoesSobreImg) 
        && strstr($arquivo, 'reduzida') == false
    ){
        
        // Caminho para a imagem original
        $imagemOriginal = "./img/$arquivo";
        $novaLargura = 800;

        $separaNomeArquivo = explode('.', $arquivo);
        $arquivoImgReduzida = "./img/{$separaNomeArquivo[0]}-reduzida.{$separaNomeArquivo[1]}";
        doLog(" == arquivoImgReduzida == ". $arquivoImgReduzida);

        $temImgReduzida = file_exists($arquivoImgReduzida);
        if(!$temImgReduzida){
            doLog(" == imagemOriginal == ". $imagemOriginal);
            redimensionarImg($imagemOriginal, $novaLargura);
        }else{
            doLog(' == Img reduzida já produzida...');
        }
    }
}

function doLog($msg){
    echo $msg.'<br>';
}

function redimensionarImg($imagemOriginal, $novaLargura = 800){

    $separaNomeImg = explode('/', $imagemOriginal);
    $nomeImg = $separaNomeImg[count($separaNomeImg) - 1];

    // Obtém informações sobre a imagem original
    list($larguraOriginal, $alturaOriginal) = getimagesize($imagemOriginal);

    // Calcula a nova altura mantendo a proporção
    $novaAltura = ($novaLargura / $larguraOriginal) * $alturaOriginal;

    // Cria uma nova imagem com as dimensões desejadas
    $novaImagem = imagecreatetruecolor($novaLargura, $novaAltura);

    // Carrega a imagem original
    $imagemOriginal = imagecreatefromjpeg($imagemOriginal);

    // Redimensiona a imagem original para as novas dimensões
    imagecopyresampled($novaImagem, $imagemOriginal, 0, 0, 0, 0, $novaLargura, $novaAltura, $larguraOriginal, $alturaOriginal);

    $separaSomenteNome = explode(".", $nomeImg);
    $nomeImgSemExtensao = $separaSomenteNome[0];
    $nomeImgCompactada = $nomeImgSemExtensao.'-reduzida';

    // Caminho para a nova imagem redimensionada
    $novaImagemCaminho = "./img/$nomeImgCompactada.jpg";
    // Salva a nova imagem redimensionada
    imagejpeg($novaImagem, $novaImagemCaminho);

    // Libera a memória
    imagedestroy($novaImagem);
}

echo 'Imagem redimensionada e salva com sucesso.';

?>
