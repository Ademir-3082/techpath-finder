<?php
session_start();

require 'functions.php';
require 'perguntas.php';

inicializar_sessao_finder();

/*
 * ============================================================
 * PROCESSAR POST
 * ============================================================
 * Salva resposta, avança ou volta entre etapas.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $acao       = $_POST['acao'] ?? '';
  $etapa_post = isset($_POST['etapa']) ? (int)$_POST['etapa'] : -1;
  $resposta   = $_POST['resposta'] ?? null;

  /*
   * ============================================================
   * VALIDAÇÃO SERVER-SIDE
   * ============================================================
   *
   * Antes de salvar qualquer resposta, o servidor valida:
   *
   * 1. se a etapa existe;
   * 2. se a etapa enviada corresponde à etapa atual da sessão;
   * 3. se a resposta existe entre as alternativas daquela etapa.
   */

  $etapa_atual = (int)($_SESSION['finder_etapa'] ?? 0);

  if (!etapa_valida($etapa_post)) {
    header('Location: finder.php');
    exit;
  }

  if ($etapa_post !== $etapa_atual) {
    header('Location: finder.php');
    exit;
  }

  $resposta_valida = false;
  $indice_resposta = null;

  if ($resposta !== null && $resposta !== '') {

    $indice_resposta = filter_var(
      $resposta,
      FILTER_VALIDATE_INT,
      [
        'options' => [
          'min_range' => 0
        ]
      ]
    );

    if (
      $indice_resposta !== false &&
      isset($perguntas[$etapa_post]['alternativas'][$indice_resposta])
    ) {
      $resposta_valida = true;
    }
  }

  /*
   * ============================================================
   * CONTINUAR
   * ============================================================
   */
  if ($acao === 'continuar') {

    if (!$resposta_valida) {
      header('Location: finder.php');
      exit;
    }

    salvar_resposta_etapa(
      $etapa_post,
      $indice_resposta
    );

    $proxima = $etapa_post + 1;

    /*
     * Última etapa concluída.
     */
    if ($proxima >= total_etapas()) {

      $_SESSION['finder_etapa'] =
        total_etapas() - 1;

      header('Location: resultado.php');
      exit;
    }

    /*
     * Avança para a próxima etapa.
     */
    $_SESSION['finder_etapa'] = $proxima;

    header('Location: finder.php');
    exit;
  }

  /*
   * ============================================================
   * ANTERIOR
   * ============================================================
   *
   * Se o usuário alterar a resposta da etapa atual antes de
   * voltar, a nova resposta será salva somente se for válida.
   */
  if ($acao === 'anterior' && $etapa_post > 0) {

    if ($resposta_valida) {
      salvar_resposta_etapa(
        $etapa_post,
        $indice_resposta
      );
    }

    $_SESSION['finder_etapa'] =
      $etapa_post - 1;

    header('Location: finder.php');
    exit;
  }

  /*
   * Qualquer ação desconhecida retorna ao estado seguro atual.
   */
  header('Location: finder.php');
  exit;
}

/*
 * ============================================================
 * ESTADO ATUAL
 * ============================================================
 */
$etapa = (int)($_SESSION['finder_etapa'] ?? 0);

if (!etapa_valida($etapa)) {
  $etapa = 0;
  $_SESSION['finder_etapa'] = 0;
}

$pergunta =
  $perguntas[$etapa] ?? null;

$resposta_salva =
  obter_resposta_etapa($etapa);

$pct =
  progresso_percentual($etapa);

$total =
  total_etapas();

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
    TechPath Finder — Etapa <?= $etapa + 1 ?> de <?= $total ?>
  </title>

  <link
    rel="stylesheet"
    href="style.css"
  >

</head>

