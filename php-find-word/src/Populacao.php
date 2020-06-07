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

    public function mutacao()
    {
        foreach ($this->individuos as $individuo) {
            $string = $individuo->getCromossomo();
            for ($i = 0; $i < strlen($string); $i++) {
                if (rand(0, 100) < 2) {
                    $string[$i] = Util::randomLetra();
                }
            }
            $individuo->setCromossomo($string);
        }
    }

}