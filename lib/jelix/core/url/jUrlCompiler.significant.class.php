<?php
/**
* @package     jelix
* @subpackage  core
* @author      Laurent Jouanneau
* @contributor
* @copyright   2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*
*/


/**
* Compiler for significant url engine
* @package  jelix
* @subpackage core
*/
class jUrlCompilerSignificant implements jISimpleCompiler{

    public function compile($aSelector){
        global $gJCoord;

        $sourceFile = $aSelector->getPath();
        $cachefile = $aSelector->getCompiledFilePath();


        // lecture du fichier xml
        $xml = simplexml_load_file ( $sourceFile);
        if(!$xml){
           return false;
        }
        /*
        <urls>
         <classicentrypoint name="index" default="true">
            <url pathinfo="/test/:mois/:annee" module="" action="">
                  <param name="mois" escape="true" regexp="\d{4}"/>
                  <param name="annee" escape="false" />
                  <static name="bla" value="cequejeveux" />
            </url>
            <url handler="" module="" action=""  />
         </classicentrypoint>
        </urls>

         g�n�re dans un fichier propre � chaque entrypoint :

            $PARSE_URL = array($isDefault , $infoparser,$infoparser... )

            o�
            $isDefault : indique si c'est un point d'entr�e par d�faut, et donc si le parser ne trouve rien, si il ignore ou fait une erreur

            $infoparser = array('module','action','selecteur handler')
            ou
            $infoparser = array( 'module','action', 'regexp_pathinfo',
               array('annee','mois'), // tableau des valeurs dynamiques, class�es par ordre croissant
               array(true, false), // tableau des valeurs escapes
               array('bla'=>'cequejeveux' ) // tableau des valeurs statiques
            )


         g�n�re dans un fichier commun � tous :

            $CREATE_URL = array(
               'news~show@classic' =>
                  array(0,'entrypoint', https true/false, entrypoint true/false, 'selecteur handler')
                  ou
                  array(1,'entrypoint', https true/false, entrypoint true/false,
                        array('annee','mois','jour','id','titre'), // liste des param�tres de l'url � prendre en compte
                        array(true, false..), // valeur des escapes
                        "/news/%1/%2/%3/%4-%5", // forme de l'url
                        )
                  ou
                  array(2,'entrypoint', https true/false, entrypoint true/false ); pour les cl�s du type "@request"
                  array(3,'entrypoint', https true/false, entrypoint true/false );  pour les cl�s du type  "module~@request"

        */
        $typeparam = array('string'=>'([^\/]+)','char'=>'([^\/])', 'letter'=>'(\w)',
           'number'=>'(\d+)', 'int'=>'(\d+)', 'integer'=>'(\d+)', 'digit'=>'(\d)',
           'date'=>'([0-2]\d{3}\-(?:0[1-9]|1[0-2])\-(?:[0-2][1-9]|3[0-1]))', 'year'=>'([0-2]\d{3})', 'month'=>'(0[1-9]|1[0-2])', 'day'=>'([0-2][1-9]|3[0-1])'
           );
        $createUrlInfos=array();
        $createUrlContent="<?php \n";
        $defaultEntrypoints=array();

        foreach($xml->children() as $name=>$tag){
           if(!preg_match("/^(.*)entrypoint$/", $name,$m)){
               //TODO : erreur
               continue;
           }
           $requestType= $m[1];
           $entryPoint = (string)$tag['name'];
           $isDefault =  (isset($tag['default']) ? (((string)$tag['default']) == 'true'):false);
           $isHttps = (isset($tag['https']) ? (((string)$tag['https']) == 'true'):false);
           $generatedentrypoint =$entryPoint;
           if(isset($tag['noentrypoint']) && (string)$tag['noentrypoint'] == 'true')
                $generatedentrypoint = '';
           $parseInfos = array($isDefault);

           // si c'est le point d'entr�e par d�faut pour le type de requet indiqu�
           // alors on indique une regle supplementaire que matcherons
           // toutes les urls qui ne correspondent pas aux autres r�gles
           if($isDefault){
             $createUrlInfos['@'.$requestType]=array(2,$entryPoint, $isHttps);
           }

           $parseContent = "<?php \n";
           foreach($tag->url as $url){
               $module = (string)$url['module'];
               if(isset($url['https'])){
                   $urlhttps=(((string)$url['https']) == 'true');
               }else{
                   $urlhttps=$isHttps;
               }
               if(isset($url['noentrypoint']) && ((string)$url['noentrypoint']) == 'true'){
                   $urlep='';
               }else{
                   $urlep=$generatedentrypoint;
               }
               // dans le cas d'un point d'entr�e qui n'est pas celui par d�faut pour le type de requete indiqu�
               // si il y a juste un module indiqu� alors on sait que toutes les actions
               // concernant ce module passeront par ce point d'entr�e.
               if(!$isDefault && !isset($url['action']) && !isset($url['handler'])){
                 $parseInfos[]=array($module, '', '/.*/', array(), array(), array(), false );
                 $createUrlInfos[$module.'~*@'.$requestType] = array(3,$urlep, $urlhttps);
                 continue;
               }

               $action = (string)$url['action'];
               if(isset($url['actionoverride'])){
                  $actionOverride = preg_split("/[\s,]+/", (string)$url['actionoverride']);
               }else{
                  $actionOverride = false;
               }

               // si il y a un handler indiqu�, on sait alors que pour le module et action indiqu�
               // il faut passer par cette classe handler pour le parsing et la creation de l'url
               if(isset($url['handler'])){
                  $class = (string)$url['handler'];
                  // il faut absolument un nom de module dans le selecteur, car lors de l'analyse de l'url
                  // dans le request, il n'y a pas de module connu dans le context (normal...)
                  $p= strpos($class,'~');
                  if($p === false)
                    $selclass = $module.'~'.$class;
                  elseif( $p == 0)
                    $selclass = $module.$class;
                  else
                    $selclass = $class;
                  $s= new jSelectorUrlHandler($selclass);
                  $createUrlContent.="include_once('".$s->getPath()."');\n";
                  $parseInfos[]=array($module, $action, $selclass, $actionOverride );
                  $createUrlInfos[$module.'~'.$action.'@'.$requestType] = array(0,$urlep, $urlhttps, $selclass);
                  if($actionOverride){
                     foreach($actionOverride as $ao){
                        $createUrlInfos[$module.'~'.$ao.'@'.$requestType] = array(0,$urlep,$urlhttps, $selclass);
                     }
                  }
                  continue;
               }

               $listparam=array();
               $escapes = array();
               if(isset($url['pathinfo'])){
                  $path = (string)$url['pathinfo'];
                  $regexppath = $path;

                  if(preg_match_all("/\:([a-zA-Z_]+)/",$path,$m, PREG_PATTERN_ORDER)){
                      $listparam=$m[1];

                      foreach($url->param as $var){

                        $nom = (string) $var['name'];
                        $k = array_search($nom, $listparam);
                        if($k === false){
                          // TODO error
                          continue;
                        }

                        if (isset ($var['escape'])){
                            $escapes[$k] = (((string)$var['escape']) == 'true');
                        }else{
                            $escapes[$k] = false;
                        }

                        if(isset($var['type'])){
                           if(isset($typeparam[(string)$var['type']]))
                              $regexp = $typeparam[(string)$var['type']];
                           else
                              $regexp = '([^\/]+)';
                        }else if (isset ($var['regexp'])){
                            $regexp = '('.(string)$var['regexp'].')';
                        }else{
                            $regexp = '([^\/]+)';
                        }

                        $regexppath = str_replace(':'.$nom, $regexp, $regexppath);
                      }

                      foreach($listparam as $k=>$name){
                        if(isset($escapes[$k])){
                           continue;
                        }
                        $escapes[$k] = false;
                        $regexppath = str_replace(':'.$name, '([^\/]+)', $regexppath);
                      }
                  }
               }else{
                 $regexppath='.*';
                 $path='';
               }
               if(isset($url['optionalTrailingSlash']) && $url['optionalTrailingSlash'] == 'true'){
                    if(substr($regexppath, -1) == '/'){
                        $regexppath.='?';
                    }else{
                        $regexppath.='\/?';
                    }
               }

               $liststatics = array();
               foreach($url->static as $var){
                  $liststatics[(string)$var['name']] =(string)$var['value'];
               }
               $parseInfos[]=array($module, $action, '!^'.$regexppath.'$!', $listparam, $escapes, $liststatics, $actionOverride );
               $createUrlInfos[$module.'~'.$action.'@'.$requestType] = array(1,$urlep, $urlhttps, $listparam, $escapes,$path, false);
               if($actionOverride){
                  foreach($actionOverride as $ao){
                     $createUrlInfos[$module.'~'.$ao.'@'.$requestType] = array(1,$urlep, $urlhttps, $listparam, $escapes,$path, true);
                  }
               }
           }

           $parseContent.='$GLOBALS[\'SIGNIFICANT_PARSEURL\'][\''.rawurlencode($entryPoint).'\'] = '.var_export($parseInfos, true).";\n?>";

           jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/'.rawurlencode($entryPoint).'.entrypoint.php',$parseContent);
        }
        $createUrlContent .='$GLOBALS[\'SIGNIFICANT_CREATEURL\'] ='.var_export($createUrlInfos, true).";\n?>";
        jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/creationinfos.php',$createUrlContent);
        return true;
    }

}

?>