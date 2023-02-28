<?php

namespace Validator;

use InvalidArgumentException;
use Repository\TokensAutorizadosRepository;
use Service\UsuariosService;
use Util\ConstantesGenericasUtil;
use UTIL\JsonUtil;

class RequestValidator
{

    private $request;
    private array $dadosRequest = [];
    private object $tokensAutorizadosRepository;

    const GET = 'GET';
    const POST = 'POST';
    const DELETE = 'DELETE';
    const PUT = 'PUT';
    const USUARIOS = 'USUARIOS';


    public function __construct($request)
    {
        $this->request = $request;
        $this->tokensAutorizadosRepository = new TokensAutorizadosRepository();
    }


    /**
     * @return string
     */
    public function processarRequest()
    {
        $retorno =  mb_convert_encoding(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');
        if(in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)){
            $retorno = $this->direcionarRequest();
        }
        return $retorno;
    }

    private function direcionarRequest()
    {   
        if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== SELF::DELETE) {
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }

        $tokenPreparado = $this->tokensAutorizadosRepository->prepararToken(getallheaders()['Authorization']);
        $this->tokensAutorizadosRepository->validarToken($tokenPreparado);
        $metodo = $this->request['metodo'];
        return $this->$metodo();
    }


    private function get()
    {
        $retorno = mb_convert_encoding(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, true)){
            switch($this->request['rota']){
                case self::USUARIOS:
                    $UsuariosService = new UsuariosService($this->request);
                    $retorno = $UsuariosService->validarGet();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }


    private function delete()
    {
        $retorno = mb_convert_encoding(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE, true)){
            switch($this->request['rota']){
                case self::USUARIOS:
                    $UsuariosService = new UsuariosService($this->request);
                    $retorno = $UsuariosService->validarDelete();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }


    private function post()
    {
        $retorno = mb_convert_encoding(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, true)){
            switch($this->request['rota']){
                case self::USUARIOS:
                    $UsuariosService = new UsuariosService($this->request);
                    $UsuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $UsuariosService->validarPost();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            return $retorno;
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
    }

    private function put()
    {
        $retorno = mb_convert_encoding(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA, 'UTF-8');

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, true)){
            switch($this->request['rota']){
                case self::USUARIOS:
                    $UsuariosService = new UsuariosService($this->request);
                    $UsuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $UsuariosService->validarPut();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            
        }

        return $retorno;

    }



}