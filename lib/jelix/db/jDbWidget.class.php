<?php
/**
* @package    jelix
* @subpackage db
* @version    $Id:$
* @author     Croes G�rald, Laurent Jouanneau
* @contributor Laurent Jouanneau
* @copyright  2001-2005 CopixTeam, 2005-2006 Laurent Jouanneau
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
* Methodes issues originellement de la classe  CopixDbWidget du framework Copix 2.3dev20050901. http://www.copix.org
* Une partie du code est sous Copyright 2001-2005 CopixTeam (licence LGPL)
* Auteurs initiaux : Gerald Croes et Laurent Jouanneau
* Adapt�e et am�lior�e pour Jelix par Laurent Jouanneau
*/

class jDBWidget {
    /**
    * a jDbConnection object
    */
    private $_conn;

    /**
    * Constructor
    */
    function __construct ($connection){
        $this->_conn = $connection;
    }

    /**
    * Effectue une requ�te, renvoi une ligne de resultat sous forme d'objet et libere les ressources.
    * @param   string   $query   requ�te SQL
    * @return  object  objet contenant les champs  sous forme de propri�t�s, de la ligne s�lectionn�e
    */
    public function  fetchFirst($query){
        $rs     =  $this->_conn->query ($query);
        $result =  $rs->fetch ();
        return $result;
    }

    /**
    * Effectue une requ�te, et met � jour les propri�tes de l'objet pass� en param�tre
    * @param   string   $query   requ�te SQL
    * @param   object ou string  object � remplir ou nom de la classe de l'objet � remplir
    * @return  object  objet initialis� rempli
    */
    public function fetchFirstInto ($query, $object){
        $rs     =  $this->_conn->query   ($query);
        $result =  $rs->fetchInto ($object);
        return $result;
    }

    /**
    * R�cup�re tout les enregistrements d'un select dans un tableau (d'objets)
    * @param   string   $query   requ�te SQL
    * @return  array    tableau d'objets
    */
    public function fetchAll($query, $limitOffset=null, $limitCount=null){
        if($limitOffset===null || $limitCount===null){
            $rs =  $this->_conn->query ($query);
        }else{
            $rs =  $this->_conn->limitQuery ($query, $limitOffset, $limitCount);
        }
        return $rs->fetchAll ();
    }

    /**
    * R�cup�re tout les enregistrements d'un select dans un tableau (d'objets)
    * @param   string   $query   requ�te SQL
    * @param   object ou string  $className object � remplir ou nom de la classe de l'objet � remplir
    * @return  array    tableau d'objets
    */
    public function fetchAllInto($query, $className, $limitOffset=null, $limitCount=null){
        if($limitOffset===null || $limitCount===null){
            $rs =  $this->_conn->query ($query);
        }else{
            $rs =  $this->_conn->limitQuery ($query, $limitOffset, $limitCount);
        }
        $result = array();
        if ($rs){
            while($res = $rs->fetchInto ($className)){
                $result[] =  $res;
            }
        }
        return $result;
    }
}
?>