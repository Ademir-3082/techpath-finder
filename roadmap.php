<?php
session_start();

require 'functions.php';
require 'perguntas.php';

inicializar_sessao_finder();

$total = total_etapas();

/*
 * ============================================================
 * PROTEÇÃO DE ACESSO
 * ============================================================
 */
if (!finder_concluido($perguntas)) {
  header('Location: finder.php');
  exit;
}

/*
 * ============================================================
 * MOTOR
 * ============================================================
 */
$perfil  = calcular_perfil_finder($perguntas);
$ranking = ranking_finder($perguntas);

$pontuacao = $perfil['pontuacao'];

$dimensoes_ordenadas = array_keys($pontuacao);
$valores_ordenados   = array_values($pontuacao);

$d1 = $valores_ordenados[0] ?? 0;
$d2 = $valores_ordenados[1] ?? 0;

$dim1 = $dimensoes_ordenadas[0] ?? null;
$dim2 = $dimensoes_ordenadas[1] ?? null;

/*
 * ============================================================
 * REGRA CANÔNICA
 * ============================================================
 *
 * COMBINADO:
 * D1 + D2 >= 9
 * diferença <= 1
 *
 * PREDOMINANTE:
 * D1 >= 5
 * diferença para D2 >= 2
 *
 * DEMAIS:
 * Mapa distribuído
 */
if (
  ($d1 + $d2) >= 9 &&
  ($d1 - $d2) <= 1
) {

  $tipo_resultado = 'combinado';

} elseif (
  $d1 >= 5 &&
  ($d1 - $d2) >= 2
) {

  $tipo_resultado = 'predominante';

} else {

  $tipo_resultado = 'distribuido';
}

/*
 * ============================================================
 * NOMES
 * ============================================================
 */
$nomes = [
  'criar'      => 'Criar',
  'investigar' => 'Investigar',
  'conectar'   => 'Conectar',
  'proteger'   => 'Proteger',
  'apoiar'     => 'Apoiar',
];

/*
 * ============================================================
 * CAMINHOS POR DIMENSÃO
 * ============================================================
 *
 * IMPORTANTE:
 * Esta ordem está sincronizada com resultado.php.
 */
$caminhos_por_dimensao = [

  'criar' => [
    'Desenvolvimento Web',
    'Automação e Prototipagem',
    'Engenharia de Software',
  ],

  'investigar' => [
    'QA / Software Testing',
    'Data Analytics',
    'Troubleshooting Técnico',
  ],

  'conectar' => [
    'Infraestrutura e Redes',
    'Cloud',
    'DevOps / Observabilidade',
  ],

  'proteger' => [
    'Cibersegurança',
    'SOC / Security Operations',
    'Cloud Security',
  ],

  'apoiar' => [
    'Technical Support',
    'Application Support',
    'Customer / Technical Success',
  ],
];

/*
 * ============================================================
 * COMBINAÇÕES ESPECÍFICAS
 * ============================================================
 */
$caminhos_combinados = [

  'apoiar|investigar' => [
    'Application Support',
    'Technical Support',
    'Troubleshooting',
  ],

  'criar|investigar' => [
    'Desenvolvimento de Software',
    'QA Automation',
    'Prototipagem',
  ],

  'conectar|proteger' => [
    'Infraestrutura e Redes',
    'Cloud / DevOps',
    'Segurança de Infraestrutura',
  ],

  'investigar|proteger' => [
    'Cibersegurança',
    'SOC / Security Operations',
    'Análise de Incidentes',
  ],

  'apoiar|criar' => [
    'Front-end',
    'UX Engineering',
    'Produtos orientados ao usuário',
  ],

  'conectar|investigar' => [
    'Data Analytics',
    'Observabilidade',
    'Infraestrutura / Troubleshooting',
  ],
];

/*
 * ============================================================
 * CHAVE DE COMBINAÇÃO
 * ============================================================
 */
function chave_roadmap($a, $b) {

  $itens = [$a, $b];

  sort($itens);

  return implode('|', $itens);
}

