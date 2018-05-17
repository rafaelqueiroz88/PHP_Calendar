<?php
namespace DatabaseConnector {
    use PDO;
    use PDOException;
    class Database {
        
        /**
         * Objeto único
         * Este objeto será retornado se a função GetConnection obtiver uma conexão
         * funcional.
         */
        public static $conn;
        
        /**
         * Conexão com o banco de dados
         * Não requer nenhuma entrada
         * Retorna conexão com o banco de dados
         */
        public static function GetConnection() {

            /**
             * Dados para conexão. Troque para os dados relacionados a sua
             * conexão atual. No meu caso root está configurado sem senha.
             * Altere se necessário
             */
            self::$conn = null;
            $host       = "localhost";
            $db_name    = "calendar";
            $username   = "root";
            $password   = "";

            try {
                self::$conn = new PDO(
                    "mysql:host=" . $host . ";
                    dbname=" . $db_name,$username,$password
                );
                self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
            catch( PDOException $exception ) {
                echo "Falha detectada: " . $exception->getMessage();
                die();
            }

            return Database::$conn;
        }
    }
}