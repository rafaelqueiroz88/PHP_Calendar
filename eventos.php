<?php

session_start();

include 'database.php';
include 'processar.php';

use DatabaseConnector\Database;
use Processo\Eventos as Evento;
new Database;

$db = Database::GetConnection();

new Evento( $db );

/**
 * Se caso for executado um cadastro através do verbo POST, recolher os dados,
 * e se todos estiverem ok, executar a função CadastrarEvento da classe Eventos
 * em processar.php.
 * A função envia um retorno do tipo boolean. Utilizar para notificar o sucesso
 * ou falha da operação.
 */
if( $_SERVER["REQUEST_METHOD"] == "POST" ) :

    /**
     * Estanciando a classe Eventos
     */
    new Evento( $db );

    /**
     * Recolhendo os valores recebidos e atribuindo aos objetos necessários
     */
    Evento::$criador        = $_SESSION['user_id'];
    Evento::$evento         = $_POST['evento'];
    Evento::$data_agendada  = $_POST['data_agendada'];
    
    $participantes          = array();
    if ( !empty( $_POST['participante'] ) ) :
        foreach( $_POST['participante'] as $participante ) :
            $participantes[] = array( "id" => $participante );
        endforeach;
        Evento::$participantes = $participantes;
    endif;
    
    /**
     * Se caso a sql de cadastro retornar TRUE, ou seja, todas as necessidades foram cumpridas
     * executar a seguinte mensagem de texto notificando o sucesso da operação
     */
    if( Evento::CadastrarEvento() ) :
        echo "Evento criado com sucesso!";
    else :
        echo "Falha na operação. É possível que algum campo não tenha sido devidamente preenchido!"; 
    endif;
endif;

$d        = isset( $_GET['d'] ) ? $_GET['d'] : date( "d" );
$a        = isset( $_GET['a'] ) ? $_GET['a'] : date( "Y" );
$m        = isset( $_GET['mes'] ) ? $_GET['mes'] : date( "m" );

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Eventos de <?php echo "$d/$m/$a"; ?></title>
        <link rel="stylesheet" href="estilo.css" />
    </head>
    <body>
        <a href="index.php" class="botao-navegacao">Retornar a agenda</a>
        <br />
        <br />
        <div class="cadastro-evento">
            <?php
            if( isset( $_SESSION['evento_removido'] ) ) :
                echo $_SESSION['evento_removido'];
                unset( $_SESSION['evento_removido'] );
            endif;
            ?>
            <fieldset>
                <legend>Evento</legend>
                <form action="eventos.php?<?php echo "d=$d&m=$m&a=$a"; ?>" method="post">
                    <input type="hidden" name="dia" value="<?php echo $d; ?>" />
                    <input type="hidden" name="mes" value="<?php echo $m; ?>" />
                    <input type="hidden" name="ano" value="<?php echo $a; ?>" />
                    <input type="hidden" name="data_agendada" value="<?php echo date( $a . '-' . $m . '-' . $d ); ?>" />
                    <div class="campo-evento">
                        <div class="descricao">
                            <label for="evento">Descrição: </label>
                        </div>
                        <input type="text" name="evento" class="campo-texto" id="evento" required />
                    </div>                   
                    <div class="campo-evento">                        
                        <spam>
                            O evento será agendado em: <?php echo "$d/$m/$a"; ?>.                                
                        </spam>                        
                    </div>
                    <div class="campo-evento">
                        <h3>Sua lista de participantes</h3>
                        <hr />
                        <?php
                        Evento::ListarUsuarios();
                        ?>
                    </div>
                    <div class="campo-evento">
                        <center>
                            <button class="botao-cadastro" id="botao-cadastro">
                                Agendar
                            </button>
                        </center>
                    </div>
                </form>
            </fieldset>
            <hr />
            <br />
            Estes eventos já estão agendados nesta data (<?php echo "$d/$m/$a"; ?>);
            <br />
            <?php
            Evento::ListarEventos( $a, $m, $d );
            ?>
        </div>
    </body>
</html>