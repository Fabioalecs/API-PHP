<?php

use Util\ConstantesGenericasUtil;
use UTIL\JsonUtil;
use Util\RotasUtil;
use Validator\RequestValidator;

include 'bootstrap.php';


try {
    $RequestValidator = new RequestValidator(RotasUtil::getRotas());
    $retorno = $RequestValidator->processarRequest();
    
    $jsonUtil = new JsonUtil();
    $jsonUtil->processarArray($retorno);

} catch (Exception $exception) {
    echo json_encode([
        ConstantesGenericasUtil::TIPO => ConstantesGenericasUtil::TIPO_ERRO,
        ConstantesGenericasUtil::RESPOSTA => mb_convert_encoding($exception->getMessage(), 'UTF-8')
    ]);
    exit;
}

