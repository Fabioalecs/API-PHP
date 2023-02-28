<?php

namespace Repository;

use DB\MySQL;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

class TokensAutorizadosRepository
{
    private object $MySQL;
    public const TABELA = "token_autorizados";
    
    /**
     * TokensAutorizadosRepository constructor.
     */
    public function __construct()
    {   
        $this->MySQL = new MySQL;
    }

    /**
     * @param $tokenPreparado
     */
    public function validarToken($tokenPreparado)
    {

        $consultaToken = 'SELECT id FROM ' .self::TABELA . ' WHERE token = :token AND status = :status';
        $stmt = $this->getMySQL()
                        ->getDb()
                        ->prepare($consultaToken);
        $stmt->bindValue(':token', $tokenPreparado);
        $stmt->bindValue(':status', ConstantesGenericasUtil::SIM);
        $stmt->execute();

        if($stmt->rowCount() !== 1)
        {
            header('HTTP/1.1 401 Unauthorized');
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
        }
     
    }


    /**
     * @param $token
     */
    public function prepararToken($token)
    {
        $token = str_replace([' ', 'Bearer'], '', $token);
        return $token;
        
    }


    /**
     * @return MySQL|object
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }


}