<?php
namespace App\Libraries;


define("PAGINA_ACTUAL_GRID", "_gridpaginador");

class GridPagination
{
	private $arr_columnas = "";
	private $arr_datos = "";
	private $str_html = "";
	private $theme = "";
	private $idvalue = "";
	private $idpaginador;

	function __construct()
	{
	}

	public function set_configs($arr_columns, $arr_datos, $idvalue, $theme, $idtabla = "")
	{
		$this->arr_columns = $arr_columns;
		$this->arr_datos = $arr_datos;
		$this->idvalue = $idvalue;
		$this->theme = $theme;
		$this->idtabla = $idtabla;
		$this->is_paginador = FALSE;
	} // set_configs()

	public function set_configs_paginador($arr_columns, $idvalue, $theme, $total_datos, $valores_xpagina, $funcion = "", $idtabla = "")
	{
		$this->arr_columns = $arr_columns;
		$this->idvalue = $idvalue;
		$this->theme = $theme;
		$this->is_paginador = TRUE;

		$this->total_datos = $total_datos;
		$this->limit_values_paginador = $valores_xpagina;
		$this->funcion = $funcion;
		$this->idtabla = $idtabla;
	} // set_configs_paginador()

	public function set_data($arr_datos = array())
	{
		$this->arr_datos = $arr_datos;
		// echo "set_data()";
		// echo "<pre>"; print_r($this->arr_datos); die();
	} // set_data()

	public function get_table()
	{
		$this->get_header();
		$this->get_body();
		if ($this->is_paginador  && $this->total_datos > $this->limit_values_paginador) {
			$this->get_paginador();
		}
		return $this->str_html;
	} // get_table()


	public function get_header()
	{
		$this->str_html .= "<div class='table-responsive' id='revisar_requisitos'>";
		$this->str_html .= "<table id='{$this->idtabla}' class='table table-striped'>";

		$this->str_html .= "<thead>";
		$this->str_html .= "<tr class={$this->theme}>";

		foreach ($this->arr_columns as $item => $c) {
			$tipo = $c["type"];
			$label = $c["header"];
			$width = (isset($c["width"])) ? $c["width"] : "auto";
			$tooltip = (isset($c["tooltip"])) ? $c["tooltip"] : "";

			switch ($tipo) {
				case 'hidden':
				$this->str_html .= "<th id='" . $item . "' hidden>";
				$this->str_html .= "<center>" . $label . "</center>";
				$this->str_html .= "</th>";
				break;
				case 'text':
				$this->str_html .= "<th id='" . $item . "' style='width:{$width}'>";
				$this->str_html .= "<center>" . $label . "</center>";
				$this->str_html .= "</th>";
				break;
				case 'button':
				$this->str_html .= "<th id='" . $item . "' style='width:{$width}'>";
				$this->str_html .= "<center>" . $label . "</center>";
				$this->str_html .= "</th>";
				break;
			}
		}

		$this->str_html .= "</tr>";
		$this->str_html .= "</thead>";
	} // get_header()

