<?php

namespace Sistema\Service;

use Sistema\Entity\Cliente;
use Sistema\Mapper\ClienteMapper;

class ClienteService
{
    /**
     * @var Cliente
     */
    private $cliente;
    /**
     * @var ClienteMapper
     */
    private $clienteMapper;

    public function __construct(Cliente $cliente,ClienteMapper $clienteMapper)
    {

        $this->cliente = $cliente;
        $this->clienteMapper = $clienteMapper;
    }

    public function insert(array $data)
    {
        $cliente = $this->cliente;
        $cliente->setNome($data['nome']);
        $cliente->setEmail($data['email']);

        $mapper = $this->clienteMapper;
        return $mapper->insert($cliente);
    }

    public function fetchAll()
    {
        $mapper = $this->clienteMapper;
        return $mapper->fetchAll();
    }

    public function find($id)
    {
        $mapper = $this->clienteMapper;
        return $mapper->find($id);
    }
}