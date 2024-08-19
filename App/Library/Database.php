<?php

namespace App\Library;

use PDO;
use PDOException;
use Exception;

use App\Library\Session;

class Database
{
    private $conexao;
    private static $dbdrive  = "";
    private static $host     = "";
    private static $port     = "";
    private static $user     = "";
    private static $password = "";
    private static $db       = "";

    /**
     * Método construtor do banco de dados
     */

    public function __construct(
        $db_dbdrive,
        $db_host,
        $db_port,
        $db_bdados,
        $db_user,
        $db_password
    ) {
        self::$dbdrive  = $db_dbdrive;
        self::$host     = $db_host;
        self::$port     = $db_port;
        self::$db       = $db_bdados;
        self::$user     = $db_user;
        self::$password = $db_password;
    }

    /*Evita que a classe seja clonada*/
    private function __clone()
    {
    }

    /*Método que destroi a conexão com banco de dados e
        remove da memória todas as variáveis setadas*/
    public function __destruct() {
        $this->disconnect();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }

    /*Metodos que trazem o conteudo da variavel desejada
    @return   $xxx = conteudo da variavel solicitada*/
    private function getDBDrive() {return self::$dbdrive;}
    private function getHost()    {return self::$host;}
    private function getPort()    {return self::$port;}
    private function getUser()    {return self::$user;}
    private function getPassword(){return self::$password;}
    private function getDB()      {return self::$db;}

    public  function connect()
    {
        try {
            if ( $this->getDBDrive() == 'mysql' ) {            // MySQL

                $this->conexao = new PDO(
                                            $this->getDBDrive().":host=".$this->getHost().";port=".$this->getPort().";dbname=".$this->getDB(),
                                            $this->getUser(),
                                            $this->getPassword(),
                                            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
                                        );

            } else if ( $this->getDBDrive() == 'sqlsrv' ) {    // SQL Server

                $this->conexao = new PDO(
                                            $this->getDBDrive().":Server=".$this->getHost().",".$this->getPort().";DataBase=".$this->getDB(),
                                            $this->getUser(),
                                            $this->getPassword(),
                                            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
                                        );

            }

            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, $this->conexao::ERRMODE_EXCEPTION);

        } catch (PDOException $i) {
            //se houver exceçao, exibe
            die("Erro: <code>" . $i->getMessage() . "</code>");
        }

        // Definir a variável de sessão `@current_user` após a conexão
        $this->setCurrentUser(Session::get('usuarioId')); 

