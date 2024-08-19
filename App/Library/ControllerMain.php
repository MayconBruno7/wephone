<?php

namespace App\Library;

class ControllerMain 
{
	public $dados;
	public $model;

	/**
	 * construct
	 *
	 * @param array $dados 
	 */
	public function __construct($dados)
	{ 
        $this->auxiliarConstruct($dados);
	}

    /**
     * auxiliarConstruct
     *
     * @param mixed $dados 
     * @return void
     */
    public function auxiliarConstruct($dados)
    {
		$this->dados = $dados;
		$this->loadHelper(["Ambiente", "Crud"]);

        // criando o objeto do Model e conectando a base de dados

        $cModel = $dados['model'] . "Model";

		// verificando se o arquivo model existe para ser carregado
		if (file_exists(".." . DS . "App" . DS . "Model" . DS . $cModel . ".php")) {
			require_once ".." . DS . "App" . DS . "Model" . DS . $cModel . ".php";
			$this->model = new $cModel(); // cria objeto model
		}
    }

	/**
	 * loadModel
	 *
	 * @param string $nomeModel 
	 * @return void|object
	 */
	public function loadModel($nomeModel)
	{
		$nomeModel .= "Model";

		if (file_exists(".." . DS . "App" . DS . "Model" . DS . $nomeModel . ".php")) {
			require_once ".." . DS . "App" . DS . "Model" . DS . $nomeModel . ".php";
			return new $nomeModel();
		} else {
			return null;
		}
	}

	/**
	 * loadHelper
	 *
	 * @param string|array $nome
	 * @return void
	 */
	public function loadHelper($nome)
	{
		if (gettype($nome) == "string") {
			$nome = [$nome];
		}

		foreach ($nome as $value) {

			$nameFile = ".." . DS . "App" . DS . "Helper" . DS . "{$value}.php";

			if (file_exists($nameFile)) {
				require_once $nameFile;
			}
		}
	}

	/**
	 * loadView - Carrega views
	 *
	 * @param string $nameView 
	 * @param array $dados 
	 * @return void
	 */
	public function loadView($nameView, $dados = [], $exibeCabRodape = true)
	{
		$aDados = $dados;

		// Carrega cabeçalho
		if ($exibeCabRodape) {
			require_once ".." . DS . "App" . DS . "View" . DS . "comuns" . DS . "cabecalho.php";
		}

		if (Session::get("inputs") != false) {
            $dados = Session::getDestroy('inputs');
        }

		if (count($dados) > 0) {
			$_POST = $dados;
		}
		
		// Carrega a página
		if (file_exists(".." . DS . "App" . DS . "View" . DS . $nameView . ".php")) {
			require_once ".." . DS . "App" . DS . "View" . DS . $nameView . ".php";
		} else {
			require_once ".." . DS . "App" . DS . "View" . DS . "comuns" . DS . "erros.php";
		}

		// Carrega rodapé
		if ($exibeCabRodape) {
			require_once ".." . DS . "App" . DS . "View" . DS . "comuns" . DS . "rodape.php";
		}
	}
		
    /**
     * getController
     *
     * @return string
     */
    public function getController()
    {
        return $this->dados['controller'];
    }

    /**
     * getAcao
     *
     * @return string
     */
    public function getAcao()
    {
        return $this->dados['acao'];
    }

    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {  
        if (isset($this->dados['get']['parametros'])) {
            $parametros = explode('/', $this->dados['get']['parametros']);
            return (isset($parametros[3]) ?  $parametros[3] : null);
        } else {
            return null;
        }
    }

	/**
     * getId
     *
     * @return int
     */
    public function getOutrosParametros($get)
    {  
        if (isset($this->dados['get']['parametros'])) {
            $parametros = explode('/', $this->dados['get']['parametros']);
            return (isset($parametros[$get]) ?  $parametros[$get] : null);
        } else {
            return null;
        }
    }

    /**
     * getPost
     *
     * @return array
     */
    public function getPost($nameCampo = null)
    {
		if (is_null($nameCampo)) {
			return $this->dados['post'];
		} else {
			return $this->dados['post'][$nameCampo];
		}
    }

    /**
     * getGet
     *
     * @return array
     */
    public function getGet()
    {
        return $this->dados['get'];
    }

    // /**
    //  * getOutrosParametros
    //  *
    //  * @return array
    //  */
    // public function getOutrosParametros()
    // {
    //     return $this->dados['outrosParametros'];
    // }

	/**
     * getAdministrador
     *
     * @return boolean
     */
    public function getAdministrador()
    {
        if (Session::get("usuarioId") != "") {
            if (Session::get("usuarioNivel") == 1) {
                return true;
            }            
        }

        return false;
    }

    /**
     * getUsuario
     *
     * @return boolean
     */
    public function getUsuario()
    {
        if (Session::get("usuarioId") != "" && (Session::get("usuarioNivel") == 1 || Session::get("usuarioNivel") == 11)) {
            return true;      
        }
    
        return false;
    }
}