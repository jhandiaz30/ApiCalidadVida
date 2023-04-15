<?php

//SE IMPORTA EL ARCHIVO DE CONEXION A LA BASE DE DATOS
require_once 'config/conexionSqlsrv.php';

class Accidentalidad{

    // $fechaIli = getdate();

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

    //SE CARGA EL COMBO DE SELECCIONAR EVENTO EN ACCIDENTES
    public function loadEvento(){

        try {
            //SE REALIZA LA CONEXION A LA BASE DE DATOS
            $db = $this->conexion->conectar();

            //SE VALIDA SI HAY CONEXION
            if( $db != null ){

                //EN CASO DE HABER CONEXION SE REALIZA LA QUERY
                $queryEvent = $db -> prepare("SELECT * FROM ili_tipo_eve");

                //SE EJEUCTA LA QUERY
                $queryEvent->execute();

                $eventos = $queryEvent->fetchAll( PDO::FETCH_ASSOC );

                //SI EXISTEN EVENTOS 
                if($eventos){
                    //TRAE LOS EVENTOS QUE EXISTEN
                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $eventos;

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

    //SE CARGA EL COMBO DE SELECCIONAR MUNICIPIO EN ACCIDENTES
    public function loadMunicipio(){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryMunicipio = $db -> prepare("SELECT * FROM ili_municipio");

                $queryMunicipio->execute();

                $municipios = $queryMunicipio->fetchAll( PDO::FETCH_ASSOC );

                if($municipios){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $municipios;

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

    //SE CARGA EL COMBO DE SELECCIONAR CLASE ACC EN ACCIDENTES
    Public function loadClaseAcc(){

        try {
            $db = $this->conexion->conectar();

            if($db != null ){

                $queryClase = $db -> prepare("SELECT * FROM ili_clase_acc");

                $queryClase->execute();

                $claseAcc = $queryClase->fetchAll( PDO::FETCH_ASSOC );

                if($claseAcc){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $claseAcc;

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

    //SE CARGA EL COMBO DE SELECCIONAR CONTRATO EN ACCIDENTES Y
    public function loadContrato( $id_user ){

        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryContrato = $db -> prepare(
                    "SELECT C.ID_CONTRATO, C.NUMERO, C.EMPRESA  FROM USU_CONT AS US
					INNER JOIN USUARIO AS U ON U.ID_USUARIO = US.ID_USUARIO
					INNER JOIN  CONTRATO AS C ON C.ID_CONTRATO = US.ID_CONTRATO
					WHERE U.ID_USUARIO = ? ;"
                );

                $queryContrato -> execute( array( $id_user ) );
        
                $cont = $queryContrato->fetchAll( PDO::FETCH_ASSOC );

                if( $cont ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $cont;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontro contratos";
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

    //METODO QUE INSERTA A LA BD LOS DATOS DE LOS FORMULARIOS DE ACCIDENTES 
    //SE PASAN LOS DATOS QUE IRAN A SER INSERTADOS EN LA BD SEGUN LA QUERY REALIZADA
    public function saveAccidente(
        $tipoEvento,
        $claseEvento,
        $municipio,
        $zona,
        $claseAccidente,
        $poblacion,
        $cedula,
        $nombreAccidentado,
        $cargoAccidentado,
        $trabajoNormal,
        $lugarAccidente,
        $fechaAccidente,
        $parteCuerpoAfectado,
        $descripcion,
        $nombreReportador,
        $cargoReportador,
        $fechaReporte,
        $estadoReporte,
        $adjunto,
        $contrato,
        $usuario
    ){
        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryContrato = $db -> prepare(
                    "INSERT INTO ili_accidentes( id_tipo_eve, clase_eve, id_municipio, zona, 
                    id_clase_acc, poblacion, cedula_acc, nom_accidentado, cargo_accidentado, 
                    t_normal, lugar_acc, fecha_acc, p_cuerpo_afec, descripcion_acc, nom_reporta, 
                    cargo_reporta, fh_reporte, estado_reporte, adjunto, id_contrato, id_usuario) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);"
                );
                /*AQUI SE PASAN LOS DATOS QUE SERAN INSERTADOS EN LA BD, IMPORTANTE RESPETAR EL ORDEN 
                EN COMO SE INSERTAR EN LA QUERY AQUI LOS PARAMETROS TAMBIEN DEBEN RESPETAR ESE ORDEN*/
                $queryContrato -> execute( array( 
                    $tipoEvento,
                    $claseEvento,
                    $municipio,
                    $zona,
                    $claseAccidente,
                    $poblacion,
                    $cedula,
                    $nombreAccidentado,
                    $cargoAccidentado,
                    $trabajoNormal,
                    $lugarAccidente,
                    $fechaAccidente,
                    $parteCuerpoAfectado,
                    $descripcion,
                    $nombreReportador,
                    $cargoReportador,
                    $fechaReporte,
                    $estadoReporte,
                    $adjunto,
                    $contrato,
                    $usuario
                ) );
        
                // $cont = $queryContrato->fetchAll( PDO::FETCH_ASSOC );

                if( $queryContrato-> errorCode() == '00000'  ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = "Operación realizada exitosamente.";

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "Operación no realizada.";
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

    /*SE CARGA EL COMBO DE SELECCIONAR EL CONTRATO Y APARTIR DE ESO CARGA INMEDIATAMENTE EL 
    ADMINISTRADOR Y LA DEPENDENCIA*/ 
    public function loadAdminDepend( $id_contrato ){

        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryAdminDepend = $db -> prepare(

                    "SELECT C.COORDINADOR AS N_COORDINADOR,  C.FECHA_INI AS FECHA_INICIAL, 
                    EOMONTH(GETDATE()) AS FECHA_FINAL, D.DESCRIPCION AS N_DEPENDENCIA FROM CONTRATO AS C
                    INNER JOIN DEPENDENCIA AS D ON C.ID_DEPENDENCIA = D.ID_DEPENDENCIA
                    WHERE C.ID_CONTRATO = ? AND C.ESTADO = 'Activo';"
                );

                $queryAdminDepend -> execute( array( $id_contrato ) );
        
                $adminDepend = $queryAdminDepend->fetchAll( PDO::FETCH_ASSOC );

                if( $adminDepend ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $adminDepend;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontro contratos";
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


    //METODO QUE INSERTA A LA BD LOS DATOS DEL FORMULARIO DE ILI
    public function saveIli(
        $numeroCont,
        $usuario,
        $accidentesIncapacitantes,
        $accidentesNoIncapacitantes,
        $diasPerdidos,
        $horasHombres,
        $periodoContrato,
        $id_ili           
                            
    ){
        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                //SE OBTIENE LA FECHA DEL SISTEMA
                $fechaSistema = date("Y-m-d");

                $frecuencia = (($accidentesIncapacitantes + $accidentesNoIncapacitantes) * 250000) / $horasHombres;

                $severidad = ( $diasPerdidos * 250000 ) / $horasHombres;

                if ($severidad==0 ){
                    $ili=0;

                }else{  
                    $ili = ($frecuencia / $severidad) * 1000;

                }

                if( $id_ili != 'null' && !empty($id_ili) && isset($id_ili) ){

                    $queryIli = $db -> prepare(
                        "UPDATE ili_ili SET fecha_sistema = ?, acc_incap = ?, acc_noincap = ?, 
                        dias_perdidos = ?, horas_ht = ?, periodo = ?, severidad = ?, frecuencia = ?, ili = ?
                        WHERE id_ili = ?;"
                    );

                    $queryIli -> execute( array( 
                        $fechaSistema,
                        $accidentesIncapacitantes,
                        $accidentesNoIncapacitantes,
                        $diasPerdidos,
                        $horasHombres,
                        $periodoContrato,
                        $severidad,
                        $frecuencia,
                        $ili,
                        $id_ili
                    ));

                }else{

                    $queryIli = $db -> prepare(
                        "INSERT INTO ili_ili (id_contrato, id_usuario, fecha_sistema, acc_incap, 
                        acc_noincap, dias_perdidos, horas_ht, periodo, severidad, frecuencia, ili) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?);"
                    );

                    $queryIli -> execute( array( 
                        $numeroCont,
                        $usuario,
                        $fechaSistema,
                        $accidentesIncapacitantes,
                        $accidentesNoIncapacitantes,
                        $diasPerdidos,
                        $horasHombres,
                        $periodoContrato,
                        $severidad,
                        $frecuencia,
                        $ili
                    ));

                }

                if( $queryIli-> errorCode() == '00000'  ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = "Operación realizada exitosamente.";

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "Operación no realizada.";
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

    //METODO QUE CARGA EL LOS DATOS DE UN CONTRATO SELECCIONADO APARTIR DEL AÑO Y MES DEL PERIODO DEL MISMO
    public function verificarIli( $id_contrato, $anio, $mes ){
        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryIli = $db -> prepare(
                    "SELECT * FROM ILI_ILI 
                    WHERE ID_CONTRATO = ? AND SUBSTRING(PERIODO,1,4) = ? AND SUBSTRING(PERIODO,6,2) = ? "
                );

                $queryIli -> execute( array( $id_contrato, $anio, $mes ) );
        
                $ili = $queryIli->fetchAll( PDO::FETCH_ASSOC );

                if( $ili ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $ili;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontro registro de ili";
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

    //METODO QUE CARGA LOS ILI REALIZADOS LOS ULTIMOS SEIS MESES AL CONTRATO SELECCIONADO
    public function loadEstadisticas( $id_contrato ){

        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryEstadistica = $db -> prepare(

                    "SELECT x.MES ,ISNULL(ili_ili.ili,0) AS ILI FROM (
                        SELECT CONCAT( FORMAT( DATEADD(MM, 0, GETDATE()), 'yyyy' ), '-',  
                        FORMAT(  DATEADD(MM, 0, GETDATE()) , 'MM')  )AS PE,  
                        FORMAT(  DATEADD(MM, 0, GETDATE()) , 'MM') AS MES 
                        UNION 
                        SELECT CONCAT( FORMAT( DATEADD(MM, -1, GETDATE()), 'yyyy' ), '-',  
                        FORMAT(  DATEADD(MM, -1, GETDATE()) , 'MM')   )AS PE ,  
                        FORMAT(  DATEADD(MM, -1, GETDATE()) , 'MM') AS MES 
                        UNION 
                        SELECT CONCAT( FORMAT( DATEADD(MM, -2, GETDATE()), 'yyyy' ), '-',  
                        FORMAT(  DATEADD(MM, -2, GETDATE()) , 'MM')   )AS PE ,  
                        FORMAT(  DATEADD(MM, -2, GETDATE()) , 'MM') AS MES 
                        UNION 
                        SELECT CONCAT( FORMAT( DATEADD(MM, -3, GETDATE()), 'yyyy' ), '-',  
                        FORMAT(  DATEADD(MM, -3, GETDATE()) , 'MM')   )AS PE, 
                        FORMAT(  DATEADD(MM, -3, GETDATE()) , 'MM') AS MES 
                        UNION 
                        SELECT CONCAT( FORMAT( DATEADD(MM, -4, GETDATE()), 'yyyy' ), '-',
                        FORMAT(  DATEADD(MM, -4, GETDATE()) , 'MM')  )AS PE,
                        FORMAT(  DATEADD(MM, -4, GETDATE()) , 'MM') AS MES 
                        UNION 
                        SELECT CONCAT( FORMAT( DATEADD(MM, -5, GETDATE()), 'yyyy' ), '-',  
                        FORMAT(  DATEADD(MM, -5, GETDATE()) , 'MM') ) AS PE,
                        FORMAT(  DATEADD(MM, -5, GETDATE()) , 'MM') AS MES
                    ) as x
                    LEFT JOIN ili_ili 
                    ON periodo = x.PE
                    AND id_contrato = ?;"
                );

                $queryEstadistica -> execute( array( $id_contrato ) );
        
                $estadisticas = $queryEstadistica->fetchAll( PDO::FETCH_ASSOC );

                if( $estadisticas ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $estadisticas;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontro contratos";
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


    public function consultarAccidente( $id_user ){
        try {

            $db = $this->conexion->conectar();

            if( $db != null ){

                $queryAci = $db -> prepare(
                    "SELECT  * FROM ili_accidentes WHERE id_usuario = ?
                    ORDER BY DATEPART(year, fecha_acc) DESC, DATEPART(month, fecha_acc) DESC,
                    DATEPART(day, fecha_acc) DESC;"
                );

                $queryAci -> execute( array( $id_user  ) );
        
                $accidente = $queryAci->fetchAll( PDO::FETCH_ASSOC );

                if( $accidente ){

                    $this->responseObj->error    = 0;
                    $this->responseObj->message  = $accidente;

                }else{

                    $this->responseObj->error    = 2;
                    $this->responseObj->message  = "No se encontro registro de accidentes";
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

        public function mostAccidente( $id_accidente ){
            try {
    
                $db = $this->conexion->conectar();
    
                if( $db != null ){
    
                    $queryAci = $db -> prepare(
                        "SELECT  * FROM ili_accidentes WHERE id_accidente = ?;"
                    );
    
                    $queryAci -> execute( array( $id_accidente  ) );
            
                    $accidente = $queryAci->fetchAll( PDO::FETCH_ASSOC );
    
                    if( $accidente ){
    
                        $this->responseObj->error    = 0;
                        $this->responseObj->message  = $accidente;
    
                    }else{
    
                        $this->responseObj->error    = 2;
                        $this->responseObj->message  = "No se encontro registro de accidentes";
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


    public function editAccidente(
        $tipoEvento,
        $claseEvento,
        $municipio,
        $zona,
        $claseAccidente,
        $poblacion,
        $cedula,
        $nombreAccidentado,
        $cargoAccidentado,
        $trabajoNormal,
        $lugarAccidente,
        $fechaAccidente,
        $parteCuerpoAfectado,
        $descripcion,
        $nombreReportador,
        $cargoReportador,
        $fechaReporte,
        $estadoReporte,
        $adjunto,
        $contrato,
        $usuario,
        $id_accidente
){
try {

$db = $this->conexion->conectar();

if( $db != null ){

$queryContrato = $db -> prepare(
"UPDATE  ili_accidentes SET id_tipo_eve = ?, clase_eve = ?, id_municipio= ?, zona= ?, id_clase_acc= ?, 
poblacion= ?, cedula_acc= ?, nom_accidentado= ?, cargo_accidentado= ?, t_normal= ?, lugar_acc= ?, fecha_acc= ?,
p_cuerpo_afec= ?, descripcion_acc= ?, nom_reporta= ?, cargo_reporta= ?, fh_reporte= ?, estado_reporte= ?,
adjunto= ?, id_contrato= ?, id_usuario= ?
 WHERE id_accidente = ?;"
);
/*AQUI SE PASAN LOS DATOS QUE SERAN INSERTADOS EN LA BD, IMPORTANTE RESPETAR EL ORDEN EN COMO SE 
INSERTAR EN LA QUERY AQUI LOS PARAMETROS TAMBIEN DEBEN RESPETAR ESE ORDEN*/
$queryContrato -> execute( array( 
    $tipoEvento,
    $claseEvento,
    $municipio,
    $zona,
    $claseAccidente,
    $poblacion,
    $cedula,
    $nombreAccidentado,
    $cargoAccidentado,
    $trabajoNormal,
    $lugarAccidente,
    $fechaAccidente,
    $parteCuerpoAfectado,
    $descripcion,
    $nombreReportador,
    $cargoReportador,
    $fechaReporte,
    $estadoReporte,
    $adjunto,
    $contrato,
    $usuario,
    $id_accidente
) );

// $cont = $queryContrato->fetchAll( PDO::FETCH_ASSOC );

if( $queryContrato-> errorCode() == '00000'  ){

$this->responseObj->error    = 0;
$this->responseObj->message  = "Operación realizada exitosamente.";

}else{

$this->responseObj->error    = 2;
$this->responseObj->message  = "Operación no realizada.";
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