/*
 * ============================================================
 * INTERCALAR DUAS LISTAS
 * ============================================================
 *
 * Utilizado em combinações que ainda não possuem
 * interpretação específica cadastrada.
 *
 * Dessa forma, as duas forças aparecem no Roadmap.
 */
function intercalar_roadmap(
  $lista_a,
  $lista_b,
  $limite = 3
) {

  $resultado = [];

  $lista_a = is_array($lista_a)
    ? array_values($lista_a)
    : [];

  $lista_b = is_array($lista_b)
    ? array_values($lista_b)
    : [];

  $maior = max(
    count($lista_a),
    count($lista_b)
  );

  for ($i = 0; $i < $maior; $i++) {

    if (
      isset($lista_a[$i]) &&
      !in_array(
        $lista_a[$i],
        $resultado,
        true
      )
    ) {

      $resultado[] = $lista_a[$i];

      if (count($resultado) >= $limite) {
        break;
      }
    }

    if (
      isset($lista_b[$i]) &&
      !in_array(
        $lista_b[$i],
        $resultado,
        true
      )
    ) {

      $resultado[] = $lista_b[$i];

      if (count($resultado) >= $limite) {
        break;
      }
    }
  }

  return $resultado;
}

/*
 * ============================================================
 * DIMENSÕES EXPLORATÓRIAS
 * ============================================================
 *
 * Usa exatamente a mesma regra compartilhada
 * utilizada pelo resultado.php.
 */
$dimensoes_exploratorias =
  dimensoes_exploratorias_finder(
    $perguntas,
    3
  );

$empate_no_corte =
  empate_no_corte_finder(
    $perguntas,
    3
  );

/*
 * ============================================================
 * DEFINIÇÃO DOS CAMINHOS
 * ============================================================
 */
$caminhos = [];

if (
  $tipo_resultado === 'combinado' &&
  $dim1 &&
  $dim2
) {

  $chave =
    chave_roadmap(
      $dim1,
      $dim2
    );

  if (
    isset(
      $caminhos_combinados[$chave]
    )
  ) {

    $caminhos =
      $caminhos_combinados[$chave];

  } else {

    $caminhos =
      intercalar_roadmap(
        $caminhos_por_dimensao[$dim1] ?? [],
        $caminhos_por_dimensao[$dim2] ?? [],
        3
      );
  }

} elseif (
  $tipo_resultado === 'predominante' &&
  $dim1
) {

  $caminhos =
    $caminhos_por_dimensao[$dim1]
    ?? [];

} else {

  foreach (
    $dimensoes_exploratorias
    as $dimensao
  ) {

    $opcoes =
      $caminhos_por_dimensao[$dimensao]
      ?? [];

    if (!empty($opcoes)) {

      $caminho =
        $opcoes[0];

      if (
        !in_array(
          $caminho,
          $caminhos,
          true
        )
      ) {

        $caminhos[] =
          $caminho;
      }
    }
  }
}

$caminhos =
  array_values(
    array_unique(
      $caminhos
    )
  );

/*
 * ============================================================
 * CONTEÚDO DOS ROADMAPS
 * ============================================================
 */
