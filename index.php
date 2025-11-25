<?php 
 require "PDF2Dir.class.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <title>Documentos UniFUNVIC - Lista de Documentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Documentos UniFUNVIC - Lista de Documentos</title>

</head>
<body>
    <div class="container">

    <span> 
        <img class="logo" src="./logo_funvic_branco.png" alt="Centro Unbiversitário UniFUNVIC">
    </span>
    <span>
    
        <h1 class="titulo">Avaliação Para renovação de reconhecimento - Teologia</h1>
                <p>
                <i class="fa-solid fa-user-graduate"></i> Curso: Bacharelado em Teologia na modalidade presencial</p>
                <p>
                <i class="fa-solid fa-school"></i> Código da avaliação: 227266
                </p>
                <p>
                <i class="fa-solid fa-barcode"></i> Código: 202424613
                </p>
                <p style="font-weight: bolder;">
                    IES: Centro Universitário UNIFUNVIC
                </p>
    </span>    

<hr>

    <?php
        $lista = new PDF2Dir();
        
        ob_start();
        echo $lista->lerDiretorio("pdf");
        ob_end_flush();
    ?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="js/jstree.min.js"></script>

<script>

    $('#arvore').jstree({

    'core': {
        'expand_selected_onload': false,
        'themes': {
            'responsive': true,
            'dots': true
        }
    },
    'plugins': ['types', 'wholerow']
})
    .on('click', '.jstree-anchor', function(e) {
        var node = $('#arvore').jstree(true).get_node(this);
        
        // Verifica se é um arquivo (não tem filhos)
        if (node.type === 'file' || node.children.length === 0) {
            e.preventDefault();
            e.stopPropagation();
            
            // Obtém o link do atributo 'href' ou dos dados do nó
            var downloadUrl = $(this).attr('href') || node.a_attr.href;
  
             if(window.open(downloadUrl, '_blank') == null){
                 if (downloadUrl) {
                    var link = document.createElement('a');
                    link.href = downloadUrl;
                    link.target = "_blank";
                    link.download = node.text;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
             }
        }
    });
</script>
</body>
</html>


