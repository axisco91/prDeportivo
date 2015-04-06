<?php

class CBarraMenu extends CWidget{
	private $_opciones=array();
	
	public function __construct($opciones){
		foreach ($opciones as $opcion){
			$fila=array("TEXTO"=>"",
							"URL"=>"",
							"SUBMENU"=>false,
							"ACTIVO"=>true,
							"ITEMS"=>"");
							
			if (isset($opcion["TEXTO"]))
					$fila["TEXTO"]=$opcion["TEXTO"];
			
			if (isset($opcion["URL"]))
					$fila["URL"]=$opcion["URL"];
			if (isset($opcion["SUBMENU"]))
					$fila["SUBMENU"]=$opcion["SUBMENU"];
			if (isset($opcion["ACTIVO"]))
				$fila["ACTIVO"]=$opcion["ACTIVO"];
			if (isset($opcion["ITEMS"]))
				$fila["ITEMS"]=$opcion["ITEMS"];
				
				$this->_opciones[]=$fila;
		}
	}
	
	public function dibujate(){
		return $this->dibujaApertura().$this->dibujaFin();
	}
	
	public function dibujaApertura(){
		ob_start();
		echo CHTML::dibujaEtiqueta("div",array("id"=>"navbar","class"=>"navbar-collapse collapse"),"",false);
		echo CHTML::dibujaEtiqueta("ul",array("class"=>"nav navbar-nav"),"",false);
		foreach($this->_opciones as $valor){
			if ($valor["ACTIVO"]==true){
				if ($valor["SUBMENU"]==false){
					echo CHTML::dibujaEtiqueta("li",array(),"",false);
					echo CHTML::dibujaEtiqueta("a",array("href"=>$valor["URL"]),$valor["TEXTO"]);
				}
				else {
						echo CHTML::dibujaEtiqueta("li",array("class"=>"dropdown"),"",false);
						echo CHTML::dibujaEtiqueta("a",array("class"=>"dropdown-toggle", "data-toggle"=>"dropdown"),$valor["TEXTO"],"false");
						echo CHTML::dibujaEtiquetaCierre("a");
						echo CHTML::dibujaEtiqueta("ul",array("class"=>"dropdown-menu","role"=>"menu"),"",false);
						foreach($valor["ITEMS"] as $sub){
							echo CHTML::dibujaEtiqueta("li",array(),"",false);
							echo CHTML::dibujaEtiqueta("a",array("href"=>$sub["URL"]),$sub["TEXTO"]);
							echo CHTML::dibujaEtiquetaCierre("li");
						}
						echo CHTML::dibujaEtiquetaCierre("ul");
				}
			}	
	    }
		echo CHTML::dibujaEtiquetaCierre("ul");
		echo CHTML::dibujaEtiquetaCierre("div");
		
		$escrito=ob_get_contents();
		ob_end_clean();
		echo $escrito;
		return $escrito;
	}
	
	public function dibujaFin(){
		return "";
	}
	
}
