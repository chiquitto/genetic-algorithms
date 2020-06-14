<?php


namespace Chiquitto\FindWord;


class Individuo
{
    private $cromossomo;
    private $aptidao;
    private $geracao;

    public static function random($geracao)
    {
        return new Individuo(
            Util::randomPalavra(AlgoritmoGenetico::$tamCromossomo),
            $geracao
        );
    }

    public function __construct($cromossomo, $geracao)
    {
        $this->cromossomo = $cromossomo;
        $this->geracao = $geracao;
    }

    public function avaliar($correto)
    {
        $this->aptidao = 1;
        for ($i = 0; $i < AlgoritmoGenetico::$tamCromossomo; $i++) {
            if ($correto[$i] == $this->cromossomo[$i]) {
                $this->aptidao++;
            }
        }
        return $this->aptidao;
    }

    public function getAptidao()
    {
        return $this->aptidao;
    }

    public function getGeracao()
    {
        return $this->geracao;
    }

    public function getCromossomo()
    {
        return $this->cromossomo;
    }

    public function setCromossomo($cromossomo)
    {
        $this->cromossomo = $cromossomo;
    }

}