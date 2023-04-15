<?php

date_default_timezone_set("America/Bogota");
header('content-type: application/json; charset=utf-8');

/* Permitir peticiones desconocidas para desarrollo */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];

if($method == "OPTIONS") {
    die();
}

/*SE IMPORTAN LIBRERIAS Y CONTROLADORES*/
require_once    'vendor/autoload.php';
require_once    'controller/accidentalidad.php';
require_once    'auth/login.php';
require_once    'controller/encuesta.php';

//Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];

/*Se Inicializa  la aplicación SLIM */
$app = new \Slim\App($config);

//METODO PARA LA AUTENTICACION
$app->post('/login', function ( $request, $response) {
    //SE RECUPERAN LOS DATYOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA UN OBJETO LOGIN
    $login = new Login();
    //SE INVOCA EL METODO LOGIN PASANDO EN USER Y PASSWORD
    $rta = $login->loginUser( $data['user'], $data['password']);
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

// **********************************AQUI INICIAN LOS METODOS PARA ACCIDENTALIDAD***************************

//METODO PARA CARGAR EL EVENTO
$app->post('/loadEvent', function ( $request, $response) {
    //SE INSTANCIA EL OBJETO 
    $event = new Accidentalidad();

    $rta = $event->loadEvento();

    return $response->withJson($rta);

});

//METODO PARA CARGAR EL MUNICIPIO
$app->post('/loadMunicipio', function ( $request, $response ){

    $municipio = new Accidentalidad();

    $rta = $municipio->loadMunicipio();

    return $response->withJson($rta);
});

//METODO PARA CARGAR LA CLASE
$app->post('/loadClase', function ( $request, $response ){

    $clase = new Accidentalidad();

    $rta = $clase->loadClaseAcc();

    return $response->withJson($rta);
});

//METODO PARA CARGAR EL CONTRATO
$app->post('/loadContrato', function ( $request, $response ) {

    $data = $request->getParams();

    $contrato =  new Accidentalidad();

    $rta = $contrato->loadContrato( $data['id_user'] );

    return $response->withJson($rta);

});


//METODO QUE GUARDA LOS DATOS DEL FORMULARIO DE ACCIDENTES
$app->post('/saveAccidente', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveAcci = new Accidentalidad();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveAcci->saveAccidente( 
                                    $data['tipoEvento'],
                                    $data['claseEvento'],
                                    $data['municipio'],
                                    $data['zona'],
                                    $data['claseAccidente'],
                                    $data['poblacion'],
                                    $data['cedula'],
                                    $data['nombreAccidentado'],
                                    $data['cargoAccidentado'],
                                    $data['trabajoNormal'],
                                    $data['lugarAccidente'],
                                    $data['fechaAccidente'],
                                    $data['parteCuerpoAfectado'],
                                    $data['descripcion'],
                                    $data['nombreReportador'],
                                    $data['cargoReportador'],
                                    $data['fechaReporte'],
                                    $data['estadoReporte'],
                                    $data['adjunto'],
                                    $data['contrato'],
                                    $data['usuario']
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

//METODO QUE CARGA EL ADMINISTRADOR Y DEPENDENCIA 
$app->post('/loadAdminDepend', function ( $request, $response ) {

    $data = $request->getParams();

    $adminDepend =  new Accidentalidad();

    $rta = $adminDepend->loadAdminDepend( $data['id_contrato'] );

    return $response->withJson($rta);

});

//METODO QUE VERIFICA EL ILI
$app->post('/verificarIli', function ( $request, $response ) {

    $data = $request->getParams();

    $iliContrato =  new Accidentalidad();

    $rta = $iliContrato->verificarIli( 
                                        $data['id_contrato'],
                                        $data['anio'],
                                        $data['mes'] 
    );

    return $response->withJson($rta);

});

//METODO QUE GUARDA TODOS LOS DATOS DEL FORMULARIO DE ILI
$app->post('/saveIli', function ($request, $response){

        //SE RECUPERAN LOS DATOS ENVIADOS POR POST
        $data = $request->getParams();
        //SE INSTANCIA EL OBJETO 
        $saveIndicador = new Accidentalidad();

        $rta = $saveIndicador->saveIli(
                                $data['numeroCont'],
                                $data['usuario'],
                                $data['accidentesIncapacitantes'],
                                $data['accidentesNoIncapacitantes'],
                                $data['diasPerdidos'],
                                $data['horasHombres'],
                                $data['periodoContrato'],
                                $data['id_ili']
        );

        return $response->withJson($rta);
});

//METODO QUE PERMITE CARGAR LAS ESTADISTICAS DE CADA CONTRATO SEGUN EL SELECCIONADO
$app->post('/loadEstadisticas', function ( $request, $response ) {

    $data = $request->getParams();

    $estadisticas =  new Accidentalidad();

    $rta = $estadisticas->loadEstadisticas( $data['id_contrato'] );

    return $response->withJson($rta);

});


//METODO PARA CONSULTAR ACCIDENTES
$app->post('/consultarAccidente', function ( $request, $response ) {

    $data = $request->getParams();

    $accidente =  new Accidentalidad();

    $rta = $accidente->consultarAccidente( $data['id_user'] );

    return $response->withJson($rta);

});

//METODO PARA MOSTRAR EL accidente
$app->post('/mostAccidente', function ( $request, $response ) {

    $data = $request->getParams();

    $accidente =  new Accidentalidad();

    $rta = $accidente->mostAccidente( $data['id_accidente'] );

    return $response->withJson($rta);

});

//METODO PARA EDITAR EL ACCIDENTE
$app->post('/editAccidente', function ($request, $response){
    
    $data = $request->getParams();
    
    $saveAcci = new Accidentalidad();
    
    $rta = $saveAcci->editAccidente( 
                                    $data['tipoEvento'],
                                    $data['claseEvento'],
                                    $data['municipio'],
                                    $data['zona'],
                                    $data['claseAccidente'],
                                    $data['poblacion'],
                                    $data['cedula'],
                                    $data['nombreAccidentado'],
                                    $data['cargoAccidentado'],
                                    $data['trabajoNormal'],
                                    $data['lugarAccidente'],
                                    $data['fechaAccidente'],
                                    $data['parteCuerpoAfectado'],
                                    $data['descripcion'],
                                    $data['nombreReportador'],
                                    $data['cargoReportador'],
                                    $data['fechaReporte'],
                                    $data['estadoReporte'],
                                    $data['adjunto'],
                                    $data['contrato'],
                                    $data['usuario'],
                                    $data['id_accidente']
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

// *************************************AQUI FINALIZAN LOS METODOS PARA ACCIDENTALIDAD***************************

//*************************************METODOS PARA ENCUESTA***********************/

//METODO QUE CARGA LAS ENCUESTAS SEGUN EL USUARIO Y SU ROL
$app->post('/nombreApp', function ( $request, $response ){

    $data = $request->getParams();

    $app = new Encuesta();

    $rta = $app->nombreApp($data['id_user']);

    return $response->withJson($rta);
});

//METODO QUE CARGA LAS PREGUNTAS SEGUN LA ENCUESTA SELECCIONADA
$app->post('/loadPregunta', function ( $request, $response ){

    $data = $request->getParams();
    $encuesta = new Encuesta();
    $rta = $encuesta->loadPregunta( $data['id_app'] );
    return $response->withJson($rta);
});

//METODO QUE CARGA LOS DATOS DEL USUARIO TABLA USUARIO
$app->post('/loadUsuario', function ( $request, $response ){

    $data = $request->getParams();
    $encuesta = new Encuesta();
    $rta = $encuesta->loadUsuario( $data['id_user'] );
    return $response->withJson($rta);
});

//METODO QUE CARGA LOS DATOS DEL ENCABEZADO TABLA APP
$app->post('/loadEncabezado', function ( $request, $response ){

    $data = $request->getParams();
    $encuesta = new Encuesta();
    $rta = $encuesta->loadEncabezado( $data['id_app'] );
    return $response->withJson($rta);
});

//METODO QUE CARGA LAS OPCIONES DE RESPUESTA PARA CADA PREGUNTA
$app->post('/loadOpcionRta', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadOpcionRta( $data['id_pregunta'] );
    return $response->withJson($rta);
});

//METODO QUE TRAE LOS USUARIOS
$app->post('/loadUsurol', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadUsurol();
    return $response->withJson($rta);
});

$app->post('/loadRoles', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadRoles();
    return $response->withJson($rta);
});


$app->post('/loadPreguntas', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadPreguntas();
    return $response->withJson($rta);
});

$app->post('/loadPreguntasopc', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadPreguntasopc();
    return $response->withJson($rta);
});

$app->post('/loadOpcionesrta', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadOpcionesrta();
    return $response->withJson($rta);
});

$app->post('/loadContratos', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadContratos();
    return $response->withJson($rta);
});
$app->post('/finalizarEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveEnc = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ENCABEZADO y SE PASAN LOS DATOS
    $rta = $saveEnc->finalizarEncuesta(
                                    
                                   
                                    $data['numeroCuadrilla'],
                                    $data['numeroConsignacion'],
                                    $data['nombreJefe'],
                                    $data['actividad'],
                                    $data['fechaEncuesta'],
                                    $data['numeroCont'],
                                    $data['nombreEnc'],
                                    $data['usuario']
                                                                                           
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});
$app->post('/loadUsucont', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadUsucont();
    return $response->withJson($rta);
});

