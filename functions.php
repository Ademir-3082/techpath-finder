<?php

/*
 * ============================================================
 * FUNÇÕES GERAIS
 * ============================================================
 */

function ler_json($arquivo) {

  if (!file_exists($arquivo)) {
    return [];
  }

  $fh = fopen($arquivo, 'r');

  if (!$fh) {
    return [];
  }

  flock($fh, LOCK_SH);

  $tamanho = filesize($arquivo);

  $conteudo = $tamanho > 0
    ? fread($fh, $tamanho)
    : '';

  flock($fh, LOCK_UN);

  fclose($fh);

  $dados = json_decode($conteudo, true);

  return is_array($dados)
    ? $dados
    : [];
}


function salvar_json($arquivo, $dados) {

  $fh = fopen($arquivo, 'c+');

  if (!$fh) {
    return false;
  }

  flock($fh, LOCK_EX);

  ftruncate($fh, 0);
  rewind($fh);

  $json = json_encode(
    $dados,
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_UNICODE
  );

  if ($json === false) {

    flock($fh, LOCK_UN);
    fclose($fh);

    return false;
  }

  fwrite($fh, $json);
  fflush($fh);

  flock($fh, LOCK_UN);

  fclose($fh);

  return true;
}


function gerar_id() {
  return uniqid('', true);
}


/*
 * ============================================================
 * ESTRUTURA DO FINDER
 * ============================================================
 */

function total_etapas() {
  return 12;
}


function etapa_valida($etapa) {

  $etapa_validada = filter_var(
    $etapa,
    FILTER_VALIDATE_INT
  );

  return
    $etapa_validada !== false &&
    $etapa_validada >= 0 &&
    $etapa_validada < total_etapas();
}


/*
 * ============================================================
 * SESSÃO
 * ============================================================
 */

function inicializar_sessao_finder() {

  if (
    !isset($_SESSION['finder_respostas']) ||
    !is_array($_SESSION['finder_respostas'])
  ) {
    $_SESSION['finder_respostas'] = [];
  }

  if (
    !isset($_SESSION['finder_etapa']) ||
    !etapa_valida($_SESSION['finder_etapa'])
  ) {
    $_SESSION['finder_etapa'] = 0;
  }
}


function resetar_sessao_finder() {

  $_SESSION['finder_respostas'] = [];
  $_SESSION['finder_etapa'] = 0;
}


function obter_resposta_etapa($etapa) {

  if (!etapa_valida($etapa)) {
    return null;
  }

  return
    $_SESSION['finder_respostas'][$etapa]
    ?? null;
}


function salvar_resposta_etapa(
  $etapa,
  $alternativa
) {

  if (!etapa_valida($etapa)) {
    return false;
  }

  $_SESSION['finder_respostas'][$etapa] =
    (int)$alternativa;

  return true;
}


/*
 * ============================================================
 * VALIDAÇÃO DE RESPOSTA
 * ============================================================
 */

function resposta_finder_valida(
  $perguntas,
  $etapa,
  $alternativa
) {

  if (!etapa_valida($etapa)) {
    return false;
  }

  $indice = filter_var(
    $alternativa,
    FILTER_VALIDATE_INT,
    [
      'options' => [
        'min_range' => 0
      ]
    ]
  );

  if ($indice === false) {
    return false;
  }

  if (
    !is_array($perguntas) ||
    !isset(
      $perguntas[$etapa]
      ['alternativas']
      [$indice]
    )
  ) {
    return false;
  }

  return true;
}


/*
 * ============================================================
 * CONCLUSÃO DO FINDER
 * ============================================================
 */

