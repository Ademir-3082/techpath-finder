<?php

/**
 * ============================================================
 * ARQUIVO DE CONTEÚDO — TECHPATH FINDER
 * ============================================================
 *
 * Este arquivo contém as 12 perguntas e suas alternativas.
 *
 * Cada alternativa possui:
 *
 * - texto
 *   conteúdo exibido ao usuário
 *
 * - principal
 *   dimensão que recebe 1 sinal no ranking
 *
 * - secundaria
 *   informação complementar interna
 *
 * - evidencia
 *   comportamento observado utilizado na explicação
 *
 * IMPORTANTE:
 *
 * A ordem visual das dimensões varia entre as perguntas.
 * Isso reduz viés de posição e impede que uma letra
 * específica represente sempre a mesma dimensão.
 *
 * A ordem é estática, e não aleatória, para garantir:
 *
 * - persistência correta após F5;
 * - retorno seguro com "Anterior";
 * - consistência das respostas salvas na sessão.
 */

$perguntas = [

  /*
   * ==========================================================
   * P01 — SISTEMA DESCONHECIDO
   *
   * Ordem:
   * Criar → Investigar → Conectar → Proteger → Apoiar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você recebe acesso a uma ferramenta que nunca utilizou e precisa entender como ela funciona. Ninguém está disponível para explicar. Qual tende a ser seu primeiro movimento?',

    'descricao' =>
      'Não existe resposta certa. Escolha o que mais se aproxima do seu primeiro impulso.',

    'alternativas' => [

      [
        'texto' =>
          'Começo explorando e testando funções para descobrir o que consigo fazer.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'tende a aprender experimentando possibilidades',
      ],

      [
        'texto' =>
          'Observo como as partes da ferramenta se comportam e procuro padrões antes de tirar conclusões.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'busca compreender o funcionamento antes de concluir',
      ],

      [
        'texto' =>
          'Procuro entender onde a ferramenta se encaixa no processo e com quais pessoas ou sistemas ela se relaciona.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'procura compreender relações antes de agir',
      ],

      [
        'texto' =>
          'Primeiro identifico permissões, limites e possíveis impactos antes de experimentar.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'considera limites e possíveis impactos antes de modificar',
      ],

      [
        'texto' =>
          'Penso em uma necessidade real e tento entender como a ferramenta poderia ajudar a resolvê-la.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'relaciona tecnologia a uma necessidade prática',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P02 — ALGO PAROU DE FUNCIONAR
   *
   * Ordem:
   * Investigar → Conectar → Proteger → Apoiar → Criar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Uma pessoa relata que uma função importante parou de funcionar. Você ainda tem poucas informações. O que faria primeiro?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Tento reproduzir o problema e observar exatamente em quais condições ele acontece.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'busca reproduzir comportamentos antes de concluir',
      ],

      [
        'texto' =>
          'Procuro entender quais outras partes do sistema ou processo podem estar relacionadas ao que parou de funcionar.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'considera dependências ao analisar problemas',
      ],

      [
        'texto' =>
          'Avalio primeiro se continuar testando pode causar perda de dados ou impacto maior.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'avalia possíveis impactos antes de aprofundar a intervenção',
      ],

      [
        'texto' =>
          'Faço perguntas para compreender o que a pessoa estava tentando fazer e em que ponto a dificuldade surgiu.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'procura compreender a experiência de quem enfrenta o problema',
      ],

      [
        'texto' =>
          'Procuro uma forma prática de manter a tarefa funcionando enquanto exploramos uma solução definitiva.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'busca alternativas práticas diante de uma falha',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P03 — DUAS SOLUÇÕES POSSÍVEIS
   *
   * Ordem:
   * Conectar → Proteger → Apoiar → Criar → Investigar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você encontrou duas maneiras de resolver um problema. As duas parecem funcionar. Como tende a decidir qual seguir?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Analiso qual solução se encaixa melhor nas ferramentas, processos e pessoas já envolvidos.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'considera integração com o contexto existente',
      ],

      [
        'texto' =>
          'Considero qual opção permite controlar melhor os impactos e voltar atrás com mais facilidade se algo inesperado acontecer.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'valoriza controle de impacto e reversibilidade',
      ],

      [
        'texto' =>
          'Considero qual alternativa será mais clara e prática para quem precisará utilizá-la no dia a dia.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'considera a experiência de uso ao tomar decisões',
      ],

      [
        'texto' =>
          'Testo uma adaptação ou combinação das abordagens para observar se surge uma terceira possibilidade.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'explora possibilidades além das opções inicialmente disponíveis',
      ],

      [
        'texto' =>
          'Comparo resultados e evidências produzidas pelas duas abordagens.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'compara evidências antes de escolher',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P04 — TAREFA REPETITIVA
   *
   * Ordem:
   * Proteger → Apoiar → Criar → Investigar → Conectar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você percebe que uma tarefa manual é repetida várias vezes por semana e consome bastante tempo. O que mais chama sua atenção?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Antes de mudar o processo, observo quais partes são críticas e o que poderia dar errado com uma alteração.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'antecipa consequências antes de modificar um processo',
      ],

      [
        'texto' =>
          'Procuro entender quais partes dessa tarefa mais dificultam o dia a dia de quem precisa executá-la.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'considera dificuldades reais das pessoas ao pensar melhorias',
      ],

      [
        'texto' =>
          'Começo imaginando uma maneira diferente de executar a tarefa e testo uma pequena melhoria.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'identifica oportunidades de construir melhorias',
      ],

      [
        'texto' =>
          'Procuro entender por que o processo chegou a funcionar daquela maneira antes de modificá-lo.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'investiga a origem de um processo antes de alterá-lo',
      ],

      [
        'texto' =>
          'Mapeio etapas, ferramentas e pessoas envolvidas para enxergar o fluxo completo.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'organiza relações entre etapas e envolvidos',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P05 — UMA HORA PARA EXPLORAR
   *
   * Ordem:
   * Apoiar → Criar → Investigar → Conectar → Proteger
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você ganha uma hora livre para explorar uma tecnologia que nunca utilizou. Não há tarefa, cobrança ou resultado esperado. O que despertaria primeiro sua curiosidade?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Imaginar um problema real de alguém que poderia ser facilitado usando aquela tecnologia.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'relaciona tecnologia a problemas e necessidades humanas',
      ],

      [
        'texto' =>
          'Testar se consigo construir alguma coisa pequena e observar o que acontece.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'explora novas tecnologias por meio da construção',
      ],

      [
        'texto' =>
          'Entender o que acontece por trás do funcionamento e descobrir seus limites.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'demonstra curiosidade por funcionamento e limites',
      ],

      [
        'texto' =>
          'Descobrir com quais outras ferramentas ou serviços ela consegue se conectar e o que essas relações permitem.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'demonstra curiosidade pelas conexões entre tecnologias',
      ],

      [
        'texto' =>
          'Descobrir quais controles, permissões e limites determinam o que pode ou não ser feito.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'explora controles e fronteiras de uma tecnologia',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P06 — EXPLICAR ALGO COMPLEXO
   *
   * Ordem:
   * Criar → Investigar → Conectar → Proteger → Apoiar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Uma pessoa precisa começar a utilizar uma ferramenta complexa que ainda não conhece. Você ficou responsável por apresentar o funcionamento. Por onde tenderia a começar?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Monto uma pequena demonstração e deixo a pessoa experimentar enquanto observamos o que acontece.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'transforma explicações abstratas em experiências práticas',
      ],

      [
        'texto' =>
          'Primeiro identifico quais partes da ferramenta costumam gerar mais dúvida e procuro entender por quê.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'investiga pontos de dificuldade antes de explicar',
      ],

      [
        'texto' =>
          'Começo mostrando onde a ferramenta entra no processo e como suas principais partes se relacionam.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'usa contexto e relações para facilitar compreensão',
      ],

      [
        'texto' =>
          'Começo pelos limites, permissões e situações em que uma ação pode afetar outras partes do sistema.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'inclui limites e impactos durante a orientação',
      ],

      [
        'texto' =>
          'Começo por uma tarefa que aquela pessoa realmente precisa realizar e explico a ferramenta a partir dela.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'adapta a explicação à necessidade prática do usuário',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P07 — MUDANÇA IMPORTANTE
   *
   * Ordem:
   * Investigar → Conectar → Proteger → Apoiar → Criar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Uma mudança será feita em um sistema utilizado por várias pessoas. Antes da implementação, qual aspecto despertaria primeiro sua atenção?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Quais informações e evidências justificam a mudança e como saberemos se ela produziu o efeito esperado.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'busca evidências para avaliar uma mudança',
      ],

      [
        'texto' =>
          'Quais sistemas, processos e pessoas se relacionam com essa mudança e como podem ser afetados.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'considera efeitos da mudança em todo o ambiente',
      ],

      [
        'texto' =>
          'O que precisaria estar preparado caso o comportamento depois da mudança seja diferente do esperado.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'considera contingência diante de resultados inesperados',
      ],

      [
        'texto' =>
          'Como essa mudança aparecerá na rotina de quem utiliza o sistema e o que essas pessoas precisarão fazer diferente.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'considera impacto de mudanças sobre os usuários',
      ],

      [
        'texto' =>
          'Que novas possibilidades essa mudança pode abrir e o que poderíamos experimentar a partir dela.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'enxerga mudanças como oportunidade de experimentação',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P08 — PROJETO NOVO, POUCAS INFORMAÇÕES
   *
   * Ordem:
   * Conectar → Proteger → Apoiar → Criar → Investigar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você entra em um projeto novo e recebe apenas uma explicação geral do que precisa ser feito. Ainda existem muitas informações em aberto. Qual tende a ser seu primeiro movimento?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Procuro entender quem e o que está envolvido no projeto e como essas partes dependem umas das outras.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'mapeia relações e dependências antes da execução',
      ],

      [
        'texto' =>
          'Identifico quais decisões tomadas agora poderiam limitar caminhos depois e quais seria melhor manter reversíveis.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'considera consequências e reversibilidade das decisões',
      ],

      [
        'texto' =>
          'Procuro entender o que as pessoas precisarão conseguir fazer com o resultado e parto dessa necessidade.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'parte da necessidade prática de quem utilizará a solução',
      ],

      [
        'texto' =>
          'Transformo parte do que já sabemos em algo pequeno e concreto para observar o que conseguimos aprender com isso.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'reduz incerteza por meio de experimentação concreta',
      ],

      [
        'texto' =>
          'Separo o que já sabemos daquilo que ainda são suposições e procuro descobrir quais dúvidas precisam ser esclarecidas primeiro.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'separa evidências de suposições em cenários ambíguos',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P09 — INFORMAÇÕES CONTRADITÓRIAS
   *
   * Ordem:
   * Proteger → Apoiar → Criar → Investigar → Conectar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você está tentando entender um problema e encontra duas fontes confiáveis que apresentam explicações diferentes para o que está acontecendo. O que tende a fazer primeiro?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Identifico primeiro quais decisões dependeriam dessa informação e quais consequências teria agir com uma interpretação errada.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'considera consequências antes de agir com informação incerta',
      ],

      [
        'texto' =>
          'Procuro entender qual decisão ou tarefa precisa ser resolvida na prática e uso essa necessidade para orientar o próximo passo.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'orienta a análise pela necessidade prática a ser resolvida',
      ],

      [
        'texto' =>
          'Crio uma pequena situação de teste para observar o que acontece e gerar uma nova pista.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'gera novas pistas por meio de experimentação',
      ],

      [
        'texto' =>
          'Comparo as duas explicações e procuro identificar quais observações ou evidências sustentam cada uma.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'compara evidências antes de formar uma conclusão',
      ],

      [
        'texto' =>
          'Procuro entender se cada explicação está considerando contextos ou partes diferentes da situação.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'considera diferenças de contexto diante de informações conflitantes',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P10 — FUNCIONA, MAS NÃO É TOTALMENTE COMPREENDIDO
   *
   * Ordem:
   * Apoiar → Criar → Investigar → Conectar → Proteger
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você encontra uma solução que está funcionando bem, mas seu funcionamento ainda não é totalmente compreendido. Se tivesse oportunidade de explorá-la, o que despertaria primeiro sua atenção?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Procuro entender qual resultado essa solução entrega para quem a utiliza e o que realmente precisa ser preservado dessa experiência.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'avalia uma solução pelo valor percebido por quem a utiliza',
      ],

      [
        'texto' =>
          'Reproduzo uma versão pequena separadamente e experimento algumas variações para observar o que muda.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'explora possibilidades por meio de variações controladas',
      ],

      [
        'texto' =>
          'Procuro identificar quais condições parecem essenciais para o comportamento observado e quais evidências sustentam essa relação.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'busca condições e evidências que expliquem um comportamento',
      ],

      [
        'texto' =>
          'Mapeio com quais outras partes do sistema e do processo essa solução interage para entender onde seu comportamento se encaixa.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'analisa o comportamento dentro do ecossistema',
      ],

      [
        'texto' =>
          'Procuro identificar o que precisaria permanecer estável para que uma mudança futura não comprometa esse funcionamento.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'procura preservar condições críticas de funcionamento',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P11 — TEMPO LIMITADO
   *
   * Ordem:
   * Criar → Investigar → Conectar → Proteger → Apoiar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Você tem pouco tempo para avançar em uma situação com várias coisas que poderiam ser feitas. Não será possível cuidar de tudo agora. Como tende a decidir por onde começar?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Escolho uma ação pequena que permita experimentar uma possibilidade e aprender algo para orientar o restante.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'usa experimentos pequenos para orientar decisões posteriores',
      ],

      [
        'texto' =>
          'Procuro identificar qual dúvida, se esclarecida primeiro, reduziria mais a incerteza sobre as próximas decisões.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'prioriza redução de incerteza',
      ],

      [
        'texto' =>
          'Observo quais tarefas ou decisões estão ligadas a outras partes e começo por aquela que pode destravar mais coisas ao redor.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'prioriza considerando dependências e fluxo',
      ],

      [
        'texto' =>
          'Identifico o que seria mais difícil de corrigir depois e considero isso ao escolher o que precisa de atenção agora.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'considera custo de reversão ao priorizar',
      ],

      [
        'texto' =>
          'Procuro entender qual necessidade concreta está impedindo alguém de avançar e uso isso para definir minha prioridade.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'prioriza considerando o bloqueio vivido por quem depende da solução',
      ],
    ],
  ],

  /*
   * ==========================================================
   * P12 — DESAFIO QUE DESPERTA CURIOSIDADE
   *
   * Ordem:
   * Investigar → Conectar → Proteger → Apoiar → Criar
   * ==========================================================
   */
  [
    'enunciado' =>
      'Imagine que você pode escolher livremente um novo desafio para explorar, sem obrigação de já saber resolvê-lo. Qual deles despertaria primeiro sua curiosidade?',

    'descricao' =>
      '',

    'alternativas' => [

      [
        'texto' =>
          'Investigar um comportamento difícil de explicar até encontrar pistas sobre o que pode estar acontecendo.',

        'principal' =>
          'investigar',

        'secundaria' =>
          'proteger',

        'evidencia' =>
          'demonstra curiosidade por causas e explicações',
      ],

      [
        'texto' =>
          'Entender como várias partes diferentes se relacionam e encontrar uma forma de fazê-las trabalhar juntas.',

        'principal' =>
          'conectar',

        'secundaria' =>
          'apoiar',

        'evidencia' =>
          'demonstra interesse por integração e visão sistêmica',
      ],

      [
        'texto' =>
          'Explorar onde uma solução pode falhar e pensar em maneiras de torná-la mais resistente a situações inesperadas.',

        'principal' =>
          'proteger',

        'secundaria' =>
          'conectar',

        'evidencia' =>
          'demonstra interesse por confiabilidade e prevenção',
      ],

      [
        'texto' =>
          'Entender uma dificuldade que alguém está tendo com tecnologia e encontrar uma maneira prática de facilitar aquela experiência.',

        'principal' =>
          'apoiar',

        'secundaria' =>
          'criar',

        'evidencia' =>
          'demonstra interesse em reduzir barreiras tecnológicas para pessoas',
      ],

      [
        'texto' =>
          'Transformar uma ideia em algo concreto e descobrir o que acontece enquanto ela ganha forma.',

        'principal' =>
          'criar',

        'secundaria' =>
          'investigar',

        'evidencia' =>
          'demonstra curiosidade por transformar ideias em soluções',
      ],
    ],
  ],
];