	public function get_body()
	{
		// echo "get_body()";
		// echo "<pre>"; print_r($this->arr_datos); die();
		$mensaje = "";
		$this->str_html .= "<tbody>";
		// if(count($this->arr_datos) > 0 && $this->arr_datos[0][$this->idvalue]!=""){
		if (count($this->arr_datos) > 0) {
			// echo "if"; die();
			// echo "<pre>";
			// print_r($this->arr_datos);
			// die();
			for ($i = 0; $i < count($this->arr_datos); $i++) {
				$this->str_html .= "<tr>";
				$cont_columnas = 0;
				$fila = $i + 1;
				// "<pre>"; print_r($this->arr_datos);die();
				foreach ($this->arr_columns as $item => $c) {
					$tipo = $c["type"];
					$label = $c["header"];
					// echo $item."\n";
					// print_r($c)."\n";
					// die();

					switch ($tipo) {
						case 'hidden':
						$this->str_html .= "<td id='" . $item . "' data='" . $this->arr_datos[$i][$item] . "' hidden>";
						$this->str_html .= $this->arr_datos[$i][$item];
						$this->str_html .= "</td>";
						break;
						case 'text':
						$this->str_html .= "<td id='" . $item . "' data='" . $this->arr_datos[$i][$item] . "' >";
						$this->str_html .= $this->arr_datos[$i][$item];
						$this->str_html .= "</td>";
						break;

						case 'button':

						$flag = TRUE;
						if( (isset($c['config']["dato1_condicional_mayor_cero"]) && $c['config']["dato1_condicional_mayor_cero"] != '') &&
						(isset($c['config']["dato2_condicional_mayor_cero"]) && $c['config']["dato2_condicional_mayor_cero"] != '') ){
							$dato1_condicional_mayor_cero = $this->arr_datos[$i][$c['config']["dato1_condicional_mayor_cero"]];
							$dato2_condicional_mayor_cero = $this->arr_datos[$i][$c['config']["dato2_condicional_mayor_cero"]];

							$flag = ($dato1_condicional_mayor_cero>0 && strlen($dato2_condicional_mayor_cero)>0) || ($dato1_condicional_mayor_cero==0 && strlen($dato2_condicional_mayor_cero)==0)?TRUE:FALSE;
						}

						if($flag){


							$tooltip = (isset($c['config']["tooltip"])) ? 'data-toggle="tooltip" data-placement="top" data-original-title="' . $c['config']["tooltip"] . '"' : '';

							// El parámetro de la función será el valor del nombre del campo según la consulta
							$this->str_html .= "<td id='" . $item . "'>";
							$visible = (isset($c['config']['visible'])) ? TRUE : FALSE;
							$visible = (isset($this->arr_datos[$i][$item]) && ($this->arr_datos[$i][$item] != FALSE || $this->arr_datos[$i][$item] != 0 || $this->arr_datos[$i][$item] != '')) ? TRUE : FALSE;
							// echo $visible."\n";
							// echo $this->arr_datos[$i][$item];
							$idvalor = $this->arr_datos[$i][$item];

							if (isset($this->arr_datos[$i]['nivel'])) {
								$nivel = $this->arr_datos[$i]['nivel'];
							}


							// echo $idvalor."\n";
							if (isset($c['config']["m_arg"]) && $c['config']["m_arg"]) {

								$funcion = $c['config']["funcion"] . "({$idvalor})>" . $c["config"]['texto'];
								// echo "default1"."\n";
								// echo $label."\n";
							} else {
								// echo "ELSE"; die();
								// $funcion = $c['config']["funcion"] . "('{$idvalor}')>" . $c["config"]['texto'];
								$funcion = (isset($c['config']["funcion"])) ? $c['config']["funcion"] . "('{$idvalor}')" : '';
								$texto = (isset($c['config']["texto"])) ? $c['config']["texto"] : '';
								// echo $c['config']["m_arg"]."\n";
								// echo $funcion."\n";
								// die();
							}
							// echo $funcion."\n";
							// echo $visible."\n";
							// var_dump($texto); die();
							$disabled_corte = '';
							if (isset($c['config']['config_condicion_corte'])) {
								$data_valor=$c['config']['config_condicion_corte']['valor_comparar'];
								if ($data_valor == 1) {
									$disabled_corte = 'disabled';
								}
							}

							if ($visible) {
								// echo "soy visible \n";
								// echo $funcion."\n";
								$this->str_html .= "<center><button " . $tooltip . " class='btn btn-sm btn-primary' onclick={$funcion} {$disabled_corte}>
								{$texto}
								</button>
								</center>";
								// echo $this->str_html."\n";
							}

							$this->str_html .= "</td>";
						}else{
							$this->str_html .= "<td></td>";
						}
						break;
					}
					$cont_columnas++;
				} // end for columns
				$this->str_html .= "</tr>";
			}
		} else {
			// echo "else"; die();
			$this->str_html .= "<tr>";
			$this->str_html .= "<td colspan='" . count($this->arr_columns) . "'>No hay datos para mostrar</td>";
			$this->str_html .= "</tr>";
		}

		$this->str_html .= "</tbody>";

		$this->str_html .= "</table>";
		$this->str_html .= "</div>";
	} // get_body()


