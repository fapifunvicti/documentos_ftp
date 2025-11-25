<?php
class PDF2Dir {

        private string $folder;

        public static function limparPasta(string $folder, string $url): string {

            $parsed = parse_url($url);

            $path = $parsed['path'] ?? '';
            $path_parts = explode('/', trim($path, '/'));

            if($path_parts[0] === $folder){
                array_shift($path_parts);
            }

            $novo_path = '/' . implode('/', $path_parts);
            //$parsed['path'] = $novo_path;
            return $novo_path;

        }

        public static function buscarMimeType(string $path){
            $info = pathinfo($path, PATHINFO_EXTENSION);

            switch($info){
                case 'pdf':
                    return  ['mime'=>'application/pdf', 'ext' => $info];

                case 'xls':
                    return ['mime' => 'application/vnd.ms-excel', 'ext' => $info];
                  

                case 'xlsx':
                    return ['mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'ext'=> $info];
                    
                case 'mp4':
                    return ['mime' => 'video/mp4', 'ext'=> $info];
            }

            return ['mime' => 'application/octet-stream', 'ext' => $info];
        }

        public function lerDiretorio(?string $dir, int $profundidade = 0): string {
            if($dir === null || !is_dir($dir)){
                throw new InvalidArgumentException("Diretorio Inválido ou Nulo");
            }

            if($profundidade <= 0){
                $this->folder = $dir;
            }

	        $dir_original = $dir;

  

            $itens = scandir($dir);
            natcasesort($itens);
            
            $itens = array_values(array_diff($itens, ['.', '..', '.htaccess']));
            $total_itens = count($itens);

            $html = "";


           
            if($profundidade <= 0){
                $html .= "<div id=\"arvore\">";
                $html .= "<ul>";
            }

            foreach($itens as $item){
                if($item == "." || $item === ".." || str_starts_with($item, ".")){
                    continue;
                }
                
                $dir_completo = $dir . DIRECTORY_SEPARATOR . $item;

                if(is_dir($dir_completo)){
                    $numero_arquivos = count(glob($dir_completo. DIRECTORY_SEPARATOR . "*", GLOB_BRACE));
                    
                    if($numero_arquivos <= 0){
                        continue;
                    }

                    
                    //if($profundidade == 0){
                    //    $total = count(array_diff(glob($dir_completo. "/*/*.{pdf}", GLOB_BRACE), ['.', '..']));

                        //if($total <= 0){
                        //   continue;
                        //}
                    //}

                    

                    if($profundidade == 0){
                        $html .= '<li data-jstree=\'{\"opened\":true,\"selected\":true}\' >'. '<strong>' . htmlspecialchars($item) . '</strong>' .  PHP_EOL;
                    }else {
                         $html .= '<li>'.  htmlspecialchars($item) .  PHP_EOL;
                    }
      
                    if($profundidade > 0){
                        $html .= "<ul>";
                        $html .=  $this->lerDiretorio($dir_completo, $profundidade+1);;
                        $html .= "</ul>";
                    }else {
                        $html .= "<ul>";
                        $html .=  $this->lerDiretorio($dir_completo, $profundidade+1);;
                        $html .= "</ul>";
                    }
                    $html .= '</li>';
                }else {
                    $nome = htmlentities($item, ENT_QUOTES, "UTF-8", false);
		            $info = pathinfo($this->folder . $dir . '/' . $item, PATHINFO_EXTENSION);
                    

                    $query = http_build_query([
			            'pasta' => $this->folder,
			            'arquivo' =>  PDF2Dir::limparPasta($this->folder, $dir . '/' . $item), //$dir . '/' . $item
                        'mimetype' => $info
                    ]);
                    
                    $icone = './pdf.png';

                    switch($info){

                        default:
                        case 'pdf':
                            $icone = './pdf.png';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icone = './xls_icon.png';
                            break;
                        case 'mp4':
                            $icone  = './mp4_icon.png';
                            break;
                    }

                    $html .= '<p>'.pathinfo($this->folder . $dir . '/' . $item, PATHINFO_EXTENSION).'</p>';
                    $link = "download.php?" . $query;
                    $html .= "<li  data-jstree='{\"icon\":\"{$icone}\"}'>";
                    $html .= "<a class=\"jstree-clicked\" href=\"{$link}\">{$nome}</a>";
                    $html .= "</li>";
                }

            }


     

            return $html;

        }
   
}       
