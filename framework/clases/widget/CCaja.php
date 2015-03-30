<?php


	class CCaja extends CWidget
	{
		private $_titulo="";
		private $_contenido="";
		private $_opciones=array("class"=>"caja");
		
		public function __construct($titulo,$contenido="",
								$opciones=array())
		{
			$this->_titulo=$titulo;
			$this->_contenido=$contenido;
			$this->_opciones=array_merge($this->_opciones,
										$opciones);
		}
		
		public function dibujate()
		{
			return $this->dibujaApertura().$this->dibujaFin();
			
		}
		public function dibujaApertura()
		{
			ob_start();
			//genero un id unico para el contenido
			$idContenido=CHTML::generaID();
			
			//apertura caja
			echo CHTML::dibujaEtiqueta("div",$this->_opciones,
									"",false);
			
			//titulo
			echo CHTML::dibujaEtiqueta("div",array("class"=>"titulo",
													"onclick"=>"cajaMostrarContenido('$idContenido');"),
									$this->_titulo);
			
			//apertura contenido
			echo CHTML::dibujaEtiqueta("div",array("class"=>"cuerpo",
													"id"=>$idContenido),
									$this->_contenido,false);
			
			
			$escrito=ob_get_contents();
			ob_end_clean();
			
			return $escrito;
		}
		public function dibujaFin()
		{
			ob_start();
			
			//cierre de contenido
			echo CHTML::dibujaEtiquetaCierre("div");
			
			//cierre de la caja
			echo CHTML::dibujaEtiquetaCierre("div");
			
			
			$escrito=ob_get_contents();
			ob_end_clean();
			
			return $escrito;
		}
		
		public static function requisitos()
		{
			ob_start();
			?>
			
			function cajaMostrarContenido(id)
			{
				var contenido=document.getElementById(id);
				
				if (contenido.style.display=="none")
				     contenido.style.display="block";
				    else 
					 contenido.style.display="none";
			 
			}
			
			<?php
			
			$escrito=ob_get_contents();
			ob_end_clean();
			
			return CHTML::script($escrito);
		}
	}