function finder_concluido(
  $perguntas = null
) {

  $respostas =
    $_SESSION['finder_respostas']
    ?? [];

  if (!is_array($respostas)) {
    return false;
  }

  if (
    count($respostas) <
    total_etapas()
  ) {
    return false;
  }

  for (
    $etapa = 0;
    $etapa < total_etapas();
    $etapa++
  ) {

    if (
      !array_key_exists(
        $etapa,
        $respostas
      )
    ) {
      return false;
    }

    $resposta =
      $respostas[$etapa];

    if (is_array($perguntas)) {

      if (
        !resposta_finder_valida(
          $perguntas,
          $etapa,
          $resposta
        )
      ) {
        return false;
      }

    } else {

      $indice = filter_var(
        $resposta,
        FILTER_VALIDATE_INT,
        [
          'options' => [
            'min_range' => 0,
            'max_range' => 4
          ]
        ]
      );

      if ($indice === false) {
        return false;
      }
    }
  }

  return true;
}


/*
 * ============================================================
 * PROGRESSO
 * ============================================================
 */

function progresso_percentual($etapa) {

  $total = total_etapas();

  if ($total <= 0) {
    return 0;
  }

  $etapa = (int)$etapa;

  $etapa = max(
    0,
    min(
      $etapa,
      $total
    )
  );

  return round(
    ($etapa / $total)
    * 100
  );
}


/*
 * ============================================================
 * MOTOR DE INTERPRETAÇÃO — TECHPATH FINDER
 * ============================================================
 */

function dimensoes_finder() {

  return [
    'criar',
    'investigar',
    'conectar',
    'proteger',
    'apoiar',
  ];
}


/*
 * ============================================================
 * CALCULAR PERFIL
 * ============================================================
 */

function calcular_perfil_finder(
  $perguntas
) {

  $principais =
    array_fill_keys(
      dimensoes_finder(),
      0
    );

  $secundarias =
    array_fill_keys(
      dimensoes_finder(),
      0
    );

  $evidencias = [];

  $total_validas = 0;

  $respostas =
    $_SESSION['finder_respostas']
    ?? [];

  if (!is_array($respostas)) {
    $respostas = [];
  }

  foreach (
    $respostas
    as $etapa => $indice_alternativa
  ) {

    $etapa = (int)$etapa;

    if (
      !resposta_finder_valida(
        $perguntas,
        $etapa,
        $indice_alternativa
      )
    ) {
      continue;
    }

    $indice_alternativa =
      (int)$indice_alternativa;

    $alternativa =
      $perguntas[$etapa]
      ['alternativas']
      [$indice_alternativa];

    $principal =
      $alternativa['principal']
      ?? null;

    $secundaria =
      $alternativa['secundaria']
      ?? null;

    $evidencia =
      $alternativa['evidencia']
      ?? null;

    $total_validas++;

    if (
      $principal !== null &&
      array_key_exists(
        $principal,
        $principais
      )
    ) {
      $principais[$principal]++;
    }

    if (
      $secundaria !== null &&
      array_key_exists(
        $secundaria,
        $secundarias
      )
    ) {
      $secundarias[$secundaria]++;
    }

    if (!empty($evidencia)) {

      $evidencias[] = [
        'etapa' =>
          $etapa + 1,

        'dimensao' =>
          $principal,

        'secundaria' =>
          $secundaria,

        'texto' =>
          $evidencia,
      ];
    }
  }

  arsort($principais);
  arsort($secundarias);

  return [
    'pontuacao' =>
      $principais,

    'secundarias' =>
      $secundarias,

    'evidencias' =>
      $evidencias,

    'total_respostas' =>
      $total_validas,
  ];
}


/*
 * ============================================================
 * RANKING
 * ============================================================
 */

function ranking_finder(
  $perguntas
) {

  $perfil =
    calcular_perfil_finder(
      $perguntas
    );

  $ranking = [];

  $indice = 0;
  $posicao = 0;

  $pontos_anteriores = null;

  foreach (
    $perfil['pontuacao']
    as $dimensao => $pontos
  ) {

    $indice++;

    if (
      $pontos_anteriores === null ||
      $pontos !== $pontos_anteriores
    ) {
      $posicao = $indice;
    }

    $ranking[] = [
      'posicao' =>
        $posicao,

      'dimensao' =>
        $dimensao,

      'pontos' =>
        $pontos,
    ];

    $pontos_anteriores =
      $pontos;
  }

  return $ranking;
}


