<?php


namespace Chiquitto\FindWord;


class Individuo
{
    private $cromossomo;
    private $aptidao;

    public static function random()
    {
        return new Individuo(
            Util::randomPalavra(AlgoritmoGenetico::$tamCromossomo)
        );
    }

    public function __construct($cromossomo)
    {
        $this->cromossomo = $cromossomo;
    }

    public function avaliar($correto)
    {
        $this->aptidao = 1;
        for ($i = 0; $i < AlgoritmoGenetico::$tamCromossomo; $i++) {
            $this->aptidao += abs(ord($correto[$i]) - ord($this->cromossomo[$i]));
        }
        $this->aptidao = 1 / $this->aptidao;
        return $this->aptidao;
    }

    public function mutacao()
    {
        $string = $this->getCromossomo();
        for ($i = 0; $i < strlen($string); $i++) {
            if (mt_rand(0, 100) < 1) {
                $string[$i] = Util::randomLetra();
            }
        }
        $this->setCromossomo($string);
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