<?php

namespace Chiquitto\FindWord;

class Populacao
{
    /**
     * @var Individuo[]
     */
    private $individuos;
    private $somatorioAptidao = 0;
    private $geracao;

    public function __construct($geracao)
    {
        $this->individuos = [];
        $this->geracao = $geracao;
    }

    public static function inicializar($geracao)
    {
        $pop = new Populacao($geracao);

        for ($i = 0; $i < AlgoritmoGenetico::$maxTamPopulacao; $i++) {
            $pop->addIndividuo(Individuo::random($geracao));
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

    public function mutacao()
    {
        foreach ($this->individuos as $individuo) {
            if ($individuo->getGeracao() != $this->getGeracao()) {
                continue;
            }

            $string = $individuo->getCromossomo();
            for ($i = 0; $i < strlen($string); $i++) {
                if (rand(0, 100) < 3) {
                    $string[$i] = Util::randomLetra();
                }
            }
            $individuo->setCromossomo($string);
        }
    }

    public function getGeracao()
    {
        return $this->geracao;
    }

    public function getSomatorioAptidao()
    {
        return $this->somatorioAptidao;
    }

}