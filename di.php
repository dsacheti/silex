<?php

class Pessoa
{
    private $nome;
    private $email;
    private $db;
    
    public function __construct(\PDO $db) {
        
        $this->db = $db;
    }
    
    public function save()
    {
        $this->db->exec($statement);
    }
}

$pdo = new \PDO('dsn','user','senha');

$pessoa = new Pessoa($pdo);