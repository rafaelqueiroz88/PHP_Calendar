<?php
namespace Processo {
    use PDO;
    use PDOException;
    class Eventos {
        /**
         * Objetos esperados pela classe para processar os eventos
         */
        private static $conn;
        private static $_events         = "events";
        private static $_users          = "users";
        private static $_participants   = "event_participants";
        public static $criador;
        public static $evento;
        public static $dia;
        public static $mes;
        public static $ano;
        public static $data_agendada;
        public static $participantes;
        /**
         * Os objetos a seguir podem ser utilizados para notificar erro ou imprimir
         * valores em tela
         */
        public static $erro;
        public static $total;
        
        /**
         * O construtor requer uma conexão com o banco de dados
         * Não envia, nem faz nenhuma notificação de retorno, apenas é necessário
         * para o processo interno da classe
         */
        public function __construct( $db ) {
            self::$conn = $db;
        }

        /**
         * Cadastrar um evento ao banco de dados na tabela: events
         * Se o usuário cadastrar participantes, também será feito um registro
         * na tabela: event_participants para vincular o ou os usuário(s) a um
         * determinado evento
         * Retorna true or false
         * O resultado é utilizado para notificar o sucesso ou falha da operação
         * na tela do usuário
         */
        public static function CadastrarEvento() {

            $query = "INSERT INTO " . self::$_events . " 
            (description, event_date, event_create, event_owner) 
            VALUES
            (?, ?, now(), ?)";
            $stmt = self::$conn->prepare( $query );

            self::$evento           = htmlspecialchars( strip_tags( self::$evento ) );
            self::$data_agendada    = htmlspecialchars( strip_tags( self::$data_agendada ) );
            self::$criador          = htmlspecialchars( strip_tags( self::$criador ) );

            $stmt->bindParam( 1, self::$evento );
            $stmt->bindParam( 2, self::$data_agendada );
            $stmt->bindParam( 3, self::$criador );
            
            /**
             * Se houverem participantes, será recolhido o evento recém gerado
             * e para cada participante, um novo registro vinculando o participante
             * ao evento será feito em event_participants.
             * Se não houverem participantes, a requisição será normalmente executada.
             * O if indica que o objeto participantes NÃO ESTÁ VAZIO.
             */
            if ( !empty( self::$participantes ) ) :
                /**
                 * Executando o cadastro do evento no banco
                 */
                $stmt->execute();
                /**
                 * Recuperando o ultimo cadastro para obter o ID do evento
                 */
                $query_select   = "SELECT * FROM " . self::$_events . " 
                WHERE event_owner = '1' ORDER BY id DESC LIMIT 1";
                $statement      = self::$conn->prepare( $query_select );
                /**
                 * Verificando se a seleção foi feita com sucesso
                 */
                if( $statement->execute() ) :
                    $row = $statement->fetch( PDO::FETCH_ASSOC );
                    extract( $row );
                    $id_evento = $row['id'];
                    /**
                     * Para cada id recebido, será
                     */
                    foreach( self::$participantes as $participante ) :
                        $query_insert = "INSERT INTO " . self::$_participants . " 
                        (event, user) 
                        VALUES
                        ('$id_evento', '" . $participante['id'] . "')";
                        $stmt_insert = self::$conn->prepare( $query_insert );
                        $stmt_insert->execute();
                    endforeach;
                    return true;
                else :
                    return false;
                endif;                
            else :
                if( $stmt->execute() ) :
                    return true;
                else :
                    return false;
                endif;
            endif;
        }

        /**
         * Requer um id
         * Atualiza um registro para o status 1 (onde 0=sim, 1=não)
         * Assim os registros não aparecem nas consultas comuns.
         * A página que utilizar esta função deverá redirecionar o usuário
         */
        public function ApagarEvento( $id ) {
            $query = "UPDATE " . self::$_events . " set status = 1 WHERE id = $id";
            $stmt = self::$conn->prepare( $query );
            $stmt->execute();
        }

