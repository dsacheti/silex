<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MicroFrame\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity;
 * @ORM\Table(name="posts")
 */
class Post 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $titulo;
    
    /**
     * @ORM\Column(type="text")
     */
    private $conteudo;
    
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getConteudo() {
        return $this->conteudo;
    }

    
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
        return $this;
    }

    public function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
        return $this;
    }


}
