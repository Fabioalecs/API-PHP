<?php

namespace UTIL;

use JsonException;
use Util\ConstantesGenericasUtil;

class JsonUtil 
{

   public function processarArray($retorno)
   {
      $dados = [];
      $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO;

      if(is_array($retorno) && count($retorno) > 0){
         $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
         $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
      }

      $this->retornarJson($dados);

   }


   private function retornarJson($json)
   {
      header('Content-Type: application/json');
      header('Acess-Control-Allow-Methods: GET, POST, PUT, DELETE');

      echo json_encode($json);
      exit;
   }



    /**
     * @return array|mixed 
     */

    public static function tratarCorpoRequisicaoJson()
    {
         try {
            $postJson = json_decode(file_get_contents('php://input'), 'UTF-8');
         } catch (JsonException $exception) {
            throw new JsonException(ConstantesGenericasUtil::MSG_ERR0_JSON_VAZIO);
         }

         if(is_array($postJson) && count($postJson) > 0) {
            return $postJson;
         }
    }




}