$roadmaps = [

  'Desenvolvimento Web' => [
    'fundamento' =>
      'HTML, CSS, JavaScript e funcionamento básico da Web.',

    'pratica' =>
      'Construa uma página responsiva com interação em JavaScript.',

    'prova' =>
      'Publique o projeto e documente o que você construiu e aprendeu.',
  ],

  'Automação e Prototipagem' => [
    'fundamento' =>
      'Lógica de programação, scripts e automação de tarefas.',

    'pratica' =>
      'Escolha uma tarefa repetitiva e crie uma pequena automação.',

    'prova' =>
      'Registre o problema, a solução criada e o ganho obtido.',
  ],

  'Engenharia de Software' => [
    'fundamento' =>
      'Lógica, estruturas de dados, Git e organização de código.',

    'pratica' =>
      'Desenvolva uma aplicação pequena dividindo responsabilidades em funções e módulos.',

    'prova' =>
      'Mantenha o código em um repositório com README e histórico de evolução.',
  ],

  'Desenvolvimento de Software' => [
    'fundamento' =>
      'Lógica, programação, Git e fundamentos de aplicações.',

    'pratica' =>
      'Construa uma aplicação pequena que resolva um problema concreto.',

    'prova' =>
      'Publique o código com documentação do funcionamento.',
  ],

  'QA Automation' => [
    'fundamento' =>
      'Testes de software, casos de teste e lógica de programação.',

    'pratica' =>
      'Automatize testes de um fluxo simples de uma aplicação.',

    'prova' =>
      'Documente cenários, resultados esperados e falhas encontradas.',
  ],

  'Prototipagem' => [
    'fundamento' =>
      'Validação de hipóteses, protótipos e experimentação.',

    'pratica' =>
      'Transforme uma ideia em um protótipo funcional pequeno.',

    'prova' =>
      'Teste o protótipo e registre o que mudou após a experiência.',
  ],

  'QA / Software Testing' => [
    'fundamento' =>
      'Casos de teste, critérios de aceite, bugs e qualidade de software.',

    'pratica' =>
      'Teste uma aplicação real e registre cenários de sucesso e falha.',

    'prova' =>
      'Crie um relatório de bugs com passos para reprodução e evidências.',
  ],

  'Data Analytics' => [
    'fundamento' =>
      'Planilhas, SQL, métricas e interpretação de dados.',

    'pratica' =>
      'Analise um pequeno conjunto de dados e responda perguntas objetivas.',

    'prova' =>
      'Produza uma análise com conclusões sustentadas pelos dados.',
  ],

  'Troubleshooting Técnico' => [
    'fundamento' =>
      'Diagnóstico estruturado, logs, redes e sistemas.',

    'pratica' =>
      'Escolha uma falha técnica e siga problema → hipótese → teste → evidência → conclusão.',

    'prova' =>
      'Documente o diagnóstico e explique como chegou à causa.',
  ],

  'Troubleshooting' => [
    'fundamento' =>
      'Diagnóstico estruturado, sistemas, redes e análise de evidências.',

    'pratica' =>
      'Reproduza uma falha e teste hipóteses até identificar sua causa.',

    'prova' =>
      'Crie uma documentação curta com sintomas, testes e solução.',
  ],

  'Infraestrutura e Redes' => [
    'fundamento' =>
      'TCP/IP, DNS, DHCP, dispositivos de rede e sistemas operacionais.',

    'pratica' =>
      'Monte ou simule uma pequena rede e observe a comunicação entre os dispositivos.',

    'prova' =>
      'Documente a topologia, os testes realizados e problemas encontrados.',
  ],

  'Cloud' => [
    'fundamento' =>
      'Computação em nuvem, redes, máquinas virtuais e serviços.',

    'pratica' =>
      'Explore um laboratório de cloud e configure um serviço simples.',

    'prova' =>
      'Documente arquitetura, configuração e resultado do laboratório.',
  ],

  'DevOps / Observabilidade' => [
    'fundamento' =>
      'Linux, redes, Git, logs, métricas e conceitos de CI/CD.',

    'pratica' =>
      'Observe logs e métricas de uma aplicação e identifique seu comportamento.',

    'prova' =>
      'Crie uma documentação mostrando o que foi monitorado e o que descobriu.',
  ],

  'Cloud / DevOps' => [
    'fundamento' =>
      'Linux, redes, cloud, Git e automação.',

    'pratica' =>
      'Implemente uma aplicação simples em um ambiente de laboratório.',

    'prova' =>
      'Documente o fluxo da aplicação até a infraestrutura.',
  ],

  'Observabilidade' => [
    'fundamento' =>
      'Logs, métricas, monitoramento e comportamento de sistemas.',

    'pratica' =>
      'Monitore uma aplicação ou serviço e procure padrões de comportamento.',

    'prova' =>
      'Produza um pequeno relatório com sinais e conclusões observadas.',
  ],

  'Infraestrutura / Troubleshooting' => [
    'fundamento' =>
      'Redes, sistemas operacionais e diagnóstico de falhas.',

    'pratica' =>
      'Simule uma falha de conectividade ou serviço e investigue sua causa.',

    'prova' =>
      'Documente hipótese, testes, evidências e resolução.',
  ],

  'Cibersegurança' => [
    'fundamento' =>
      'Redes, sistemas operacionais, princípios de segurança e controle de acesso.',

    'pratica' =>
      'Realize laboratórios seguros de análise de vulnerabilidades e configuração defensiva.',

    'prova' =>
      'Documente riscos encontrados e medidas de mitigação.',
  ],

  'SOC / Security Operations' => [
    'fundamento' =>
      'Logs, redes, eventos de segurança e resposta a incidentes.',

    'pratica' =>
      'Analise eventos de um laboratório e identifique comportamentos suspeitos.',

    'prova' =>
      'Produza um relatório simples de investigação de incidente.',
  ],

  'Cloud Security' => [
    'fundamento' =>
      'Cloud, identidade, permissões, redes e princípios de segurança.',

    'pratica' =>
      'Analise configurações de segurança em um ambiente de laboratório.',

    'prova' =>
      'Documente riscos, impacto e recomendações de proteção.',
  ],

  'Segurança de Infraestrutura' => [
    'fundamento' =>
      'Redes, sistemas, hardening e controle de acesso.',

    'pratica' =>
      'Revise a configuração de um ambiente de laboratório procurando pontos de risco.',

    'prova' =>
      'Crie um checklist de segurança com justificativas.',
  ],

  'Análise de Incidentes' => [
    'fundamento' =>
      'Logs, eventos, redes e metodologia de investigação.',

    'pratica' =>
      'Investigue um cenário fictício de incidente usando evidências disponíveis.',

    'prova' =>
      'Monte uma linha do tempo e uma conclusão baseada nas evidências.',
  ],

  'Technical Support' => [
    'fundamento' =>
      'Sistemas operacionais, redes, atendimento técnico e troubleshooting.',

    'pratica' =>
      'Resolva problemas técnicos simulados seguindo uma investigação estruturada.',

    'prova' =>
      'Documente diagnóstico, solução e orientação fornecida ao usuário.',
  ],

  'Application Support' => [
    'fundamento' =>
      'Aplicações, logs, banco de dados básico, APIs e troubleshooting.',

    'pratica' =>
      'Investigue uma falha simulada em uma aplicação e identifique onde o fluxo quebra.',

    'prova' =>
      'Documente sintomas, evidências, causa provável e solução.',
  ],

  'Customer / Technical Success' => [
    'fundamento' =>
      'Produto, comunicação técnica, métricas e experiência do usuário.',

    'pratica' =>
      'Escolha uma dificuldade de uso e produza uma orientação clara para resolvê-la.',

    'prova' =>
      'Crie uma documentação ou tutorial que outra pessoa consiga seguir.',
  ],

  'Front-end' => [
    'fundamento' =>
      'HTML, CSS, JavaScript, responsividade e experiência do usuário.',

    'pratica' =>
      'Construa uma interface responsiva para resolver uma necessidade real.',

    'prova' =>
      'Publique o projeto e registre decisões de interface e implementação.',
  ],

  'UX Engineering' => [
    'fundamento' =>
      'Interfaces, acessibilidade, front-end e comportamento do usuário.',

    'pratica' =>
      'Construa e teste uma pequena interação de interface.',

    'prova' =>
      'Registre o problema, a solução e o aprendizado obtido no teste.',
  ],

  'Produtos orientados ao usuário' => [
    'fundamento' =>
      'Necessidades do usuário, produto, experimentação e métricas.',

    'pratica' =>
      'Escolha uma dificuldade real e desenhe uma solução pequena para testá-la.',

    'prova' =>
      'Documente hipótese, experimento, feedback e conclusão.',
  ],
];