<body class="tela-finder">

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
      Etapa <?= $etapa + 1 ?> de <?= $total ?>
    </div>

  </header>

  <div class="finder-progresso-bar-wrap">

    <div
      class="finder-progresso-bar"
      style="width: <?= $pct ?>%"
    ></div>

  </div>

  <main class="finder-main">

    <?php if (!$pergunta): ?>

      <div class="finder-card erro-card">

        <p>
          Etapa inválida.
          <a href="index.php">
            Voltar ao início
          </a>.
        </p>

      </div>

    <?php else: ?>

      <div
        class="finder-card"
        id="finder-card"
      >

        <div class="etapa-label">
          Etapa <?= $etapa + 1 ?> / <?= $total ?>
        </div>

        <h2 class="etapa-enunciado">
          <?= htmlspecialchars(
            $pergunta['enunciado'],
            ENT_QUOTES,
            'UTF-8'
          ) ?>
        </h2>

        <?php if (!empty($pergunta['descricao'])): ?>

          <p class="etapa-descricao">
            <?= htmlspecialchars(
              $pergunta['descricao'],
              ENT_QUOTES,
              'UTF-8'
            ) ?>
          </p>

        <?php endif; ?>

        <form
          method="POST"
          action="finder.php"
          id="finder-form"
        >

          <input
            type="hidden"
            name="etapa"
            value="<?= $etapa ?>"
          >

          <input
            type="hidden"
            name="acao"
            value="continuar"
            id="campo-acao"
          >

          <div
            class="alternativas-lista"
            role="group"
            aria-label="Alternativas"
          >

            <?php foreach (
              $pergunta['alternativas'] as $idx => $texto
            ): ?>

              <?php
                $selecionada =
                  $resposta_salva !== null &&
                  (int)$resposta_salva === (int)$idx;
              ?>

              <label
                class="alt-label <?= $selecionada ? 'selecionada' : '' ?>"
              >

                <input
                  type="radio"
                  name="resposta"
                  value="<?= (int)$idx ?>"
                  <?= $selecionada ? 'checked' : '' ?>
                >

                <span class="alt-letra">
                  <?= chr(65 + (int)$idx) ?>
                </span>

                <span class="alt-texto">

                  <?= htmlspecialchars(
                    is_array($texto)
                      ? ($texto['texto'] ?? '')
                      : $texto,
                    ENT_QUOTES,
                    'UTF-8'
                  ) ?>

                </span>

              </label>

            <?php endforeach; ?>

          </div>

          <div class="finder-nav">

            <?php if ($etapa > 0): ?>

              <button
                type="submit"
                class="btn btn-nav btn-anterior"
                onclick="document.getElementById('campo-acao').value='anterior'"
              >
                &#8592; Anterior
              </button>

            <?php else: ?>

              <a
                href="index.php"
                class="btn btn-nav btn-anterior"
              >
                &#8592; Início
              </a>

            <?php endif; ?>

            <button
              type="submit"
              class="btn btn-nav btn-continuar"
              id="btn-continuar"
              <?= ($resposta_salva === null) ? 'disabled' : '' ?>
              onclick="document.getElementById('campo-acao').value='continuar'"
            >

              <?= ($etapa + 1 >= $total)
                ? 'Concluir &#10003;'
                : 'Continuar &#8594;' ?>

            </button>

          </div>

        </form>

      </div>

    <?php endif; ?>

  </main>

  <footer class="finder-footer">

    <p class="assinatura">
      Desenvolvido por: Ademir
    </p>

  </footer>

  <script>
  (function() {

    var radios = document.querySelectorAll(
      'input[type="radio"][name="resposta"]'
    );

    var btnContinuar =
      document.getElementById('btn-continuar');

    function atualizarSelecao() {

      var algumMarcado = false;

      radios.forEach(function(radio) {

        var label =
          radio.closest('.alt-label');

        if (radio.checked) {

          if (label) {
            label.classList.add('selecionada');
          }

          algumMarcado = true;

        } else {

          if (label) {
            label.classList.remove('selecionada');
          }

        }

      });

      if (btnContinuar) {
        btnContinuar.disabled = !algumMarcado;
      }

    }

    radios.forEach(function(radio) {

      radio.addEventListener(
        'change',
        atualizarSelecao
      );

    });

    atualizarSelecao();

  })();
  </script>

</body>

</html>