<?php

namespace Sistema\Mapper;

use Sistema\Entity\Cliente;

class ClienteMapper
{

    private $dados = [
        0 =>[
            'nome' => 'NomeCliente',
            'email' => 'emailCliente@host.com.br'
        ],
        1 =>[
            'nome' => 'NomeOutroCliente',
            'email' => 'emailOutroCliente@host.com.br'
        ],
    ];

    public function insert(Cliente $cliente)
    {
        return [
            'nome' => 'NomeCliente',
            'email' => 'emailCliente@host.com.br'
        ];
    }

    public function fetchAll(){
        $dados = $this->dados;
        return $dados;
    }

    public function find($id)
    {
        $dados = $this->dados[$id];
        return $dados;
    }
}