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
$forca   = forca_perfil_finder($perguntas);

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
 * DESCRIÇÕES
 * ============================================================
 */
$descricoes = [

  'criar' =>
    'Transformar possibilidades em algo concreto, experimentar e construir soluções.',

  'investigar' =>
    'Observar comportamentos, buscar evidências, testar hipóteses e compreender causas.',

  'conectar' =>
    'Perceber relações, dependências, fluxos e como diferentes partes funcionam juntas.',

  'proteger' =>
    'Considerar limites, impacto, estabilidade, prevenção e reversibilidade.',

  'apoiar' =>
    'Compreender necessidades, remover barreiras e facilitar o avanço de outras pessoas.',
];

/*
 * ============================================================
 * EXPERIMENTOS
 * ============================================================
 */
$experimentos_dimensao = [

  'criar' =>
    'Construa uma solução pequena para um problema concreto e observe quais partes do processo mais despertam sua curiosidade.',

  'investigar' =>
    'Escolha um problema simples e siga: problema → hipótese → teste → evidência → conclusão.',

  'conectar' =>
    'Mapeie como ferramentas, sistemas ou serviços de uma solução dependem uns dos outros.',

  'proteger' =>
    'Analise um cenário fictício de falha ou acesso indevido e identifique impacto, limites e formas de prevenção.',

  'apoiar' =>
    'Ajude alguém a resolver uma dificuldade tecnológica e observe como você investiga, explica e acompanha a solução.',
];

/*
 * ============================================================
 * HIPÓTESES POR DIMENSÃO
 * ============================================================
 */
