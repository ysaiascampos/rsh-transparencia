<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
final class Constante  extends  ConstanteEnv
{
    /**
     * Si y No mayúscula
     */
    const URL_MINISTERIAL_PROD = 'http://www.desarrollosocialyfamilia.gob.cl';
    const SI_STRING = 'SI';
    const NO_STRING = 'NO';

    /**
     * COTIZANTE CARGA
     */
    const COTIZANTE = 1;
    const CARGA = 2;

    /**
     * Campo GRUPFAMI_C_ZONA de GRUPOS_FAMILIARES
     */
    const ZONA_STRING_URBANO = 'URBANO';
    const ZONA_STRING_NO_URBANO = 'NO URBANO';

    /**
     * Si el codigo calle es >= 11011 es NUEVA CALLE DEL SIT, de lo contrario es FPS, y debe mostrar solo NOMBRE CALLE de la tabla grupo
     * Si el codigo calle es >= 11011 se leen los datos desde las vistas, de lo contrario solo N_CALLE, nada mas.
     * IdCalle donde cambia el formato string al que se debe leer desde VAS_LOCALIDAD, VAS_CALLE, VAS_AH o VAS_UNIDAD_VECINAL
     */
    const ID_CALLE_11011_CORTE_GRUPO_FAMILIAR = 11011;

    /**
     * CALLE NO REGISTRADA
     */
    const ID_CALLE_10999_NO_REGISTRADA = 10999;

    /**
     * DATA REGITRO CIVIL
     */
    const DATOS_DE_CONTACTO_2_ERROR_INESPERADO = '-2';
    const DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR = '-3';
    const DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL = '-5';

    /**
     * Palabras o cadenas para Claves
     */
    const PASSWORD_WEBSERVER = '9XcTLvbQ';
    const PASSWORD_TOKEN = 'MDS.Catedral#l575';
    const PASSWORD_CARTOLA = 'xGskkru12zDpa';
    const PASSWORD_TOKEN_CLAVE_UNICA = 'ba3f31eb017e30572b2e6e0000000000000';

    /**
     * Cortes en el nuevo folio para distinguir de que plataforma viene
     */
    const GRUPFAMI_NUEVOFOLIO_10000000_CORTE_MINIMO_FS = 10000000;   // Ficha Social
    const GRUPFAMI_NUEVOFOLIO_20000000_CORTE_MINIMO_FPS = 20000000;  // Ficha de Protección Social
    const GRUPFAMI_NUEVOFOLIO_30000000_CORTE_MINIMO_FS2 = 30000000;  // Ficha Social
    const GRUPFAMI_NUEVOFOLIO_40000000_CORTE_MINIMO_FPS2 = 40000000; // Ficha de Protección Social
    const GRUPFAMI_NUEVOFOLIO_50000000_CORTE_MINIMO_RSH = 50000000;

    /**
     * Pasos en las vistas de Solictud Nº 1 Ingreso al Registro Ciudadano
     */
    const STEP_0_STRING_SIN_PASO = 'sin-paso';
    const STEP_1_STRING_DATOS_CONTACTO = 'datos-de-contacto';
    const STEP_1_2_STRING_VIVIENDA = 'vivienda';
    const STEP_2_STRING_INTEGRATES_FAMILIA = 'integrantes-familia';
    const STEP_3_STRING_JEFE_HOGAR = 'jefe-de-hogar';
    const STEP_4_STRING_PARENTESCO = 'parentesco';
    const STEP_5_STRING_PAREJAS = 'parejas';
    const STEP_6_STRING_DOCUMENTOS = 'documentos';
    const STEP_7_STRING_CONFIRMACION = 'confirmacion';
    const STEP_STRING_CONTINUAR_MAS_TARDE = 'borrador';

    /**
     * String que identifica a los documentos generados por firma simple
     */
    const STRING_IDENTIFICADOR_FIRMA_SIMPLE = "/fs_";

    /**
     * Porcentaje de similitud de nombre escrito, con nombre traído de WS Registro civil
     */
    const PORCENTAJE_60_STRING_SIMILITUD_ACEPTADO = 60;

