<?php

namespace Chiquitto\FindWord;

class Populacao
{
    /**
     * @var Individuo[]
     */
    private $individuos;
    private $somatorioAptidao = 0;

    public function __construct()
    {
        $this->individuos = [];
    }

    public static function inicializar()
    {
        $pop = new Populacao();

        for ($i = 0; $i < AlgoritmoGenetico::$maxTamPopulacao; $i++) {
            $pop->addIndividuo(Individuo::random());
        }

        return $pop;
    }

    public static function vazio()
    {
        return new Populacao();
    }

    public function addIndividuo(Individuo $individuo)
    {
        $this->individuos[] = $individuo;
    }

    public function addIndividuos($individuos)
    {
        array_push($this->individuos, ...$individuos);
    }

    public function calcularTamanho()
    {
        return count($this->individuos);
    }

    public function avaliar($correto)
    {
        foreach ($this->individuos as $individuo) {
            $this->somatorioAptidao += $individuo->avaliar($correto);
        }
        usort($this->individuos, function ($a, $b) {
            return $a->getAptidao() < $b->getAptidao();
        });
    }

    /**
     * @return Individuo
     */
    public function melhorIndividuo()
    {
        return $this->individuos[0];
    }

    /**
     * @param $n int
     * @return Individuo[]
     */
    public function melhores($n)
    {
        return array_slice($this->individuos, 0, $n);
    }

    /**
     * @return Individuo
     */
    public function roletaViciada()
    {
        $sorteio = mt_rand(1, $this->somatorioAptidao);
        $soma = 0;
        $i = -1;
        do {
            $i++;
            $soma += $this->individuos[$i]->getAptidao();
        } while ($soma < $sorteio);
        return $this->individuos[$i];
    }

    public function getSomatorioAptidao()
    {
        return $this->somatorioAptidao;
    }

}