$app->post('/loadDependencia', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadDependencia();
    return $response->withJson($rta);
});


$app->post('/loadApp', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadApp();
    return $response->withJson($rta);
});


$app->post('/loadUsuarios', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadUsuarios();
    return $response->withJson($rta);
});

//METODO QUE CARGA LAS OPCIONES DE RESPUESTA PARA CADA PREGUNTA
$app->post('/loadFirmas', function ( $request, $response ){

    $data = $request->getParams();
    $pregu = new Encuesta();
    $rta = $pregu->loadFirmas( $data['id_encuesta'] );
    return $response->withJson($rta);
});



//METODO QUE GUARDA LOS DATOS DEL ENCABEZADO DEL FORMULARIO
$app->post('/saveEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveEnc = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ENCABEZADO y SE PASAN LOS DATOS
    $rta = $saveEnc->saveEncuesta(
                                    
                                   
                                    $data['numeroCuadrilla'],
                                    $data['numeroConsignacion'],
                                    $data['nombreJefe'],
                                    $data['actividad'],
                                    $data['fechaEncuesta'],
                                    $data['numeroCont'],
                                    $data['nombreEnc'],
                                    $data['usuario']
                                                                                           
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});
$app->post('/guardarFirma', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveRespuesta = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveRespuesta->guardarFirma(
                                    $data['Nombres'],
                                    $data['cedula'],                
                                    $data['firma'], 
                                    $data['id_encuesta']
                                                                                                                    
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

$app->post('/eliminarFirma', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveRespuesta = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveRespuesta->eliminarFirma(
                                    $data['Nombres'],
                                    $data['cedula'],                
                                    $data['firma'], 
                                    $data['id_encuesta']
                                                                                                                    
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});


$app->post('/saveRta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveRespuesta = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveRespuesta->saveRta(
                                    $data['respuesta'],
                                    $data['observacion'],                
                                    $data['image'], 
                                    $data['pregunta'],
                                    $data['opcion'],
                                    $data['id_encuesta']
                                    
                                                                                      
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});




$app->post('/saveRtaFinal', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveRespuesta = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveRespuesta->saveRtaFinal(
                                    $data['respuesta'],
                                    $data['observacion'],                
                                    $data['image'], 
                                    $data['pregunta'],
                                    $data['opcion'],
                                    $data['id_encuesta']
                                    
                                                                                      
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

//Metodo que ejecuta el proeso de eliminar Encuestas
$app->post('/eliminarEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $saveRespuesta = new Encuesta();
    //SE INVOCA EL METODO DE GUARDADO DEL ACCIDENTE y SE PASAN LOS DATOS
    $rta = $saveRespuesta->eliminarEncuesta(
                                
                                    $data['id_encuesta']
                                    
                                                                                      
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

  // METODO QUE ME TRAE LA ULTIMA ENCUESTA REGISTRADA
$app->post('/loadTraerEncuesta', function ( $request, $response ){

    $data = $request->getParams();

    $encuesta = new Encuesta();

    $rta = $encuesta->loadTraerEncuesta($data['id_user']);

    return $response->withJson($rta);
});


$app->post('/loadEncuestaFinalizada', function ( $request, $response ){

    $data = $request->getParams();

    $encuesta = new Encuesta();

    $rta = $encuesta->loadEncuestaFinalizada($data['id_encuesta']);

    return $response->withJson($rta);
});

$app->post('/backup', function ( $request, $response ){

    $data = $request->getParams();

    $encuesta = new Encuesta();

    $rta = $encuesta->backup($data['fecha'], $data['accion'], $data['id_usuario']);

    return $response->withJson($rta);
});


$app->post('/loadTraerEncuestaFinalizada', function ( $request, $response ){

    $data = $request->getParams();

    $encuesta = new Encuesta();

    $rta = $encuesta->loadTraerEncuestaFinalizada($data['id_user'] );

    return $response->withJson($rta);
});

$app->post('/loadTraerEncuestaFinalizada2', function ( $request, $response ){

    $data = $request->getParams();

    $encuesta = new Encuesta();

    $rta = $encuesta->loadTraerEncuestaFinalizada2($data['numero']);

    return $response->withJson($rta);
});


//METODO QUE TRAE TODOS LOS DATOS DE LAS ENCUESTAS YA REGISTRADAS EN LA DB
$app->post('/loadEncuesta', function ( $request, $response) {

    $data = $request->getParams();
    
    $encu = new Encuesta();

    $rta = $encu->loadEncuesta($data['id_app'],
                             $data['usuario']);

    return $response->withJson($rta);

});

//METODO QUE TRAE LAS RESPUESTAS A LAS PREGUNTAS GUARDADAS EN LA DB
$app->post('/loadRtaPreguntas', function ( $request, $response ) {

    $data = $request->getParams();

    $rtaPreg =  new Encuesta();

    $rta = $rtaPreg->loadRtaPreguntas( $data['id_encuesta'] );

    return $response->withJson($rta);

});

//METODO QUE RECIBE LOS DATOS A EDITAR DEL ENCABEZADO
$app->post('/editarEncabezadoEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $editEnc = new Encuesta();
    
    $rta = $editEnc->editarEncabezadoEncuesta(
                                    
                                   
                                    $data['numeroCuadrilla'],
                                    $data['numeroConsignacion'],
                                    $data['nombreJefe'],
                                    $data['actividad'],
                                    $data['fechaEncuesta'],
                                    $data['numeroCont'],
                                    $data['id_encuesta']
                                  
                                                                                           
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

//METODO QUE RECIBE LOS DATOS A FINALIZAR DEL ENCABEZADO
$app->post('/finalizarEncabezadoEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $editEnc = new Encuesta();
    
    $rta = $editEnc->finalizarEncabezadoEncuesta(
                                    
                                   
                                    $data['numeroCuadrilla'],
                                    $data['numeroConsignacion'],
                                    $data['nombreJefe'],
                                    $data['actividad'],
                                    $data['fechaEncuesta'],
                                    $data['numeroCont'],
                                    $data['id_encuesta']
                                  
                                                                                           
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

//METODO QUE RECIBE LOS DATOS A EDITAR DE CADA RESPUESTA DE LAS PREGUNTAS
$app->post('/editarRtasEncuesta', function ($request, $response){
    //SE RECUPERAN LOS DATOS ENVIADOS POR POST
    $data = $request->getParams();
    //SE INSTANCIA EL OBJETO 
    $editarRtas = new Encuesta();
    
    $rta = $editarRtas->editarRtasEncuesta(
                                    $data['respuesta'],
                                    $data['observacion'],                
                                    $data['image'], 
                                    $data['opcion'],
                                    $data['pregunta'],
                                    $data['id_encuesta']
                                    
                                    
                                                                                      
    );
    //SE RETORNA UNA RESPUESTA AL CLIENTE
    return $response->withJson($rta);
});

//METODO QUE CARGA EL CONTENIDO DEL HOME
$app->get('/contenidoHome', function ( $request, $response ){

    $data = $request->getParams();
    $encuesta = new Encuesta();
    $rta = $encuesta->contenidoHome();
    return $response->withJson($rta);
});

// Run app
$app->run();


?>