/*
 * ============================================================
 * DIMENSÕES EXPLORATÓRIAS
 * ============================================================
 *
 * Retorna todas as dimensões cuja posição no ranking esteja
 * dentro do limite solicitado.
 *
 * Exemplo:
 *
 * 1º Criar        3
 * 1º Investigar   3
 * 3º Conectar     2
 * 3º Proteger     2
 * 3º Apoiar       2
 *
 * limite_posicao = 3
 *
 * Resultado:
 * Criar, Investigar, Conectar, Proteger e Apoiar.
 *
 * Assim nenhum empate é quebrado artificialmente.
 */

function dimensoes_exploratorias_finder(
  $perguntas,
  $limite_posicao = 3
) {

  $ranking =
    ranking_finder(
      $perguntas
    );

  $dimensoes = [];

  foreach (
    $ranking
    as $item
  ) {

    if (
      $item['posicao']
      <= $limite_posicao
    ) {

      $dimensoes[] =
        $item['dimensao'];
    }
  }

  return array_values(
    array_unique(
      $dimensoes
    )
  );
}


/*
 * ============================================================
 * EMPATE NO CORTE EXPLORATÓRIO
 * ============================================================
 *
 * Indica quando a última posição incluída possui mais de uma
 * dimensão empatada.
 */

function empate_no_corte_finder(
  $perguntas,
  $limite_posicao = 3
) {

  $ranking =
    ranking_finder(
      $perguntas
    );

  $quantidade_na_posicao = 0;

  foreach (
    $ranking
    as $item
  ) {

    if (
      $item['posicao']
      === $limite_posicao
    ) {
      $quantidade_na_posicao++;
    }
  }

  return
    $quantidade_na_posicao > 1;
}


/*
 * ============================================================
 * TOP DO FINDER
 * ============================================================
 */

function top_finder(
  $perguntas,
  $limite_posicao = 3
) {

  $ranking =
    ranking_finder(
      $perguntas
    );

  return array_values(
    array_filter(
      $ranking,
      function ($item)
      use ($limite_posicao) {

        return
          $item['posicao']
          <= $limite_posicao;
      }
    )
  );
}


/*
 * ============================================================
 * FORÇA DO PERFIL
 * ============================================================
 */

function forca_perfil_finder(
  $perguntas
) {

  $perfil =
    calcular_perfil_finder(
      $perguntas
    );

  $pontuacao =
    $perfil['pontuacao'];

  if (
    empty($pontuacao) ||
    $perfil['total_respostas'] <= 0
  ) {

    return [
      'percentual' => 0,
      'nivel' => 'indefinido',
      'lideres' => [],
      'pontos_lider' => 0,
      'empate_lideranca' => false,
    ];
  }

  $pontos_lider =
    max($pontuacao);

  $total =
    $perfil['total_respostas'];

  $lideres = [];

  foreach (
    $pontuacao
    as $dimensao => $pontos
  ) {

    if (
      $pontos ===
      $pontos_lider
    ) {

      $lideres[] =
        $dimensao;
    }
  }

  $percentual =
    $total > 0
      ? round(
          (
            $pontos_lider
            / $total
          )
          * 100
        )
      : 0;

  if ($percentual >= 67) {

    $nivel = 'forte';

  } elseif ($percentual >= 42) {

    $nivel = 'moderado';

  } else {

    $nivel = 'distribuido';
  }

  return [
    'percentual' =>
      $percentual,

    'nivel' =>
      $nivel,

    'lideres' =>
      $lideres,

    'pontos_lider' =>
      $pontos_lider,

    'empate_lideranca' =>
      count($lideres) > 1,
  ];
}