$hipoteses_por_dimensao = [

  'criar' => [
    'Desenvolvimento Web',
    'Automação e Prototipagem',
    'Engenharia de Software',
  ],

  'investigar' => [
    'QA / Software Testing',
    'Data Analytics',
    'Troubleshooting técnico',
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
$hipoteses_combinadas = [

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
function chave_combinacao($a, $b) {

  $itens = [$a, $b];

  sort($itens);

  return implode('|', $itens);
}

/*
 * ============================================================
 * INTERCALAR DUAS LISTAS
 * ============================================================
 */
function intercalar_hipoteses(
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
      !in_array($lista_a[$i], $resultado, true)
    ) {

      $resultado[] = $lista_a[$i];

      if (count($resultado) >= $limite) {
        break;
      }
    }

    if (
      isset($lista_b[$i]) &&
      !in_array($lista_b[$i], $resultado, true)
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
 * HIPÓTESES TECH
 * ============================================================
 */
$hipoteses = [];

if (
  $tipo_resultado === 'combinado' &&
  $dim1 &&
  $dim2
) {

  $chave =
    chave_combinacao(
      $dim1,
      $dim2
    );

  if (
    isset(
      $hipoteses_combinadas[$chave]
    )
  ) {

    $hipoteses =
      $hipoteses_combinadas[$chave];

  } else {

    $hipoteses =
      intercalar_hipoteses(
        $hipoteses_por_dimensao[$dim1] ?? [],
        $hipoteses_por_dimensao[$dim2] ?? [],
        3
      );
  }

} elseif (
  $tipo_resultado === 'predominante' &&
  $dim1
) {

  $hipoteses =
    $hipoteses_por_dimensao[$dim1]
    ?? [];

} else {

  foreach (
    $dimensoes_exploratorias
    as $dimensao
  ) {

    $opcoes =
      $hipoteses_por_dimensao[$dimensao]
      ?? [];

    if (!empty($opcoes)) {

      $hipotese = $opcoes[0];

      if (
        !in_array(
          $hipotese,
          $hipoteses,
          true
        )
      ) {

        $hipoteses[] = $hipotese;
      }
    }
  }
}

$hipoteses =
  array_values(
    array_unique($hipoteses)
  );

/*
 * ============================================================
 * EVIDÊNCIAS
 * ============================================================
 */
$evidencias_selecionadas = [];

if ($tipo_resultado === 'predominante') {

  foreach (
    $perfil['evidencias']
    as $evidencia
  ) {

    if (
      $evidencia['dimensao'] === $dim1
    ) {

      $evidencias_selecionadas[] =
        $evidencia['texto'];
    }

    if (
      count($evidencias_selecionadas) >= 4
    ) {
      break;
    }
  }

} elseif ($tipo_resultado === 'combinado') {

  foreach (
    $perfil['evidencias']
    as $evidencia
  ) {

    if (
      $evidencia['dimensao'] === $dim1 ||
      $evidencia['dimensao'] === $dim2
    ) {

      $evidencias_selecionadas[] =
        $evidencia['texto'];
    }

    if (
      count($evidencias_selecionadas) >= 4
    ) {
      break;
    }
  }

} else {

  $dimensoes_usadas = [];

  foreach (
    $perfil['evidencias']
    as $evidencia
  ) {

    if (
      in_array(
        $evidencia['dimensao'],
        $dimensoes_exploratorias,
        true
      ) &&
      !in_array(
        $evidencia['dimensao'],
        $dimensoes_usadas,
        true
      )
    ) {

      $evidencias_selecionadas[] =
        $evidencia['texto'];

      $dimensoes_usadas[] =
        $evidencia['dimensao'];
    }

    if (
      count($evidencias_selecionadas) >= 5
    ) {
      break;
    }
  }
}

/*
 * ============================================================
 * TEXTO DO MAPA
 * ============================================================
 */
if ($tipo_resultado === 'predominante') {

  $titulo_mapa =
    'Tendência predominante: ' .
    (
      $nomes[$dim1]
      ?? ucfirst((string)$dim1)
    );

  $texto_mapa =
    'Nas situações exploradas, esta foi a forma de abordagem que apareceu com maior consistência nas suas decisões.';

} elseif ($tipo_resultado === 'combinado') {

  $titulo_mapa =
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

  $texto_mapa =
    'Duas formas de abordar problemas apareceram com força semelhante. O TechPath preserva essa combinação em vez de forçar um único vencedor.';

} else {

  $titulo_mapa =
    'Mapa distribuído';

  $texto_mapa =
    'Suas decisões utilizaram diferentes formas de abordar problemas. Não existe evidência suficiente para destacar uma única direção neste momento — e isso também é um resultado válido.';
}

/*
 * ============================================================
 * EXPERIMENTOS
 * ============================================================
 */
$experimentos = [];

if (
  $tipo_resultado === 'predominante' &&
  $dim1
) {

  if (
    isset(
      $experimentos_dimensao[$dim1]
    )
  ) {

    $experimentos[] =
      $experimentos_dimensao[$dim1];
  }

  if (
    $dim2 &&
    $dim2 !== $dim1 &&
    isset(
      $experimentos_dimensao[$dim2]
    )
  ) {

    $experimentos[] =
      $experimentos_dimensao[$dim2];
  }

} elseif (
  $tipo_resultado === 'combinado' &&
  $dim1 &&
  $dim2
) {

  if (
    isset(
      $experimentos_dimensao[$dim1]
    )
  ) {

    $experimentos[] =
      $experimentos_dimensao[$dim1];
  }

  if (
    isset(
      $experimentos_dimensao[$dim2]
    )
  ) {

    $experimentos[] =
      $experimentos_dimensao[$dim2];
  }

} else {

  foreach (
    $dimensoes_exploratorias
    as $dimensao
  ) {

    if (
      isset(
        $experimentos_dimensao[$dimensao]
      )
    ) {

      $experimentos[] =
        $experimentos_dimensao[$dimensao];
    }
  }
}

$experimentos =
  array_values(
    array_unique(
      array_filter($experimentos)
    )
  );

/*
 * ============================================================
 * ROADMAP
 * ============================================================
 */
$roadmap_url = 'roadmap.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
  >

  <title>Seu Mapa TechPath</title>

  <link rel="stylesheet" href="style.css">

  <style>

    .mapa-bloco {
      margin-top: 22px;
      text-align: left;
    }

    .mapa-bloco h3 {
      margin-bottom: 10px;
    }

    .mapa-grid {
      display: grid;
      gap: 10px;
      margin-top: 14px;
    }

    .mapa-item {
      padding: 14px 16px;
      border: 1px solid rgba(255,255,255,.10);
      border-radius: 12px;
      background: rgba(255,255,255,.035);
    }

    .mapa-item-topo {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 8px;
    }

    .mapa-barra {
      height: 6px;
      border-radius: 999px;
      background: rgba(255,255,255,.08);
      overflow: hidden;
    }

    .mapa-barra > span {
      display: block;
      height: 100%;
      background: linear-gradient(
        90deg,
        #5b7cff,
        #8a6cff
      );
      border-radius: inherit;
    }

    .hipoteses-grid,
    .experimentos-grid {
      display: grid;
      gap: 10px;
      margin-top: 12px;
    }

    .hipotese-card,
    .experimento-card {
      padding: 14px 16px;
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.035);
    }

    .evidencias-lista {
      margin: 12px 0 0;
      padding-left: 20px;
    }

    .evidencias-lista li {
      margin-bottom: 8px;
    }

    .mapa-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 12px;
    }

    .mapa-tag {
      display: inline-flex;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(91,124,255,.12);
      border: 1px solid rgba(91,124,255,.22);
      font-size: .85rem;
    }

    .mapa-observacao {
      margin-top: 12px;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid rgba(91,124,255,.18);
      background: rgba(91,124,255,.06);
      font-size: .86rem;
      line-height: 1.55;
      text-align: left;
      color: var(--text-secondary);
    }

    .roadmap-cta {
      margin-top: 26px;
      padding: 20px;
      border-radius: 16px;
      border: 1px solid rgba(138,108,255,.25);
      background: rgba(91,124,255,.07);
      text-align: center;
    }

    .resultado-nota {
      opacity: .72;
      font-size: .85rem;
      margin-top: 18px;
      line-height: 1.5;
    }

  </style>

</head>

<body class="tela-resultado">

<header class="finder-header">

  <a
    href="index.php"
    class="header-logo"
  >
    <span class="logo-icon">&#9650;</span>

    <span class="logo-texto">
      TechPath<strong>Finder</strong>
    </span>
  </a>

  <div class="header-progresso-texto">
    Concluído
  </div>

</header>

<div class="finder-progresso-bar-wrap">
  <div
    class="finder-progresso-bar"
    style="width: 100%"
  ></div>
</div>

<main class="finder-main">

  <div class="finder-card resultado-card">

    <div class="resultado-icone">
      &#10003;
    </div>

    <h2 class="resultado-titulo">
      Seu Mapa TechPath
    </h2>

    <p class="resultado-desc">
      Suas <?= (int)$total ?> decisões foram
      transformadas em sinais de exploração.
      O objetivo não é escolher uma profissão por você,
      mas mostrar quais caminhos valem ser testados
      na prática.
    </p>

    <div class="resultado-aviso">

      <strong>
        <?= htmlspecialchars($titulo_mapa) ?>
      </strong>

      <br><br>

      <?= htmlspecialchars($texto_mapa) ?>

    </div>

    <div class="mapa-meta">

      <span class="mapa-tag">

        <?=
          $forca['nivel'] === 'forte'
            ? 'Sinal forte'
            : (
              $forca['nivel'] === 'moderado'
                ? 'Sinal moderado'
                : 'Mapa exploratório'
            )
        ?>

      </span>

      <?php if ($forca['empate_lideranca']): ?>

        <span class="mapa-tag">
          Liderança empatada
        </span>

      <?php endif; ?>

      <span class="mapa-tag">
        <?= (int)$perfil['total_respostas'] ?>
        decisões analisadas
      </span>

    </div>

    <?php if (
      $tipo_resultado === 'distribuido' &&
      $empate_no_corte
    ): ?>

      <div class="mapa-observacao">
        Há dimensões empatadas na fronteira do mapa.
        O TechPath preservou todas elas na exploração,
        em vez de criar um desempate artificial.
      </div>

    <?php endif; ?>

    <section class="mapa-bloco">

      <h3>
        Seu mapa de tendências
      </h3>

      <div class="mapa-grid">

        <?php foreach ($ranking as $item): ?>

          <?php

            $percentual_visual =
              $total > 0
                ? round(
                    (
                      $item['pontos']
                      / $total
                    )
                    * 100
                  )
                : 0;

          ?>

          <div class="mapa-item">

            <div class="mapa-item-topo">

              <strong>

                <?= (int)$item['posicao'] ?>º

                <?=
                  htmlspecialchars(
                    $nomes[$item['dimensao']]
                    ?? ucfirst($item['dimensao'])
                  )
                ?>

              </strong>

              <span>

                <?= (int)$item['pontos'] ?>

                <?=
                  ((int)$item['pontos'] === 1)
                    ? 'sinal'
                    : 'sinais'
                ?>

              </span>

            </div>

            <div class="mapa-barra">

              <span
                style="width: <?= $percentual_visual ?>%"
              ></span>

            </div>

            <p>
              <?=
                htmlspecialchars(
                  $descricoes[
                    $item['dimensao']
                  ] ?? ''
                )
              ?>
            </p>

          </div>

        <?php endforeach; ?>

      </div>

    </section>

    <section class="mapa-bloco">

      <h3>
        O que observamos
      </h3>

      <?php if (!empty($evidencias_selecionadas)): ?>

        <ul class="evidencias-lista">

          <?php foreach (
            $evidencias_selecionadas
            as $evidencia
          ): ?>

            <li>
              <?= htmlspecialchars(
                ucfirst($evidencia)
              ) ?>.
            </li>

          <?php endforeach; ?>

        </ul>

      <?php else: ?>

        <p>
          Não encontramos evidências suficientes
          para destacar comportamentos específicos
          nesta rodada.
        </p>

      <?php endif; ?>

    </section>

    <section class="mapa-bloco">

      <h3>SINAL</h3>

      <p>
        O mapa acima representa padrões que apareceram
        nas decisões tomadas durante o Finder.
        Ele descreve comportamento observado nesta
        experiência — não uma identidade permanente.
      </p>

    </section>

    <section class="mapa-bloco">

      <h3>HIPÓTESE</h3>

      <p>
        Com base nesses sinais, estes caminhos
        tecnológicos podem valer exploração:
      </p>

      <div class="hipoteses-grid">

        <?php foreach (
          $hipoteses
          as $indice => $hipotese
        ): ?>

          <div class="hipotese-card">

            <strong>
              Hipótese <?= $indice + 1 ?>
            </strong>

            <br>

            <?= htmlspecialchars($hipotese) ?>

          </div>

        <?php endforeach; ?>

      </div>

    </section>

    <section class="mapa-bloco">

      <h3>EXPERIMENTO</h3>

      <p>
        Em vez de escolher uma carreira apenas pelo nome,
        produza novas evidências sobre seu encaixe:
      </p>

      <div class="experimentos-grid">

        <?php foreach (
          $experimentos
          as $indice => $experimento
        ): ?>

          <div class="experimento-card">

            <strong>
              Experimento <?= $indice + 1 ?>
            </strong>

            <p>
              <?= htmlspecialchars($experimento) ?>
            </p>

          </div>

        <?php endforeach; ?>

      </div>

    </section>

    <div class="roadmap-cta">

      <h3>
        Você encontrou uma direção.
        Agora transforme-a em caminho.
      </h3>

      <p>
        O TechPath Roadmap transforma
        os sinais do Finder em próximos
        passos de desenvolvimento.
      </p>

      <a
        href="<?= htmlspecialchars($roadmap_url) ?>"
        class="btn btn-primario"
      >
        Criar meu Roadmap TechPath
      </a>

    </div>

    <p class="resultado-nota">
      O TechPath Finder é uma ferramenta de orientação
      exploratória. Ele não é um teste psicométrico,
      diagnóstico ou garantia de adequação profissional.
      As hipóteses apresentadas devem ser validadas
      por experiências práticas.
    </p>

    <div class="resultado-acoes">

      <a
        href="recomecar.php"
        class="btn btn-secundario"
      >
        Refazer descoberta
      </a>

      <a
        href="index.php"
        class="btn btn-secundario"
      >
        Voltar ao início
      </a>

    </div>

  </div>

</main>

<footer class="finder-footer">

  <p class="assinatura">
    Desenvolvido por: Ademir
  </p>

</footer>

</body>
</html>