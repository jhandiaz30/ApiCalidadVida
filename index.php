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
require_once    'controller/accidentalidad.php';

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

//METODOS PARA ACCIDENTALIDAD

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



// Run app
$app->run();


?>


