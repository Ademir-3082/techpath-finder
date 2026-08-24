<?php
session_start();

require 'functions.php';
require 'perguntas.php';

$total = total_etapas();

$respostas =
  $_SESSION['finder_respostas']
  ?? [];

$tem_sessao =
  is_array($respostas) &&
  count($respostas) > 0;

$etapa_atual =
  (int)(
    $_SESSION['finder_etapa']
    ?? 0
  );

if (!etapa_valida($etapa_atual)) {

  $etapa_atual = 0;

  $_SESSION['finder_etapa'] = 0;
}

/*
 * ============================================================
 * CONCLUSÃO RIGOROSA
 * ============================================================
 *
 * Só considera o Finder concluído quando:
 *
 * - todas as etapas esperadas existem;
 * - todas possuem resposta;
 * - cada resposta corresponde a uma alternativa válida
 *   da respectiva pergunta.
 */
$concluido =
  finder_concluido(
    $perguntas
  );

/*
 * Número exibido ao usuário.
 * Nunca ultrapassa o total de etapas.
 */
$etapa_exibida =
  min(
    $etapa_atual + 1,
    $total
  );
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
    TechPath Finder
  </title>

  <link
    rel="stylesheet"
    href="style.css"
  >

</head>

<body class="tela-home">

  <div class="home-wrapper">

    <div class="home-brand">

      <div class="brand-icon">
        &#9650;
      </div>

      <h1 class="brand-nome">
        TechPath<span>Finder</span>
      </h1>

      <p class="brand-descricao">
        Explore diferentes formas de pensar
        e descubra caminhos tecnológicos
        que podem valer a pena testar
        na prática.
      </p>

    </div>

    <div class="home-card">

      <?php if ($concluido): ?>

        <div class="sessao-info">

          <div class="sessao-badge">
            Finder concluído
          </div>

          <p class="sessao-detalhe">

            Você já concluiu as

            <strong>
              <?= $total ?> etapas
            </strong>

            do TechPath Finder.

            Deseja revisar seu resultado
            ou recomeçar?

          </p>

        </div>

        <div class="home-acoes">

          <a
            href="resultado.php"
            class="btn btn-primario"
          >
            Ver resultado
          </a>

          <a
            href="recomecar.php"
            class="btn btn-secundario"
          >
            Recomeçar do zero
          </a>

        </div>

      <?php elseif ($tem_sessao): ?>

        <div class="sessao-info">

          <div class="sessao-badge">
            Sessão em andamento
          </div>

          <p class="sessao-detalhe">

            Você está na etapa

            <strong>
              <?= $etapa_exibida ?>
            </strong>

            de

            <strong>
              <?= $total ?>
            </strong>.

            Deseja retomar de onde parou?

          </p>

        </div>

        <div class="home-acoes">

          <a
            href="finder.php"
            class="btn btn-primario"
          >
            Retomar Finder
          </a>

          <a
            href="recomecar.php"
            class="btn btn-secundario"
          >
            Recomeçar do zero
          </a>

        </div>

      <?php else: ?>

        <div class="home-intro">

          <h2>
            Pronto para começar?
          </h2>

          <p>

            O Finder percorre

            <strong>
              <?= $total ?> etapas
            </strong>

            com uma pergunta por vez.

            Você pode pausar e retomar
            quando quiser — seu progresso
            permanece salvo durante a sessão.

          </p>

          <ul class="home-features">

            <li>
              <span class="feat-icon">
                &#10003;
              </span>

              <?= $total ?>
              perguntas situacionais
            </li>

            <li>
              <span class="feat-icon">
                &#10003;
              </span>

              Progresso salvo durante a sessão
            </li>

            <li>
              <span class="feat-icon">
                &#10003;
              </span>

              Possibilidade de avançar
              e revisar respostas
            </li>

            <li>
              <span class="feat-icon">
                &#10003;
              </span>

              Mapa de tendências + Roadmap personalizado ao final
            </li>

          </ul>

        </div>

        <div class="home-acoes">

          <a
            href="finder.php"
            class="btn btn-primario"
          >
            Iniciar Finder
          </a>

        </div>

      <?php endif; ?>

    </div>

    <p class="assinatura">
      Desenvolvido por: Ademir
    </p>

  </div>

</body>

</html>