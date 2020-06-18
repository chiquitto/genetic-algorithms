<?php


namespace Chiquitto\FindWord;


class AlgoritmoGenetico
{
    public static $maxTamPopulacao;
    public static $tamCromossomo;
    public static $maxGeracoes;
    /**
     * @var Populacao
     */
    private $populacao;
    /**
     * @var Populacao
     */
    private $novaPopulacao;
    /**
     * @var int
     */
    private $geracao;

    /**
     * O UM ALGORITMO GENETICO
     */
    public function run($entrada)
    {
        static::$tamCromossomo = strlen($entrada);
        $this->geracao = 0;

        $this->dumpInicio($entrada);

        $this->populacao = Populacao::inicializar();
        $this->populacao->avaliar($entrada);

        while ($this->criterioParada()) {
            $this->novaPopulacao = new Populacao();
            $this->copiarMelhores();
            $this->crossover();
            $this->novaPopulacao->avaliar($entrada);

            $this->populacao = $this->novaPopulacao;
            $this->dump();

            $this->geracao++;
        };

        $this->dump(true);
    }

    private function copiarMelhores()
    {
        $this->novaPopulacao->addIndividuos(
            $this->populacao->melhores((int) (AlgoritmoGenetico::$maxTamPopulacao/10))
        );
    }

    private function criterioParada()
    {
        $melhorAptidao = $this->populacao->melhorIndividuo()->getAptidao();

        return ($this->geracao <= static::$maxGeracoes)
            && ($melhorAptidao < (static::$tamCromossomo + 1));
    }

    private function crossover()
    {
        while ($this->novaPopulacao->calcularTamanho() < static::$maxTamPopulacao) {
            $filhos = $this->crossoverIndividuos();
            foreach ($filhos as $individuo) {
                $individuo->mutacao();
            }
            $this->novaPopulacao->addIndividuos($filhos);
        }
    }

    private function crossoverIndividuos()
    {
        $pai = $this->populacao->roletaViciada();
        $mae = $this->populacao->roletaViciada();

        $string1 = $pai->getCromossomo();
        $string2 = $mae->getCromossomo();

        $pontoCorte = mt_rand(0, static::$tamCromossomo);

        $crossover1 = substr($string1, 0, $pontoCorte) . substr($string2, $pontoCorte);
        $crossover2 = substr($string2, 0, $pontoCorte) . substr($string1, $pontoCorte);

        return [new Individuo($crossover1, $this->geracao), new Individuo($crossover2, $this->geracao)];
    }

    ####################################

    private function dumpInicio($entrada)
    {
        echo "Iniciando ...\n";
        echo "Missão: Encontrar a string informada pelo usuario.\n";
        echo "String: {$entrada}\n";
        echo "Comprimento do problema: ", static::$tamCromossomo, "\n";

        $size = pow(static::$tamCromossomo, strlen(Util::letras()));
        // echo "Tamanho do problema: ", strtr($size, ['E+' => ' x 10^']), "\n";
        echo "Tamanho do problema: 2 ^ ", floor(log($size, 2));

        echo "\n\n\n";
    }

    private function dump($theend = false)
    {
        if ($theend) {
            echo "### FIM ###\n";
        }
        elseif ($this->geracao % 10 !== 0) {
            return;
        }

        printf("# %4d: %s\tMelhor:%d/%d\tMedia:%.2f\n",
            $this->geracao,
            $this->populacao->melhorIndividuo()->getCromossomo(),
            $this->populacao->melhorIndividuo()->getAptidao(),
            (static::$tamCromossomo + 1),
            $this->populacao->getSomatorioAptidao() / $this->populacao->calcularTamanho()
        );
    }
}