<?php
session_start();

require 'functions.php';

/*
 * ============================================================
 * TOKEN DE CONFIRMAÇÃO
 * ============================================================
 *
 * Protege a ação de recomeçar contra requisições externas.
 */
if (
  !isset($_SESSION['finder_csrf']) ||
  !is_string($_SESSION['finder_csrf']) ||
  $_SESSION['finder_csrf'] === ''
) {
  $_SESSION['finder_csrf'] = bin2hex(random_bytes(32));
}

/*
 * ============================================================
 * PROCESSAR REINÍCIO
 * ============================================================
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $confirmar = $_POST['confirmar'] ?? '';
  $token_post = $_POST['csrf_token'] ?? '';
  $token_sessao = $_SESSION['finder_csrf'] ?? '';

  $token_valido =
    is_string($token_post) &&
    is_string($token_sessao) &&
    $token_post !== '' &&
    $token_sessao !== '' &&
    hash_equals($token_sessao, $token_post);

  if ($confirmar === '1' && $token_valido) {

    resetar_sessao_finder();

    /*
     * Invalida o token usado e renova a sessão.
     */
    unset($_SESSION['finder_csrf']);

    session_regenerate_id(true);

    header('Location: finder.php');
    exit;
  }

  /*
   * POST inválido: retorna para a própria confirmação,
   * sem apagar o progresso.
   */
  header('Location: recomecar.php');
  exit;
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

  <title>TechPath Finder — Recomeçar</title>

  <link rel="stylesheet" href="style.css">
</head>

<body class="tela-recomecar">

  <header class="finder-header">

    <a href="index.php" class="header-logo">

      <span class="logo-icon">
        &#9650;
      </span>

      <span class="logo-texto">
        TechPath<strong>Finder</strong>
      </span>

    </a>

  </header>

  <main class="finder-main">

    <div class="finder-card recomecar-card">

      <div class="recomecar-icone">
        &#9888;
      </div>

      <h2 class="recomecar-titulo">
        Recomeçar o Finder?
      </h2>

      <p class="recomecar-desc">
        Seu progresso atual será apagado e você voltará à etapa 1.
        Esta ação não pode ser desfeita.
      </p>

      <form
        method="POST"
        action="recomecar.php"
      >

        <input
          type="hidden"
          name="confirmar"
          value="1"
        >

        <input
          type="hidden"
          name="csrf_token"
          value="<?= htmlspecialchars($_SESSION['finder_csrf']) ?>"
        >

        <div class="recomecar-acoes">

          <button
            type="submit"
            class="btn btn-perigo"
          >
            Sim, recomeçar
          </button>

          <a
            href="index.php"
            class="btn btn-secundario"
          >
            Cancelar
          </a>

        </div>

      </form>

    </div>

  </main>

  <footer class="finder-footer">

    <p class="assinatura">
      Desenvolvido por: Ademir
    </p>

  </footer>

</body>

</html>