/*
 * ============================================================
 * FALLBACK DE ROADMAP
 * ============================================================
 */
function obter_roadmap(
  $nome,
  $roadmaps
) {

  if (
    isset(
      $roadmaps[$nome]
    )
  ) {

    return $roadmaps[$nome];
  }

  return [

    'fundamento' =>
      'Pesquise os fundamentos essenciais dessa área e identifique as tecnologias mais utilizadas.',

    'pratica' =>
      'Realize um projeto ou laboratório pequeno relacionado a esse caminho.',

    'prova' =>
      'Documente o que fez, as dificuldades encontradas e o que aprendeu.',
  ];
}

/*
 * ============================================================
 * TÍTULO DO MAPA
 * ============================================================
 */
if (
  $tipo_resultado ===
  'predominante'
) {

  $origem_mapa =
    'Tendência predominante: ' .
    (
      $nomes[$dim1]
      ?? ucfirst((string)$dim1)
    );

} elseif (
  $tipo_resultado ===
  'combinado'
) {

  $origem_mapa =
    'Forças combinadas: ' .
    (
      $nomes[$dim1]
      ?? ucfirst((string)$dim1)
    ) .
    ' + ' .
    (
      $nomes[$dim2]
      ?? ucfirst((string)$dim2)
    );

} else {

  $origem_mapa =
    'Mapa distribuído';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
  >

  <title>
    TechPath Roadmap
  </title>

  <link
    rel="stylesheet"
    href="style.css"
  >

  <style>

    .roadmap-container {
      width: min(980px, calc(100% - 32px));
      margin: 42px auto 70px;
    }

    .roadmap-hero {
      text-align: center;
      margin-bottom: 30px;
    }

    .roadmap-hero h1 {
      margin-bottom: 12px;
    }

    .roadmap-hero p {
      max-width: 720px;
      margin: 0 auto;
      line-height: 1.65;
      opacity: .82;
    }

    .roadmap-origem {
      margin: 22px auto 0;
      display: inline-flex;
      padding: 8px 14px;
      border-radius: 999px;
      border: 1px solid rgba(91,124,255,.25);
      background: rgba(91,124,255,.10);
    }

    .roadmap-observacao {
      max-width: 760px;
      margin: 16px auto 0;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid rgba(91,124,255,.18);
      background: rgba(91,124,255,.06);
      color: var(--text-secondary);
      line-height: 1.55;
      font-size: .86rem;
    }

    .roadmap-caminho {
      margin-top: 22px;
      padding: 24px;
      border-radius: 18px;
      border: 1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.035);
    }

    .roadmap-caminho-numero {
      display: inline-flex;
      margin-bottom: 8px;
      font-size: .82rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      opacity: .65;
    }

    .roadmap-caminho h2 {
      margin: 0 0 20px;
    }

    .roadmap-etapas {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .roadmap-etapa {
      position: relative;
      padding: 18px;
      border-radius: 14px;
      background: rgba(255,255,255,.035);
      border: 1px solid rgba(255,255,255,.08);
    }

    .roadmap-etapa-numero {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin-bottom: 12px;
      background: rgba(91,124,255,.16);
      border: 1px solid rgba(91,124,255,.28);
      font-weight: 700;
    }

    .roadmap-etapa h3 {
      margin: 0 0 9px;
      font-size: 1rem;
    }

    .roadmap-etapa p {
      margin: 0;
      line-height: 1.55;
      opacity: .82;
    }

    .roadmap-metodo {
      margin-top: 30px;
      padding: 24px;
      border-radius: 18px;
      border: 1px solid rgba(138,108,255,.24);
      background: rgba(91,124,255,.07);
    }

    .roadmap-metodo h2 {
      margin-top: 0;
    }

    .roadmap-fluxo {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 16px;
    }

    .roadmap-fluxo span {
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.09);
    }

    .roadmap-acoes {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 30px;
    }

    .roadmap-nota {
      margin: 26px auto 0;
      max-width: 760px;
      text-align: center;
      line-height: 1.55;
      opacity: .65;
      font-size: .86rem;
    }

    @media (max-width: 760px) {

      .roadmap-etapas {
        grid-template-columns: 1fr;
      }

      .roadmap-container {
        margin-top: 28px;
      }

    }

  </style>