        return ($this->conexao);

    }

    // Novo método para definir a variável de sessão @current_user
    public function setCurrentUser($username)
    {
        try {
            
            $sql = "SET @current_user = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$username]);

            $sql = "SELECT @current_user";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erro ao definir variável de sessão: " . $e->getMessage();
            exit;
        }
    }

    private function disconnect(){
        $this->conexao = null;
    }

    /**
     * getCampos
     *
     * @param array $campos
     * @param string $conector
     * @return array
     */
    protected function getCampos(array $campos, $conector = ",")
    {
        $save['sql'] = "";
        $save['dados'] = [];
        $virgula = false;

        foreach ($campos as $key => $value) {
            $juncao = " " . $conector . " ";
            $sinal = " = ";

            if (strtoupper(substr(trim($key), 0, 2)) == "OR") {
                $juncao = " OR ";
                $key = substr(trim($key), 3);
            }

            if (strtoupper(substr(trim($key), strlen(trim($key)) -2, 2)) == "<>") {
                $sinal = " <> ";
                $key = trim(str_replace('<>' , "", $key));
            }

            // Verifica se o valor deve ser tratado como um LIKE
            if (is_array($value) && strtoupper($value[0]) === 'LIKE') {
                $save['sql'] .= ($virgula ? $juncao : "") . "`" . $key . "` LIKE :" . $key . " ";
                $save['dados'][":" . $key] = '%' . $value[1] . '%'; // Adiciona o valor com % para o LIKE
            } else {
                $save['sql'] .= ($virgula ? $juncao : "") . "`" . $key . "`" . $sinal . " :" . $key . " ";
                $save['dados'][":" . $key] = $value;
            }

            $virgula = true;
        }

        return $save;
    }


    /**
     * insert
     *
     * @param string $table
     * @param array $campos
     * @return void
     */
    public function insert($table, $campos = [])
    {
        try {

            // var_dump($table);
            // var_dump($campos);
            // exit;

            $save = $this->getCampos($campos);
            $fields = implode("` , `", array_keys($campos));
            $values = implode(" , ", array_keys($save['dados']));

            $sql = 'INSERT INTO `' . $table . '` (`' . $fields . '`) VALUES (' . $values . ')';

            $conexao = $this->connect();

            $query = $conexao->prepare($sql);

            $query->execute($save['dados']);

            $rs = $conexao->lastInsertId();

            self::__destruct();

        } catch (\PDOException $exc) {
            if ($exc->errorInfo[0] == '45000') {
                Session::set("msgError", "Operações não são permitidas no final de semana.");
                Redirect::page(Formulario::retornaHomeAdminOuHome());
            } else {
                echo "Erro ao inserir registro, favor entrar em contato com o suporte técnico: ERROR: " . $exc->getMessage();
            }
            exit;
        }

        return $rs;
    }


    public function update($table, $conditions, $campos)
    {
        try {

            $save           = $this->getCampos($campos);
            $condWhere      = $this->getCampos($conditions, "AND");

            $save['save']   = array_merge($save['dados'], $condWhere['dados']);
        
            // Construir a string SQL, adicionando a cláusula dataMod = NOW() somente para a tabela 'produtos'
            if ($table == 'produto') {
                $sql = "UPDATE `" . $table . "` SET " . $save['sql'] . ", dataMod = NOW() WHERE " . $condWhere['sql'] . ";";

            } else {
                $sql = "UPDATE `" . $table . "` SET " . $save['sql'] . " WHERE " . $condWhere['sql'] . ";";
            }

            $query = $this->connect()->prepare($sql);

            $query->execute($save['save']);

            $rs = $query->rowCount();

            self::__destruct();

            return $rs;

        } catch (\PDOException $exc) {
            if ($exc->errorInfo[0] == '45000') {
                Session::set("msgError", "Operações não são permitidas no final de semana.");
                Redirect::page(Formulario::retornaHomeAdminOuHome());
            } else {
                echo "Erro ao inserir registro, favor entrar em contato com o suporte técnico: ERROR: " . $exc->getMessage();
            }
            exit;
        }
    }

    /**
     * delete
     *
     * @param string $table
     * @param array $conditions
     * @return void
     */
    public function delete($table, $conditions)
    {
        try {
            $save = $this->getCampos($conditions, "AND");
            $sql = "DELETE FROM {$table} WHERE " . $save['sql'] . ";";

            $query = $this->connect()->prepare($sql);
            $query->execute($save['dados']);

            $rs = $query->rowCount();

            self::__destruct();

            return $rs;

        } catch (\PDOException $exc) {
            // Verifica o código de erro específico para a violação de chave estrangeira
            if ($exc->errorInfo[0] == '23000' && in_array($exc->errorInfo[1], [1451, 1452])) {
                // Mensagem amigável para o usuário
                Session::set("msgError", "Não é possível excluir o registro porque ele possui dependências em outras tabelas.");
                Redirect::page(Formulario::retornaHomeAdminOuHome());
            } else if ($exc->errorInfo[0] == '45000') {
                // Mensagem específica para erros programados
                Session::set("msgError", "Operações não são permitidas no final de semana.");
                Redirect::page(Formulario::retornaHomeAdminOuHome());
            } else {
                // Mensagem genérica para outros erros
                echo "Erro ao excluir registro, favor entrar em contato com o suporte técnico: ERROR: " . $exc->getMessage();
            }
            exit;
        }
    }


    /**
     * select
     *
     * @param string $table
     * @param string $tipo
     * @param array $configs
     * @return mixed
     */
    public function select($table, $tipo = "all", array $configs = [])
    {
        $where['sql'] = "";
        $where['dados'] = [];
        $campos = "*";
        $sql = '';


        // select
        if (isset($configs['campos'])) {
            $campos = "`" . implode("`, `" , $configs['campos']) . "`";
        }

        // where
        if (isset($configs['where'])) {
            $ret = $this->getCampos($configs['where'], "AND");
            $where['sql'] .= " WHERE " . $ret['sql'];
            $where['dados'] = array_merge($where['dados'], $ret['dados']);
        }

        // group by
        if (isset($configs['groupby'])) {
            $groupby = "`" . implode("`, `" , $configs['groupby']) . "`";
        }

        // order by
        if (isset($configs['orderby'])) {
            if (gettype($configs['orderby']) == "string") {
                $configs['orderby'] = [$configs['orderby']];
            }
            $orderby = "`" . implode("`, `" , $configs['orderby']) . "`";
            $orderby = str_replace(" DESC`", "` DESC", $orderby);
        }

        // executar o comando e retornar os dados
        $sql .= "SELECT " . $campos;
        $sql .= " FROM `" . $table . "`";
        $sql .= $where['sql'];
        $sql .= (!empty($groupby) ? " GROUP BY " . $groupby : '');
        $sql .= (!empty($orderby) ? " ORDER BY ". $orderby : '');

        $query = $this->connect()->prepare($sql);

        // var_dump($query);
        // exit;

        $query->execute($where['dados']);
        

        if ($tipo == "first") {
            return $this->dbBuscaArray($query);
        } elseif ($tipo == "all") {
            return $this->dbBuscaArrayAll($query);
        } elseif ($tipo == "count") {
            return $this->dbNumeroLinhas($query);
        }
    }


    /**
     * Método select que retorna um array de objetos
    *   @param string $sql
    *   @param array $params
    *   @return void
    */
    public function dbSelect( $sql , $params = null )
    {
        if ((gettype($params) != 'array') && (gettype($params) != "NULL") ) {
            $params = [$params];
        }

        $query = $this->connect()->prepare( $sql , array( PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL ) );
        $query->execute( $params );
        $rs = $query;

        self::__destruct();

        return $rs;

    }

    /*
    * Método insert que insere valores no banco de dados e retorna o último id inserido
    */
    public function dbInsert($sql , $params = null)
    {
        try {
            $conexao = $this->connect();

            $query   = $conexao->prepare($sql);
            $query->execute($params);

            $rs      = $conexao->lastInsertId(); // or die(print_r($query->errorInfo(), true));

            self::__destruct();

            return $rs;

        } catch (Exception $e) {
            print_r($query->debugDumpParams());
            echo 'Exceção capturada: '.  $e->getMessage(); exit;
        }
    }

    /* Método update que altera valores do banco de dados e retorna o número de linhas afetadas */
    public function dbUpdate( $sql , $params = null )
    {

        try {
            $query=$this->connect()->prepare($sql);
            $query->execute($params);

            $rs = $query->rowCount();// or die(print_r($query->errorInfo(), true));

            self::__destruct();

            return $rs;

        } catch (Exception $e) {
            echo 'Exceção capturada: '.  $e->getMessage(); exit;
        }

    }

    /*
        * Método delete que exclusão valores do banco de dados retorna o número de linhas afetadas
        */

    public function dbDelete($sql,$params=null)
    {

        $query=$this->connect()->prepare($sql);

        try {

            $query->execute($params);
            $rs = $query->rowCount();

        } catch (Exception $exc) {
            echo "Erro ao Excluir Registro, favor entrar em contato com Suporte Tenico" . $exc->getTraceAsString();
        }

        self::__destruct();

        if ($rs == array()) {
            return false;
        } else {
            return $rs;
        }


    }


    /*
    * Método que retornar a posição atual do registro (OBJ)
    */
    public function dbBuscaDados( $rscPdo )
    {
        return $rscPdo->fetch(PDO::FETCH_OBJ);
    }

    /*
    * Método que retornar todos os registros (OBJ)
    */
    public function dbBuscaDadosAll( $rscPdo )
    {
        return $rscPdo->fetchAll(PDO::FETCH_OBJ);
    }

    /*
    * Método que retornar a posição atual do registro (matriz)
    */
    public function dbBuscaArray( $rscPdo )
    {
        return $rscPdo->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * Método que retornar a posição atual do registro (matriz)
    */
    public function dbBuscaArrayAll( $rscPdo )
    {
        return $rscPdo->fetchall(PDO::FETCH_ASSOC);
    }

    /*
    * Método que retornar o Numero de linhas Selecionadas
    */
    public function dbNumeroLinhas( $rscPdo )
    {
        return $rscPdo->rowCount();
    }

    /*
    * Método que retornar o Numero de Colunas Selecionadas
    */
    public function dbNumeroColunas( $rscPdo )
    {
        return $rscPdo->columnCount();
    }

    public function dbResultado( $rscRes , $CampoRetorno )
    {
        $rowResX = $this->dbBuscaArray( $rscRes );

        return $rowResX[ $CampoRetorno ];
    }

}