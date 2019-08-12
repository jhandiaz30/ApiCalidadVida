<?php

require_once 'config/conexionSqlsrv.php';

class Login {

    //VARIABLE DE CONEXION
    public $conexion;
    //VARIABLE DE RESPUESTA
    public $responseObj;

    public function __construct()
    {
        //SE CREA EL OBJETO DE CONEXION
        $this->conexion = new conexionSqlsrv();
        //SE CREA UN OBJETO GENERICO
        $this->responseObj = new StdClass;
    }

    //FUNCION DEL LOGIN EN DONDE SE VALIDA QUE EL USUARIO Y CONTRASEÑA SEAN CORRECTOS Y VALIDOS
    //TENIENDO ENCUENTA QUE EN APP ESTA ASOCIADO, CON QUE ROL SE ENCUENTRA Y SU ESTADO
    public function loginUser($user, $pass){
       
        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryLogin = $db -> prepare(
                    "SELECT U.NOMBRE AS N_USUARIO, U.ID_USUARIO AS ID_USUARIO, APP.NOMBRE AS N_APP, R.NOMBRE AS N_ROL FROM USUARIO AS U
                    INNER JOIN USU_ROL AS UR ON U.ID_USUARIO = UR.ID_USUARIO
                    INNER JOIN ROLES AS R ON UR.ID_ROL = R.ID_ROL
                    INNER JOIN APP ON R.ID_APP = APP.ID_APP
                    WHERE U.USUARIO = ? AND U.CLAVE = ? AND U.ESTADO = 'activo'; "
                );

                $queryLogin -> execute( array( $user, $pass ) );
        
                $usuario = $queryLogin->fetchAll( PDO::FETCH_ASSOC );

                if( $usuario ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $usuario;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "Usuario y/o Password incorrectos";
                }

                

            }else{
                
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

            }

            return $this->responseObj;



        } catch (PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;

        }

       

    }


}

?>