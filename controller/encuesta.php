<?php

//SE IMPORTA EL ARCHIVO DE CONEXION A LA BASE DE DATOS
require_once 'config/conexionSqlsrv.php';


class Encuesta{

   

    //VARIABLE DE CONEXION
    public $conexion;
    //VARIABLE DE RESPUESTA
    public $responseObj;

    public function __construct() { 
        //SE CREA EL OBJETO DE CONEXION
        $this->conexion = new conexionSqlsrv();
        //SE CREA UN OBJETO GENERICO
        $this->responseObj = new StdClass;
    }

    

   //METODO QUE CARGA LAS ENCUESTAS SEGUN EL USUARIO Y SU ROL
    public function nombreApp($id_user){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryApp = $db -> prepare(
                    "SELECT ID_APP,NOMBRE, DESCRIPCION 
                    From APP        
                    WHERE ID_APP IN 
                    (SELECT ID_APP 
                    FROM ROLES 
                    WHERE ID_ROL IN 
                    (SELECT ID_ROL 
                    FROM USU_ROL 
                    WHERE ID_USUARIO = ? ) 
                    AND ID_APP <> 3) AND ESTADO = 'Activo';"
                );

                $queryApp->execute( array( $id_user ));

                $apps = $queryApp->fetchAll( PDO::FETCH_ASSOC );

                if($apps){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $apps;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontraron registros.";

                }
            }else{
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
            }

            return  $this->responseObj;

        } catch ( PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;
        }
    }


    //METODO QUE CARGA LAS PREGUNTAS SEGUN LA ENCUESTA SELECCIONADA
    //id_app = id de encuesta o formato que se parametriza en la DB

    public function loadPregunta($id_app){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryPregunta = $db -> prepare("SELECT P.ID_PREGUNTA, P.ORDEN_PREGUNTAS, P.TITULO, P.DESCRIPCION, P.ID_APP  
                FROM PREGUNTAS AS AP
                INNER JOIN APP AS A ON A.ID_APP = AP.ID_APP
                INNER JOIN  PREGUNTAS AS P ON P.ID_PREGUNTA = AP.ID_PREGUNTA
                WHERE A.ID_APP = ? ORDER BY CAST(P.ORDEN_PREGUNTAS AS FLOAT) ;"
                                                );

                $queryPregunta-> execute( array($id_app) ); 

                $encuesta = $queryPregunta->fetchAll( PDO::FETCH_ASSOC );

                if($encuesta){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $encuesta;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontraron registros.";

                }
            }else{
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
            }

            return  $this->responseObj;

        } catch ( PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;
        }
    }

    //METODO QUE CARGA LAS PREGUNTAS SEGUN LA ENCUESTA SELECCIONADA

    public function loadEncabezado($id_app){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryEncabezado = $db -> prepare("SELECT macroproceso, proceso, descripcion, version, codigo from app where id_app = ?;");

                $queryEncabezado-> execute( array($id_app) ); 

                $encabezado = $queryEncabezado->fetchAll( PDO::FETCH_ASSOC );

                if($encabezado){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $encabezado;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontraron registros.";

                }
            }else{
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
            }

            return  $this->responseObj;

        } catch ( PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;
        }
    }

    //METODO QUE CARGA LAS PREGUNTAS SEGUN LA ENCUESTA SELECCIONADA

    public function loadUsuario($id_user){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryUsuario = $db -> prepare("SELECT nombre, cedula from usuario where id_usuario = ?;");

                $queryUsuario-> execute( array($id_user) ); 

                $usuario = $queryUsuario->fetchAll( PDO::FETCH_ASSOC );

                if($usuario){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $usuario;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontraron registros.";

                }
            }else{
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
            }

            return  $this->responseObj;

        } catch ( PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;
        }
    }

    //METODO QUE TRAE LAS OPCIONES DE RESPUESTA DEPENDIENDO DEL ID DE LA PREGUNTA 
    public function loadOpcionRta($id_pregunta){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryOpcion = $db -> prepare("SELECT O.ID_OPCION_RTA, O.ORDEN_OPCIONES, O.TITULO, O.VALOR, P.ID_PREGUNTA  
                FROM PREG_OPC AS PO
                INNER JOIN PREGUNTAS AS P ON P.ID_PREGUNTA = PO.ID_PREGUNTA
                INNER JOIN  OPCIONES_RTA AS O ON O.ID_OPCION_RTA = PO.ID_OPCION_RTA
                WHERE P.ID_PREGUNTA = ? ;"
                                                );

                $queryOpcion-> execute( array($id_pregunta) ); 

                $pregu = $queryOpcion->fetchAll( PDO::FETCH_ASSOC );

                if($pregu){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $pregu;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontraron registros.";

                }
            }else{
                $this->responseObj->error    = 3;
                $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
            }

            return  $this->responseObj;

        } catch ( PDOException $e) {

            $this->responseObj->error    = 1;
            $this->responseObj->message  = $e->getMessage();
             
            return $this->responseObj;
        }
    }
