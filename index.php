<?php
require_once 'classe-pessoa.php';
$p = new Pessoa("crudpdo", "localhost", "postgres", "postsenha772686");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CADASTRO</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Poppins:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
  <?php
  if (isset($_POST['nome']))
  //CLICOU NO BOTAO CADASTRAR OU EDITAR
  {
    //-----------------EDITAR---------------
    if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
      $id_upd = addslashes($_GET['id_up']);
      $nome = addslashes($_POST['nome']);
      $telefone = addslashes($_POST['telefone']);
      $email = addslashes($_POST['email']);
      if (!empty($nome) && !empty($telefone) && !empty($email)) {
        //EDITAR
        $p->atualizarDados($id_upd, $nome, $telefone, $email);
        header("location: index.php");
      } else {
  ?>
        <div class="aviso">
          <img src="img/aviso.png" class="img">
          <h4>Preencha todos os Campos!</h4>
        </div>
        <?php
      }
    }
    //------------Cadastrar
    else {
      $nome = addslashes($_POST['nome']);
      $telefone = addslashes($_POST['telefone']);
      $email = addslashes($_POST['email']);

      if (!empty($nome) && !empty($telefone) && !empty($email)) {
        //CADASTRAR
        if (!$p->cadastrarPessoa($nome, $telefone, $email)) {
        ?>
          <div class="nada">
          </div>
        <?php
        } else {
        ?>
          <div class="aviso">
            <img src="img/correct.png" class="img">
            <h4>Cadastrado com Sucesso!</h4>
          </div>
  <?php
        }
      }
    }
  }
  ?>
  <?php
  if (isset($_GET['id_up'])) //SE A PESSOA CLICOU EM EDITAR 
  {
    $id_update = addslashes($_GET['id_up']);
    $res = $p->buscarDadosPessoa($id_update);
  }
  ?>
  <section id="esquerda">
    <form method="POST">
      <h2 class="main-title">CADASTRO DE ALUNOS</h2>
      <div class="form-items">
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?php if (isset($res)) {
                                                          echo $res['nome'];
                                                        } ?>" required>
        <label for="telefone">Telefone</label>
        <input type="text" name="telefone" id="telefone" value="<?php if (isset($res)) {
                                                                  echo $res['telefone'];
                                                                } ?>" required>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php if (isset($res)) {
                                                            echo $res['email'];
                                                          } ?>" required>
        <input type="submit" class="cadButton" value="<?php if (isset($res)) {
                                                        echo "ATUALIZAR";
                                                      } else {
                                                        echo "CADASTRAR";
                                                      } ?>">
      </div>
    </form>
  </section>
  <section id="direita">
    <table>
      <tr id="titulo">
        <td>NOME</td>
        <td>TELEFONE</td>
        <td colspab="2">EMAIL</td>
      </tr>
      <?php
      $dados = $p->buscarDados();
      if (count($dados) > 0) // TEM PESSOAS CADASTRADAS NO BANCO
      {
        for ($i = 0; $i < count($dados); $i++) {
          echo "<tr>";
          foreach ($dados[$i] as $k => $v) {
            if ($k != "id") {
              echo "<td>" . $v . "</td>";
            }
          }
      ?>
          <td>
            <a class="funcButtons" href="index.php?id_up=<?php echo $dados[$i]['id']; ?>">Editar</a>
            <a class="funcButtons" href="index.php?id=<?php echo $dados[$i]['id']; ?>">Excluir</a>
          </td>
        <?php
          echo "</tr>";
        }
      } else //O banco de dados esta vazio 
      {
        ?>
    </table>
    <div class="aviso">
      <img src="img/aviso.png" class="img">
      <h4>Ainda não há pessoas cadastradas</h4>
    </div>
  <?php
      }
  ?>
  </section>
</body>

</html>
<?php
if (isset($_GET['id'])) {
  $id_pessoa = addslashes($_GET['id']);
  $p->excluirPessoa($id_pessoa);
  header("location: index.php");
  exit();
}
?>