	//creamos los enlaces de nuestra paginación
	public function get_paginador()
	{
		$function = ($this->funcion == "") ? 'get_gridpaginador' : $this->funcion;
		$str_paginador = "";
		// echo "total ". $this->total_datos; die();
		//página actual
		$actual_pag = $_SESSION[PAGINA_ACTUAL_GRID];

		//limite por página
		$limit = $this->limit_values_paginador;
		//total de enlaces que existen
		$totalPag = floor($this->total_datos/$limit);
		// echo $totalPag;
		//links delante y detrás que queremos mostrar
		$pagVisibles = 2;

		if($actual_pag <= $pagVisibles){
				$primera_pag = 1;
		}else{
				$primera_pag = $actual_pag - $pagVisibles;
		}

		if(($actual_pag + $pagVisibles) <= $totalPag){
				$ultima_pag = $actual_pag + $pagVisibles;
		}else{
				$ultima_pag = $totalPag;
		}

		$str_paginador .= "<nav>
		<ul class='pagination justify-content-center'>
		";

		$str_paginador .= ($actual_pag > 1) ?
		"	<li class='page-item'>
				<a class='page-link' href='javascript:void(0)'  onclick={$function}(0," . $limit . ")>
					<i class='bi bi-skip-backward-fill'></i>
				</a>
			</li>
			"
		:
		"<li class='page-item disabled'>
			<a class='page-link' href='javascript:void(0)'>
				<i class='bi bi-skip-backward-fill'></i>
		</a>
		</li>
		";
		$str_paginador .= ($actual_pag > 1) ?
		"<li class='page-item'>
			<a class='page-link' href='javascript:void(0)' onclick={$function}(" . (($actual_pag - 2) * $limit) . ")>
			<span aria-hidden='true'><i class='bi bi-chevron-left'></i>
			</span>
		</a>
		</li>"
		:
		"<li class='page-item disabled'>
		<a class='page-link' href='javascript:void(0)'>
		<i class='bi bi-chevron-left'></i>
		</a>
		</li>";

		// for ($i = $primera_pag; $i <= $ultima_pag + 1; $i++) {
		for($i=$primera_pag; $i<=$ultima_pag+1; $i++){
			$z = $i;
			$str_paginador .= ($i == $actual_pag) ?
			"<li class='page-item disabled'>
			<a class='page-link' href='javascript:void(0)'>" . $i . "</a></li>"
			:
			"<li class='page-item'>
			<a class='page-link' href='javascript:void(0)' onclick={$function}(".(($z-1)*$limit).")>".$i."</li></a>";
		}


		$str_paginador .= ($actual_pag < $totalPag) ?
		"<li class='page-item'>
		<a class='page-link' href='javascript:void(0)' onclick={$function}(" . (($actual_pag) * $limit) . ")>
		<i class='bi bi-chevron-right' aria-hidden='true'></i>
		</a>
		</li>"
		:
		"<li class='page-item disabled'>
		<a class='page-link' href='javascript:void(0)'>
		<i class='bi bi-chevron-right' aria-hidden='true'></i>
		</a>
		</li>";



		$str_paginador .= ($actual_pag < $totalPag) ?
		"<li class='page-item'>
		<a class='page-link' href='javascript:void(0)' onclick={$function}(" . (($totalPag) * $limit) . ")>
			<i class='bi bi-skip-forward-fill'></i>
		</a>
		</li>"
		:
		"<li class='page-item disabled'>
		<a class='page-link' href='javascript:void(0)'>
			<i class='bi bi-skip-forward-fill'></i>
		</a>
		</li>";

		$str_paginador .= "</ul></nav>";


		$this->str_html .= $str_paginador;
		$str_paginador = NULL;
	} // get_paginador()