        /**
         * Não requer parametro
         * Retorna todos usuarios cadastrados já impressos em tela
         */
        public function ListarUsuarios() {
            $query                  = $query = "SELECT * FROM " . self::$_users;
            $stmt                   = self::$conn->prepare( $query );
            
            $stmt->execute();

            $num = $stmt->rowCount();
            if( $num > 0 ) :
                while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) :
                    extract( $row );
                    echo '<input type="checkbox" name="participante[]" value="' . $row['id'] . '"> ' . $row['name'] . '<br />';
                endwhile;
            endif;
        }

        /**
         * Requer uma data escrita no padrão mysql
         * ano-mes-dia
         * Retorna todos os eventos desta data
         */
        public function ListarEventos( $a, $m, $d ) {

            $query  = "SELECT * FROM " . self::$_events . " WHERE status = 0 AND event_date = '$a-$m-$d'";
            $stmt   = self::$conn->prepare( $query );
            
            $stmt->execute();

            $num = $stmt->rowCount();
            if( $num > 0 ) :
                while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) :
                    extract( $row );
                    echo 'Descrição: ' . $row['description'] . '<br />';
                    echo 'Estes usuários foram adicionados a este evento: <br />';
                    $inner_query = "SELECT DISTINCT name FROM " . self::$_users . "
                    INNER JOIN " . self::$_participants . "
                    WHERE
                    " . self::$_participants . ".user=" . self::$_users . ".id AND
                    " . self::$_participants . ".event=" . $row['id'];
                    //die("$inner_query");
                    $statement = self::$conn->prepare( $inner_query );
            
                    $statement->execute();

                    while ( $inner_row = $statement->fetch( PDO::FETCH_ASSOC ) ) :
                        echo $inner_row['name'] . '<br />';
                    endwhile;
                    echo '<br />';
                    echo '<a class="botao-navegacao" href="editar.php?id=' . $row['id'] . '">Editar</a> ';
                    echo '<a class="botao-navegacao" href="apagar.php?id=' . $row['id'] . '&d=' . $d . '&m=' . $m . '&a=' . $a . '">Apagar</a> ';
                    echo '<br />';
                    echo '<hr />';
                endwhile;
            endif;
        }

        /**
         * Requer um id
         * Recolhe as informações do evento e armazena nos objetos.
         * Os objetos podem então ser utilizados e exibidos em tela
         * 
         */
        public function AbrirEvento( $id ) {
            $query = "SELECT * FROM " . self::$_events . " WHERE id = $id";            
            $stmt   = self::$conn->prepare( $query );            
            $stmt->execute();

            $row = $stmt->fetch( PDO::FETCH_ASSOC );
            self::$evento = $row['description'];
        }

        /**
         * Requer um id
         * Atualiza o registro com as informações recebidas
         * Retorna true or false para tratar a tela que utilizar a função
         */
        public function AtualizarEvento( $id ) {
            $query = "UPDATE " . self::$_events . " set description = ?, event_last_update = now() WHERE id=$id";
            $stmt = self::$conn->prepare( $query );
            
            self::$evento = htmlspecialchars( strip_tags( self::$evento ) );

            $stmt->bindParam( 1, self::$evento );
            
            if( $stmt->execute() ) :                
                return true;
            else :
                return false;
            endif;
        }

        /**
         * Requer ano, mes, dia
         * Exibe a quantidade de eventos para cada dia, mes e ano que esta função receber
         * Exibe somente a mensagem seguida do total
         */
        public function ListarEventosPorMes( $a, $m, $d ) {
            
            $query  = "SELECT * FROM " . self::$_events . " WHERE status = 0 AND event_date = '$a-$m-$d'";
            
            $stmt   = self::$conn->prepare( $query );
            
            $stmt->execute();
            $num = $stmt->rowCount();

            echo '<a href="eventos.php?d=' . $d . '&m=' . $m . '&a=' . $a . '">';
            echo '<div class="data-evento">';
            echo date( "$d/$m/$a" ) . '<br />';
            echo "Você possui $num eventos nesta data";
            echo '</div>';
            echo '</a>';
        }
    }
}