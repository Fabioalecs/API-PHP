<?php

namespace Service;

use InvalidArgumentException;
use JsonException;
use Repository\UsuariosRepository;
use Util\ConstantesGenericasUtil;

class UsuariosService
{

    public const TABELA = 'usuarios';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    /**
     * @var array
     */
    private array $dados;

    /**
     * @var array
     */
    private array $dadosCorpoRequest = [];


    /**
     * @var object|UsuariosRepository
     */
    private object $UsuariosRepository;


    /**
     * UsuariosService constructor.
     */
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository();
    }

    /**
     * @return mixed
     */
    public function validarGet()
    {
        $recurso = $this->dados['recurso'];

        if (!in_array($recurso, self::RECURSOS_GET, true)) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($recurso);

        return $this->dados['id'] > 0 ? $this->getById() : $this->$recurso();
    }

    /**
     * @return mixed
     */
    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (!in_array($recurso, self::RECURSOS_DELETE, true)) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarIdObrigatorio();
        $retorno = $this->$recurso();

        $this->validarRetornoRequest($recurso);

        return $retorno;
    }

    /**
     * @return mixed
     */
    public function validarPut()
    {
        $recurso = $this->dados['recurso'];

        if (!in_array($recurso, self::RECURSOS_PUT, true)) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarIdObrigatorio();

        $this->validarRetornoRequest($recurso);

        return $this->$recurso();
    }

    /**
     * @return mixed
     */
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if (!in_array($recurso, self::RECURSOS_POST, true)) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $retorno = $this->$recurso();

        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    /**
     * @param retorno
     */
    private function validarRetornoRequest($retorno)
    {
        if ($retorno === null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
    }

    private function validarIdObrigatorio() {
        if ($this->dados['id'] <= 0) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
        }
    }

    public function setDadosCorpoRequest($dadosCorpoRequest)
    {
        $this->dadosCorpoRequest = $dadosCorpoRequest;
    }

    private function getById()
    {
        return $this->UsuariosRepository->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->UsuariosRepository->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->UsuariosRepository->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {
        [$login,  $senha] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha']];

        if ($login && $senha) {
            if ($this->UsuariosRepository->insertUser($login, $senha) > 0) {
                $idInserido = $this->UsuariosRepository->getMySQL()->getDb()->lastInsertId();
                $this->UsuariosRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }

            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
    }

    private function atualizar()
    {
        if ($this->UsuariosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->UsuariosRepository->getMySQL()->getDb()->commit();
            return ['Usuario Atualizado' => $this->dadosCorpoRequest['login']];
        }

        $this->UsuariosRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }
}