	public function get_offset($post, $valores_xpagina)
	{
		$offset = !isset($post["offset"]) || $post["offset"] == "undefined" ? 0 : $post["offset"];
		if ($offset == 0) {
			$_SESSION[PAGINA_ACTUAL_GRID] = 1;
		} else {
			$_SESSION[PAGINA_ACTUAL_GRID] = ($offset + $valores_xpagina) / $valores_xpagina;
		}
		return $offset;
	} // get_offset()

	public static function get_paginador_only($funcion, $valores_xpagina, $total_datos)
	{
		$function = $funcion;
		$str_paginador = "";
		//página actual
		$actual_pag = $_SESSION[PAGINA_ACTUAL_GRID];
		//limite por página
		$limit = $valores_xpagina;
		//total de enlaces que existen
		$totalPag = floor($total_datos / $limit);
		//links delante y detrás que queremos mostrar
		$pagVisibles = 2;

		if ($actual_pag <= $pagVisibles) {
			$primera_pag = 1;
		} else {
			$primera_pag = $actual_pag - $pagVisibles;
		}

		if ($actual_pag + $pagVisibles <= $totalPag) {
			$ultima_pag = $actual_pag + $pagVisibles;
		} else {
			$ultima_pag = $totalPag;
		}


		$str_paginador .= "<nav><ul class='pagination justify-content-center' style='margin-top:3px !important; '>";
		$str_paginador .= ($actual_pag > 1) ?
		"<li class='page-item'> <a href='javascript:void(0)'  onclick={$function}(0," . $limit . ")><i class='bi bi-skip-backward-fill' aria-hidden='true'></i></li></a>"
		:
		"<li class='page-item disabled'>   <a href='javascript:void(0)'><i class='bi bi-skip-backward-fill' aria-hidden='true'></i></li></a>";

		$str_paginador .= ($actual_pag > 1) ?
		"<li class='page-item'>   <a href='javascript:void(0)' onclick={$function}(" . (($actual_pag - 2) * $limit) . ")><span aria-hidden='true'><i class='bi bi-chevron-left' aria-hidden='true'></i></span> </li></a>"
		:
		"<li class='page-item disabled'><a href='javascript:void(0)'><span aria-hidden='true'><i class='bi bi-chevron-left' aria-hidden='true'></i></span></li></a>";

		for ($i = $primera_pag; $i <= $ultima_pag + 1; $i++) {
			$z = $i;
			$str_paginador .= ($i == $actual_pag) ?
			"<li class='page-item disabled'>  <a  href='javascript:void(0)'>" . $i . "</li></a>"
			:
			"<li class='page-item'><a href='javascript:void(0)' onclick={$function}(" . (($z - 1) * $limit) . ")>" . $i . "</li></a>";
		}

		$str_paginador .= ($actual_pag < $totalPag) ?
		"<li class='page-item'><a href='javascript:void(0)' onclick={$function}(" . (($actual_pag) * $limit) . ")> <span aria-hidden='true'><i class='feather-chevron-right' aria-hidden='true'></i></span></li></a>"
		:
		"<li class='page-item disabled'><a href='javascript:void(0)'><span aria-hidden='true'><i class='feather-chevron-right' aria-hidden='true'></i></span></li></a>";

		$str_paginador .= ($actual_pag < $totalPag) ?
		"<li class='page-item'><a href='javascript:void(0)' onclick={$function}(" . (($totalPag) * $limit) . ")><i class='feather-skip-forward' aria-hidden='true'></i></li></a>"
		:
		"<li class='page-item disabled'>  <a href='javascript:void(0)'><i class='feather-skip-forward' aria-hidden='true'></i></li></a>";

		$str_paginador .= "</ul></nav>";

		return $str_paginador;
	} // get_paginador_only()

}// class