// METODO QUE PERMITE CARGAR A LA DB EL ENCABEZADO DEL FORMULARIO

public function finalizarEncuesta(
    $numeroCuadrilla,
    $numeroConsignacion,
    $nombreJefe,
    $actividad,
    $fechaEncuesta,
    $contrato,
    $nombreEnc,
    $usuario   
){         

try {

 $db = $this->conexion->conectar();

 if( $db != null ){
    
    // Se toma la fecha del sistema para guardar en DB 
    $fechaSistema = date("Y-m-d");


$queryEncuesta = $db -> prepare(
"INSERT INTO encuestas( numero_grupo_trabajo, numero_de_consignacion, nombre_jefe_consignacion, 
actividad_realizar, fecha_sistema, fecha_encuesta, id_contrato, id_app, id_usuario, estado) 
VALUES (?,?,?,?,?,?,?,?,?,'Finalizado');"
);

$queryEncuesta -> execute( array( 
    $numeroCuadrilla,
    $numeroConsignacion,
    $nombreJefe,
    $actividad,
    $fechaSistema,
    $fechaEncuesta,
    $contrato,
    $nombreEnc,
    $usuario,
    $id_encuesta
));

if( $queryEncuesta-> errorCode() == '00000'  ){

$this->responseObj->error    = 0;
$this->responseObj->message  = $queryEncuesta;

}else{

$this->responseObj->error    = 2;
$this->responseObj->message  = "Operación no realizada.";
}

} else{

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


 // METODO QUE PERMITE CARGAR A LA DB EL ENCABEZADO DEL FORMULARIO

    public function saveEncuesta(
            $numeroCuadrilla,
            $numeroConsignacion,
            $nombreJefe,
            $actividad,
            $fechaEncuesta,
            $contrato,
            $nombreEnc,
            $usuario   
    ){         
       
      try {

         $db = $this->conexion->conectar();
 
         if( $db != null ){
            
            // Se toma la fecha del sistema para guardar en DB 
            $fechaSistema = date("Y-m-d");


     $queryEncuesta = $db -> prepare(
      "INSERT INTO encuestas( numero_grupo_trabajo, numero_de_consignacion, nombre_jefe_consignacion, 
      actividad_realizar, fecha_sistema, fecha_encuesta, id_contrato, id_app, id_usuario, estado) 
      VALUES (?,?,?,?,?,?,?,?,?,'Activo');"
      );

  $queryEncuesta -> execute( array( 
            $numeroCuadrilla,
            $numeroConsignacion,
            $nombreJefe,
            $actividad,
            $fechaSistema,
            $fechaEncuesta,
            $contrato,
            $nombreEnc,
            $usuario
  ));

     if( $queryEncuesta-> errorCode() == '00000'  ){

       $this->responseObj->error    = 0;
       $this->responseObj->message  = "Operación realizada exitosamente.";

     }else{

       $this->responseObj->error    = 2;
       $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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

public function eliminarFirma(      
    $Nombres,
    $cedula,
    $firma,
    $id_encuesta
){  
    
    /*El base64 se ve modificado por alguna razón al momento de llegar al PHP, modifica los + por un 
    espacio, por lo tanto, se reemplaza el base64_encode por la función de str_replace*/
    $firma = str_replace ( " " , "+" , $firma );     

try {

     $db = $this->conexion->conectar();

    if( $db != null ){
        $queryRta = $db -> prepare(
            /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

            "DELETE FROM firmas 
            WHERE Nombres = '$Nombres' AND cedula='$cedula' AND firma='$firma' AND id_encuesta='$id_encuesta' "
         

        );
      
        $queryRta -> execute( array(
            $Nombres,
            $cedula,
            $firma,
            $id_encuesta
        ));

        if( $queryRta-> errorCode() == '00000'  ){

             $this->responseObj->error    = 0;
             $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

        }else{

             $this->responseObj->error    = 2;
             $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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








public function guardarFirma(      
    $Nombres,
    $cedula,
    $firma,
    $id_encuesta
){  
    
    /*El base64 se ve modificado por alguna razón al momento de llegar al PHP, modifica los + por un 
    espacio, por lo tanto, se reemplaza el base64_encode por la función de str_replace*/
    $firma = str_replace ( " " , "+" , $firma );     

try {

     $db = $this->conexion->conectar();

    if( $db != null ){
        $queryRta = $db -> prepare(
            /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

         "INSERT INTO firmas (Nombres, cedula, firma, id_encuesta)
            VALUES (?, ?, ?, ?);"

        );
      
        $queryRta -> execute( array(
            $Nombres,
            $cedula,
            $firma,
            $id_encuesta
        ));

        if( $queryRta-> errorCode() == '00000'  ){

             $this->responseObj->error    = 0;
             $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

        }else{

             $this->responseObj->error    = 2;
             $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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


public function backup(     
    $fecha,
    $accion,
    $id_usuario
){  
       
    $fechaSistema = date("Y-m-d");

try {

     $db = $this->conexion->conectar();

    if( $db != null ){
        $queryRta = $db -> prepare(
            /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

         "INSERT INTO back (fecha, accion, id_usuario)
            VALUES (?, ?, ?);"

        );
      
        $queryRta -> execute( array(
            $fecha= $fechaSistema
            ,
            $accion,
            $id_usuario
            ));

        if( $queryRta-> errorCode() == '00000'  ){

             $this->responseObj->error    = 0;
             $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

        }else{

             $this->responseObj->error    = 2;
             $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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
 
// METODO QUE TRAE EL ULTIMO ENCABEZADO O ENCUESTA GUARDADA POR CADA USUARIO 

public function loadTraerEncuesta( $id_user ){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryEnc = $db -> prepare("SELECT * 
            FROM encuestas 
            WHERE id_encuesta = (SELECT MAX(id_encuesta) FROM encuestas 
            WHERE estado = 'activo' or estado = 'editado') AND id_usuario = ?;");

            //SE EJEUCTA LA QUERY
            $queryEnc->execute( array($id_user));
            $encuesta = $queryEnc->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($encuesta){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $encuesta;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;

    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

public function loadTraerEncuestaFinalizada2($numero ){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryEnc = $db -> prepare("
            SELECT TOP $numero * FROM encuestas ORDER BY id_encuesta DESC;");

            //SE EJEUCTA LA QUERY
            $queryEnc->execute( array($numero));
            $encuesta = $queryEnc->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($encuesta){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $encuesta;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;

    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 


public function loadTraerEncuestaFinalizada( $id_user ){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryEnc = $db -> prepare("SELECT * 
            FROM encuestas 
            WHERE id_encuesta = (SELECT MAX(id_encuesta) FROM encuestas 
            WHERE estado = 'Finalizado') AND id_usuario = ?;");

            //SE EJEUCTA LA QUERY
            $queryEnc->execute( array($id_user));
            $encuesta = $queryEnc->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($encuesta){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $encuesta;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;

    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

public function loadEncuestaFinalizada($id_encuesta ){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryEnc = $db -> prepare("SELECT * 
            FROM encuestas
            WHERE id_encuesta =?;");

            //SE EJEUCTA LA QUERY
            $queryEnc->execute( array($id_encuesta));
            $encuesta = $queryEnc->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($encuesta){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $encuesta;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;

    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

}

// METODO QUE GUARDA LAS FIRMAS DE CADA ENCUESTA




// METODO QUE GUARDA LAS RESPUESTAS A LAS PREGUNTAS DE CADA FORMULARIO

public function saveRta(
       
        $respuesta,
        $observacion,
        $image,
        $pregunta,
        $opcion,
        $id_encuesta
    ){  
        
        /*El base64 se ve modificado por alguna razón al momento de llegar al PHP, modifica los + por un 
        espacio, por lo tanto, se reemplaza el base64_encode por la función de str_replace*/
        $evidencia = str_replace ( " " , "+" , $image );     

    try {

         $db = $this->conexion->conectar();

        if( $db != null ){
            $queryRta = $db -> prepare(
                /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

                "SELECT respuesta, observacion, evidencia, id_opcion_rta, id_pregunta
                FROM respuestas_enc
                WHERE id_encuesta = ?
                AND id_pregunta = ?
                IF @@ROWCOUNT > 0 
                UPDATE respuestas_enc
                SET respuesta = ?, observacion = ?, evidencia = ?
                WHERE id_encuesta = ?
                AND id_pregunta = ?
                ELSE 
                INSERT INTO respuestas_enc (respuesta, observacion, evidencia, id_pregunta, id_opcion_rta, id_encuesta)
                VALUES (?, ?, ?, ?, ?, ?);"

            );
          
            $queryRta -> execute( array(
                $id_encuesta,
                $id_pregunta,
                $respuesta,
                $observacion,
                $evidencia,
                $id_encuesta,
                $id_pregunta,
                $respuesta,
                $observacion,
                $evidencia,
                $pregunta,
                $opcion,
                $id_encuesta,
            ));

            if( $queryRta-> errorCode() == '00000'  ){

                 $this->responseObj->error    = 0;
                 $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

            }else{

                 $this->responseObj->error    = 2;
                 $this->responseObj->message  = "Operación no realizada.";
            }

        } else{

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

// METODO QUE GUARDA LAS RESPUESTAS A LAS PREGUNTAS DE CADA FORMULARIO EN ESTADO FINALIZADO
public function saveRtaFinal(
    $respuesta,
    $observacion,
    $image,
    $pregunta,
    $opcion,
    $id_encuesta
){ 

    /*El base64 se ve modificado por alguna razón al momento de llegar al PHP, modifica los + por un 
    espacio, por lo tanto, se reemplaza el base64_encode por la función de str_replace*/
    $evidencia = str_replace ( " " , "+" , $image );      

try {

     $db = $this->conexion->conectar();

    if( $db != null ){


        $queryRta = $db -> prepare(
            /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

            "SELECT respuesta, observacion, evidencia, id_opcion_rta, id_pregunta
            FROM respuestas_enc
            WHERE id_encuesta = ?
            AND id_pregunta = ?
            IF @@ROWCOUNT > 0 
            UPDATE respuestas_enc
            SET respuesta = ?, observacion = ?, evidencia = ?
            WHERE id_encuesta = ?
            IF @@ROWCOUNT > 0
            UPDATE encuestas
            SET estado = 'Finalizado'
            WHERE id_encuesta = ?
            ELSE 
            INSERT INTO respuestas_enc (respuesta, observacion, evidencia, id_pregunta, id_opcion_rta, id_encuesta)
            VALUES (?, ?, ?, ?, ?, ?)
            UPDATE encuestas
            SET estado = 'Finalizado'
            WHERE id_encuesta = ?;"

        );
      
        $queryRta -> execute( array(
            $id_encuesta,
            $id_pregunta,
            $respuesta,
            $observacion,
            $evidencia,
            $id_encuesta,
            $id_encuesta,
            $respuesta,
            $observacion,
            $evidencia,
            $pregunta,
            $opcion,
            $id_encuesta,
            $id_encuesta
        ));        

        if( $queryRta-> errorCode() == '00000'  ){

             $this->responseObj->error    = 0;
             $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

        }else{

             $this->responseObj->error    = 2;
             $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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

// METODO QUE GUARDA LAS RESPUESTAS A LAS PREGUNTAS DE CADA FORMULARIO EN ESTADO FINALIZADO
public function eliminarEncuesta(
    $id_encuesta){ 

try {

     $db = $this->conexion->conectar($id_encuesta);

    if( $db != null ){


        $queryEliminar = $db -> prepare(
            /*UPSERT que permite actualiza o insertar según corresponda las respuestas de las encuestas*/

            "UPDATE encuestas
            SET estado = 'Eliminado'
            WHERE id_encuesta = ?;"
        );
      
        $queryEliminar -> execute( array(
            $id_encuesta
        ));        

        if( $queryEliminar-> errorCode() == '00000'  ){

             $this->responseObj->error    = 0;
             $this->responseObj->message  = "Operación realizada exitosamente." + strlen ($image);

        }else{

             $this->responseObj->error    = 2;
             $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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

//METODO QUE PERMITE TRAER DE LA DB LAS RISGISTROS DE ENCUESTAS QUE EXISTEN DEPENDIENDO DE LA ENCUESTA SELECCIONADA Y EL USUARIO 

public function loadEncuesta($id_app,$usuario){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryEncu = $db -> prepare("SELECT c.numero, c.coordinador, co.id_encuesta, co.numero_de_consignacion, 
            co.numero_grupo_trabajo, co.nombre_jefe_consignacion, co.fecha_encuesta, co.actividad_realizar, co.id_contrato 
                FROM encuestas  AS co
                INNER JOIN contrato AS c ON c.id_contrato = co.id_contrato 
                WHERE co.id_app = ? 
                AND co.id_usuario = ?
                AND (co.estado = 'Activo'
                OR co.estado = 'Editado')
                ORDER BY  co.id_encuesta DESC;");

            //SE EJEUCTA LA QUERY
            $queryEncu->execute(array($id_app, $usuario));

            $encues = $queryEncu->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($encues){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $encues;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

}

//METODO QUE TRAE LA TABLA USUROL
public function loadUsuarios(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_usuario]
            ,[nombre]
            ,[usuario]
            ,[clave]
            ,[estado]
            ,[cedula]
        FROM [dbo].[usuario]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

//METODO QUE TRAE LA TABLA USUROL
public function loadUsurol(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_usu_rol]
            ,[id_usuario]
            ,[id_rol]
        FROM [dbo].[usu_rol]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 
//METODO QUE TRAE LA TABLA APP
public function loadApp(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_app]
            ,[nombre]
            ,[descripcion]
            ,[estado]
            ,[parametrizable]
            ,[macroproceso]
            ,[proceso]
            ,[version]
            ,[codigo]
        FROM [dbo].[app]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 



//METODO QUE TRAE LA PREGUNTAS
public function loadPreguntas(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_pregunta]
            ,[orden_preguntas]
            ,[titulo]
            ,[descripcion]
            ,[imagen]
            ,[id_app]
        FROM [dbo].[preguntas]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 


//METODO QUE TRAE LA TABLA PREGOPC
public function loadPreguntasopc(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_pre_opc]
            ,[id_pregunta]
            ,[id_opcion_rta]
        FROM [dbo].[preg_opc]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 


public function loadOpcionesrta(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_opcion_rta]
            ,[orden_opciones]
            ,[titulo]
            ,[descripcion]
            ,[valor]
        FROM [dbo].[opciones_rta]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 


public function loadContratos(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_contrato]
            ,[numero]
            ,[empresa]
            ,[objetivo]
            ,[coordinador]
            ,[fecha_ini]
            ,[estado]
            ,[id_dependencia]
        FROM [dbo].[contrato]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

public function loadUsucont(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_usu_cont]
            ,[id_usuario]
            ,[id_contrato]
            ,[tipo_usu]
        FROM [dbo].[usu_cont]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

public function loadDependencia(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_dependencia]
            ,[codigo]
            ,[descripcion]
        FROM [dbo].[dependencia]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

//METODO QUE TRAE LOS ROLES
public function loadRoles(){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_rol]
            ,[nombre]
            ,[descripcion]
            ,[id_app]
            ,[tipo_rol]
        FROM [dbo].[roles]");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array());

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 
//METODO QUE TRAE LAS RESPUESTAS DE CADA PREGUNTA REGISTRADAS EN LA DB DEPENDIENDO DE CADA ENCUESTA
public function loadRtaPreguntas($id_encuesta){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT r.id_respuesta, r.respuesta, r.observacion, r.evidencia, r.id_encuesta, r.id_opcion_rta,
            p.id_pregunta, p.orden_preguntas,p.titulo, p.descripcion, p.imagen, p.id_app 
            FROM respuestas_enc AS r 
            INNER JOIN preguntas as p on p.id_pregunta = r.id_pregunta
            WHERE r.id_encuesta = ?  ");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array($id_encuesta));

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 




//METODO QUE TRAE LAS Firmas REGISTRADAS EN LA DB DEPENDIENDO DE CADA ENCUESTA
public function loadFirmas($id_encuesta){

    try {
        //SE REALIZA LA CONEXION A LA BASE DE DATOS
        $db = $this->conexion->conectar();

        //SE VALIDA SI HAY CONEXION
        if( $db != null ){

            //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
            $queryRtaPre = $db -> prepare("SELECT [id_Firma]
                  ,[Nombres]
                  ,[cedula]
                  ,[firma]
                  ,[id_encuesta]
                 
              FROM [dbo].[firmas]
               WHERE [id_encuesta]='$id_encuesta'
            ");

            //SE EJEUCTA LA QUERY
            $queryRtaPre->execute(array($id_encuesta));

            $rtaPreg = $queryRtaPre->fetchAll( PDO::FETCH_ASSOC );

            //SI EXISTEN EVENTOS 
            if($rtaPreg){
                //TRAE LOS EVENTOS QUE EXISTEN
                $this->responseObj->error    = 0;
                $this->responseObj->message  = $rtaPreg;

            }else{
                //EN CASO DE QUE NO EXISTAN EVENTOS
                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }

        }else{
            //EN CASO DE QUE EXISTA UN ERROR DE CONEXION A LA BD
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";

        }
        
        return  $this->responseObj;



    } catch (PDOException $e) {
        //SE LLEGA A EXISTIR UN ERROR DE PDO, AQUI SE MANEJAN 
        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;

    }

} 

// METODO QUE PERMITE EDITAR EN ENCABEZADO DE LOS FORMULARIOS O ENCUESTAS
public function editarEncabezadoEncuesta(
    $numeroCuadrilla,
    $numeroConsignacion,
    $nombreJefe,
    $actividad,
    $fechaEncuesta,
    $contrato,
    $id_encuesta 
){         

try {

    $db = $this->conexion->conectar();

    if( $db != null ){

        $fechaSistema = date("Y-m-d");


        $queryEditEnc = $db -> prepare(
            "UPDATE encuestas 
            SET numero_grupo_trabajo = ?, numero_de_consignacion = ?, nombre_jefe_consignacion= ?, 
            actividad_realizar= ?, fecha_sistema = ?, fecha_encuesta = ?, id_contrato = ?, estado = 'Editado'
            WHERE id_encuesta = ?;"
        );

        $queryEditEnc -> execute( array( 
            $numeroCuadrilla,
            $numeroConsignacion,
            $nombreJefe,
            $actividad,
            $fechaSistema,
            $fechaEncuesta,
            $contrato,
            $id_encuesta 
        ));

        if( $queryEditEnc-> errorCode() == '00000'  ){

            $this->responseObj->error    = 0;
            $this->responseObj->message  = "Operación realizada exitosamente.";

        }else{

            $this->responseObj->error    = 2;
            $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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

// METODO QUE PER5MITE ACTUALIZAR EL ENCABEZADO DE LAS ENCUESTAS Y CAMBIA EL ESTADO A FINALIZADO
public function finalizarEncabezadoEncuesta(
    $numeroCuadrilla,
    $numeroConsignacion,
    $nombreJefe,
    $actividad,
    $fechaEncuesta,
    $contrato,
    $id_encuesta 
){         

try {

    $db = $this->conexion->conectar();

    if( $db != null ){

        $fechaSistema = date("Y-m-d");


        $queryFinalizarEnc = $db -> prepare(
            "UPDATE encuestas 
            SET numero_grupo_trabajo = ?, numero_de_consignacion = ?, nombre_jefe_consignacion= ?, 
            actividad_realizar= ?, fecha_sistema = ?, fecha_encuesta = ?, id_contrato = ?, estado = 'Finalizado'
            WHERE id_encuesta = ?;"
        );

        $queryFinalizarEnc -> execute( array( 
            $numeroCuadrilla,
            $numeroConsignacion,
            $nombreJefe,
            $actividad,
            $fechaSistema,
            $fechaEncuesta,
            $contrato,
            $id_encuesta 
        ));

        if( $queryFinalizarEnc-> errorCode() == '00000'  ){

            $this->responseObj->error    = 0;
            $this->responseObj->message  = "Operación realizada exitosamente.";

        }else{

            $this->responseObj->error    = 2;
            $this->responseObj->message  = "Operación no realizada.";
        }

    } else{

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

//METODO QUE PERMITE EDITAR CADA PREGUNTA DEL FORMULARIO

public function editarRtasEncuesta(
    $respuesta,
    $observacion,
    $image,
    $opcion,
    $pregunta,
    $id_encuesta
    
){         
    $evidencia = str_replace ( " " , "+" , $image );

    try {

         $db = $this->conexion->conectar();

        if( $db != null ){

             $queryPregEnc = $db -> prepare("SELECT  id_pregunta 
             FROM respuestas_enc WHERE id_pregunta = ? AND  id_encuesta = ?;"
            );

            $queryPregEnc -> execute( array(
                                      $pregunta,
                                      $id_encuesta
                                    ));

           $encuestaRta = $queryPregEnc->fetchAll( PDO::FETCH_ASSOC ); 
        
            if($encuestaRta != 'null' && !empty($encuestaRta) && isset($encuestaRta)){
                
                $queryRta = $db -> prepare(
                             "UPDATE RESPUESTAS_ENC 
                             SET  RESPUESTA= ?, OBSERVACION = ?, EVIDENCIA = ?, ID_OPCION_RTA = ?
                             WHERE id_pregunta = ? 
                             AND id_encuesta = ?;"
                              );

                $queryRta -> execute( array(
                                  $respuesta,
                                  $observacion,
                                  $evidencia,
                                  $opcion,
                                  $pregunta,
                                  $id_encuesta
                                ));
    
            }else{

                 $queryRta = $db -> prepare("INSERT INTO RESPUESTAS_ENC (respuesta,observacion,evidencia,id_opcion_rta,id_pregunta,id_encuesta) 
                                             VALUES (?,?,?,?,?,?);"
                                           );

                $queryRta -> execute( array(
                                             $respuesta,
                                             $observacion,
                                             $image,
                                             $opcion,
                                             $pregunta,
                                             $id_encuesta
                                        ));
            }

            if( $queryRta-> errorCode() == '00000'  ){
                 $this->responseObj->error    = 0;
                 $this->responseObj->message  = "Operación realizada exitosamente.";

            }else{
                 $this->responseObj->error    = 2;
                 $this->responseObj->message  = "Operación no realizada.";
            }

        } else{
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

//METODO QUE CARGA EL CONTENIDO DEL HOME
public function contenidoHome(){

    try {
        $db = $this->conexion->conectar();

        if($db != null ){

            $queryApp = $db -> prepare(
                "SELECT id_parametrizacion,
                titulo,
                subtitulo,
                imagen,
                descripcion,
                activo
                FROM parametrizacion 
                WHERE activo = 'S';"
            );

            $queryApp->execute();

            $apps = $queryApp->fetchAll( PDO::FETCH_ASSOC );

            if($apps){

                $this->responseObj->error    = 0;
                $this->responseObj->message  = $apps;

            }else{

                $this->responseObj->error    = 2;
                $this->responseObj->message  = "No se encontraron registros.";

            }
        }else{
            $this->responseObj->error    = 3;
            $this->responseObj->message  = "Error al conectar con el gestor de base de datos";
        }

        return  $this->responseObj;

    } catch ( PDOException $e) {

        $this->responseObj->error    = 1;
        $this->responseObj->message  = $e->getMessage();
         
        return $this->responseObj;
    }
}

}

?>