    /**
     * ANTIGUOS (deberían borraste o poner en tabla AB_PARAMETRO_CONFIG)
     */
    const diasSinMovimiento = 30;      // primer correo recordando que ahora restan 10 dias para desistir la solicitud
    const diasSinMovimientoExtra = 40; // dias totales; es decir; adicionando 10 dias desde la primera advertencia; con esto se desiste la solicitud

    /**
     * Configuracion SOAP, consumo del runificador
     */
    public static $CONFIG_SOAP_1_2 = [
        'soap_version' => SOAP_1_2,
        'exceptions' => true,
        'trace' => 1
    ];
    public static $CONFIG_SOAP_DEFAULT = [
        'exceptions' => true
    ];

    /**
     * Mensaje que se muestra en el ciudadano en el area de Comunicamos
     */
    const CIUDADANO_HOME_COMUNICAMOS = null;

    /**
     * Posible respuesta de Regiones
     */
    const REGION_1_TARAPACA = 1;
    const REGION_2_ANTOFAGASTA = 2;
    const REGION_3_ATACAMA = 3;
    const REGION_4_COQUIMBO = 4;
    const REGION_5_VALPARAISO = 5;
    const REGION_6_LIBERTADOR_GRAL_BERNARDO_OHIGGINS = 6;
    const REGION_7_MAULE = 7;
    const REGION_8_BIOBIO = 8;
    const REGION_9_LA_ARAUCANIA = 9;
    const REGION_10_LOS_LAGOS = 10;
    const REGION_11_AYSEN_DEL_GRAL_CARLOS_IBANNEZ_DEL_CAMPO = 11;
    const REGION_12_MAGALLANES_Y_DE_LA_ANTARTICA_CHILENA = 12;
    const REGION_13_METROPOLITANA_DE_SANTIAGO = 13;
    const REGION_14_LOS_RIOS = 14;
    const REGION_15_ARICA_Y_PARINACOTA = 15;
    const REGION_16_NNUBLE = 16;

    public static $REGIONES_1_A_16_POSIBLES_RESPUESTAS = [
        self::REGION_1_TARAPACA,
        self::REGION_16_NNUBLE
    ];

    const PERFILES_INTERNOS = [1,2];
    const CORREO_ENVIADO_EXITOSO = 1;
    const CORREO_ENVIADO_ENCOLAMIENTO = 0;
    const NO_POSEE_CORREO_NO_ENVIADO = -1;
    const CORREO_ENVIADO_REPROCESO_ENCOLAMIENTO = 2;
    const CORREO_NO_ENVIADO = -1;
    const ESTADO_HABILITADO = 1;

    const ROL_OFUSCAR = 7;

    //Nombre del Reporte General
    const RG_TITULO_ITEM1 = "Evolución del Tramo CSE del Hogar";
    const RG_SUBTITULO_ITEM1 = "Gráfico de evolución de los Tramos de Calificación Socioeconómica del hogar para los últimos 12 meses.";
    const RG_TITULO_ITEM2 = "Ingresos Monetarios observados del titular";
    const RG_SUBTITULO_ITEM2 = "Tabla con los ingresos del titular considerados para las últimas 12 calificaciones socioeconómicas (CSE).";
    const RG_TITULO_ITEM3 = "Medios Activos y Pensión de Alimentos del Hogar";
    const RG_SUBTITULO_ITEM3 = "Desglose de bienes y servicios que registra el hogar.";
    const RG_TITULO_ITEM4 = "Ingresos del Titular";
    const RG_SUBTITULO_ITEM4 = "Gráfico de la evolución de los ingresos del titular.";
    const RG_TITULO_ITEM5 = "Ingresos del Hogar";
    const RG_SUBTITULO_ITEM5 = "Gráfico de la evolución de los ingresos del hogar.";
    const RG_TITULO_ITEM6 = "Antecedentes relevantes para la calificación del Hogar del RUN consultado";
    const RG_SUBTITULO_ITEM6 = "";
}
