// https://github.com/anapaulamendes/my-talks-and-workshops/blob/master/python-brasil-2018/ga_strings.py
// node find-word.js "A palavra"
// node find-word.js "minicurso algoritmo genetico 2020"
// node find-word.js "minicurso algoritmo genetico 2020 Instituto Federal do Mato Grosso do Sul"

const MAX_TAM_POPULACAO = 1000
const MAX_GERACAO = 100000
const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 "
// const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 "
const TAXA_MUTACAO = 0.01
const COPIAR_N_MELHORES_INDIVIDUOS = 2

const PRINT_INDIVIDUOS = 0
const PRINT_X_GERACAO = 100

// =======

let populacao
let auxPopulacao
let geracao
let somaAptidao, melhorAptidao

let segredo = process.argv[2] //.toUpperCase()

const TAM_PROBLEMA = segredo.length
const CHARS_LENGTH = CHARS.length

// ALGORITMO GENETICO

iniciarPopulacao()
avaliarPopulacao()
printMelhor()

while (condicaoParada()) {
    geracao++

    auxPopulacao = populacao
    populacao = []

    if (COPIAR_N_MELHORES_INDIVIDUOS > 0) {
        populacao.push( ...auxPopulacao.slice(0, COPIAR_N_MELHORES_INDIVIDUOS) )
    }

    crossoverPopulacao()
    avaliarPopulacao()

    if (geracao % PRINT_X_GERACAO == 0) {
        printMelhor()
    }
}

printMelhor()

// OPERACOES

function avaliarIndividuo(individuo) {
    let erros = 1
    for (let p = 0; p < TAM_PROBLEMA; p++) {
        if (segredo.charAt(p) != individuo.corpo.charAt(p)) {
            erros++
        }
    }
    individuo.acertos = TAM_PROBLEMA - erros
    individuo.aptidao = (erros == 1) ? 1 : 1 / erros
}

function avaliarPopulacao() {
    somaAptidao = 0
    for (let i = 0; i < populacao.length; i++) {
        avaliarIndividuo(populacao[i])
        somaAptidao += populacao[i].aptidao
    }
    populacao.sort((a, b) => {
        return b.aptidao - a.aptidao;
    })
    melhorAptidao = populacao[0].aptidao
}

function condicaoParada() {
    if (melhorAptidao >= 1) {
        return false
    }

    return geracao < MAX_GERACAO
}

function criarIndividuo() {
    let corpo = ''
    for (let i = 0; i < TAM_PROBLEMA; i++) {
        corpo += sortearLetra()
    }
    return { corpo }
}

function crossoverIndividuo(individuo1, individuo2) {
    let corte = Math.round(Math.random() * (TAM_PROBLEMA + 1))

    return [
        { corpo: individuo1.corpo.substr(0, corte) + individuo2.corpo.substr(corte) },
        { corpo: individuo2.corpo.substr(0, corte) + individuo1.corpo.substr(corte) }
    ]
}

function crossoverPopulacao() {
    let individuo1, individuo2

    while (populacao.length < MAX_TAM_POPULACAO) {
        individuo1 = roletaViciada()
        individuo2 = roletaViciada()

        let filhos = crossoverIndividuo(individuo1, individuo2)
        // populacao.push(...filhos)

        let filhosMutados = filhos.map(mutacaoIndividuo)
        populacao.push(...filhosMutados)
    }
}

function iniciarPopulacao() {
    populacao = []
    geracao = 0

    for (let i = 0; i < MAX_TAM_POPULACAO; i++) {
        populacao.push(criarIndividuo())
    }
    // populacao.push({ corpo: segredo })
}

function mutacaoIndividuo(individuo) {
    let corpo = "";
    for (let i = 0; i < TAM_PROBLEMA; i++) {
        corpo += Math.random() < TAXA_MUTACAO
            ? sortearLetra() : individuo.corpo.charAt(i)
    }
    return { corpo }
}

function printMelhor() {
    console.log(`#${geracao}: ${populacao[0].corpo} [${populacao[0].acertos} / ${TAM_PROBLEMA}] ${arredondar(somaAptidao, 5)}`)
    if (PRINT_INDIVIDUOS > 0) {
        console.log(populacao.slice(0, PRINT_INDIVIDUOS)
            .map(v => {
                v.aptidao = arredondar(v.aptidao, 5)
                return v
            })
        )
    }
}

function roletaViciada() {
    let sorteio = somaAptidao * Math.random()
    let soma = 0
    let i = -1
    do {
        i++
        soma += auxPopulacao[i].aptidao
    } while (soma < sorteio)
    return auxPopulacao[i]
}

// UTILS

function arredondar(numero, casas) {
    let d = Math.pow(10, casas)
    return Math.round(numero * d) / d
}

function sortearLetra() {
    let r = parseInt(Math.random() * CHARS_LENGTH)
    return CHARS.charAt(r)
}