</head>

<body class="tela-resultado">

<header class="finder-header">

  <a
    href="index.php"
    class="header-logo"
  >

    <span class="logo-icon">
      &#9650;
    </span>

    <span class="logo-texto">
      TechPath<strong>Finder</strong>
    </span>

  </a>

  <div class="header-progresso-texto">
    Roadmap
  </div>

</header>

<div class="finder-progresso-bar-wrap">

  <div
    class="finder-progresso-bar"
    style="width: 100%"
  ></div>

</div>

<main class="roadmap-container">

  <section class="roadmap-hero">

    <div class="resultado-icone">
      &#9650;
    </div>

    <h1>
      Seu TechPath Roadmap
    </h1>

    <p>
      O Finder identificou sinais nas suas decisões.
      Agora o Roadmap transforma essas hipóteses
      em caminhos que você pode experimentar,
      observar e validar na prática.
    </p>

    <div class="roadmap-origem">
      <?= htmlspecialchars($origem_mapa) ?>
    </div>

    <?php if (
      $tipo_resultado === 'distribuido' &&
      $empate_no_corte
    ): ?>

      <div class="roadmap-observacao">
        Há dimensões empatadas na fronteira do mapa.
        O Roadmap preservou todas elas para exploração,
        sem criar um desempate artificial.
      </div>

    <?php endif; ?>

  </section>

  <?php if (!empty($caminhos)): ?>

    <?php foreach (
      $caminhos
      as $indice => $caminho
    ): ?>

      <?php

        $plano =
          obter_roadmap(
            $caminho,
            $roadmaps
          );

      ?>

      <section class="roadmap-caminho">

        <span class="roadmap-caminho-numero">

          Caminho
          <?= $indice + 1 ?>

        </span>

        <h2>
          <?= htmlspecialchars($caminho) ?>
        </h2>

        <div class="roadmap-etapas">

          <article class="roadmap-etapa">

            <div class="roadmap-etapa-numero">
              1
            </div>

            <h3>
              Fundamento
            </h3>

            <p>
              <?= htmlspecialchars(
                $plano['fundamento']
              ) ?>
            </p>

          </article>

          <article class="roadmap-etapa">

            <div class="roadmap-etapa-numero">
              2
            </div>

            <h3>
              Experimento
            </h3>

            <p>
              <?= htmlspecialchars(
                $plano['pratica']
              ) ?>
            </p>

          </article>

          <article class="roadmap-etapa">

            <div class="roadmap-etapa-numero">
              3
            </div>

            <h3>
              Evidência
            </h3>

            <p>
              <?= htmlspecialchars(
                $plano['prova']
              ) ?>
            </p>

          </article>

        </div>

      </section>

    <?php endforeach; ?>

  <?php else: ?>

    <section class="roadmap-caminho">

      <h2>
        Continue explorando
      </h2>

      <p>
        Seu mapa ainda está distribuído.
        Realize pequenos experimentos em diferentes
        áreas e volte ao Finder quando tiver novas
        evidências sobre suas preferências.
      </p>

    </section>

  <?php endif; ?>

  <section class="roadmap-metodo">

    <h2>
      Como usar este Roadmap
    </h2>

    <p>
      Você não precisa decidir sua carreira agora.
      Percorra os caminhos como experimentos
      e observe onde aparecem curiosidade,
      facilidade, persistência e vontade
      de continuar aprendendo.
    </p>

    <div class="roadmap-fluxo">

      <span>Aprender</span>
      <span>→</span>
      <span>Experimentar</span>
      <span>→</span>
      <span>Produzir evidência</span>
      <span>→</span>
      <span>Comparar</span>
      <span>→</span>
      <span>Decidir</span>

    </div>

  </section>

  <p class="roadmap-nota">
    O TechPath Roadmap utiliza os sinais
    produzidos pelo Finder para organizar
    possibilidades de exploração.
    Ele não representa garantia de adequação
    profissional nem substitui experiência prática.
  </p>

  <div class="roadmap-acoes">

    <a
      href="resultado.php"
      class="btn btn-primario"
    >
      Voltar ao meu mapa
    </a>

    <a
      href="recomecar.php"
      class="btn btn-secundario"
    >
      Refazer descoberta
    </a>

  </div>

</main>

<footer class="finder-footer">

  <p class="assinatura">
    Desenvolvido por: Ademir
  </p>

</footer>

</body>
</html>