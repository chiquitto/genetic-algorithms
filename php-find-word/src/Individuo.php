<?php


namespace Chiquitto\FindWord;


class Individuo
{
    private $cromossomo;
    private $aptidao;

    public static function random()
    {
        return new Individuo(Util::randomPalavra(AlgoritmoGenetico::$tamCromossomo));
    }

    public function __construct($cromossomo)
    {
        $this->cromossomo = $cromossomo;
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

    public function getCromossomo()
    {
        return $this->cromossomo;
    }

    public function setCromossomo($cromossomo)
    {
        $this->cromossomo = $cromossomo;
    }

}