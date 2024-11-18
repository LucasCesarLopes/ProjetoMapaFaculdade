<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'curso_contato';  // Altere para o nome correto do seu banco de dados
$username = 'root';          // Usuário do banco de dados
$password = '';              // Senha (deixe em branco para XAMPP padrão)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Consultar os dados dos formulários
$sql = "SELECT * FROM contatos ORDER BY id DESC"; // Altere 'contatos' para o nome da tabela que armazena os formulários
$stmt = $pdo->query($sql);
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica de exclusão de um contato (opcional)
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $delete_sql = "DELETE FROM contatos WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $delete_stmt->execute();
    header("Location: admin.php"); // Redireciona após a exclusão
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Painel de Admin para visualização de formulários.">
    <title>Admin - Resultados de Contato</title>
    <link href="admin.css" rel="stylesheet">
</head>
<body>
    <!-- Cabeçalho -->
    <header class="menu">
        <nav>
            <a href="index.html" aria-label="Página inicial">
                <img src="logo.svg" alt="Logo da UniMetrocamp" class="logo">
            </a>
            <a href="index.html" class="botao">Sair</a>
        </nav>
    </header>

    <!-- Conteúdo Principal -->
    <div class="conteudo-admin">
        <h2>Resultados dos Formulários de Contato</h2>
        
        <?php if (count($contatos) > 0): ?>
            <table class="tabela-contatos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Mensagem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contatos as $contato): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contato['nome']); ?></td>
                            <td><?php echo htmlspecialchars($contato['email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($contato['mensagem'])); ?></td>
                            <td>
                                <a href="admin.php?excluir=<?php echo $contato['id']; ?>" class="botao-excluir" onclick="return confirm('Tem certeza de que deseja excluir este registro?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Não há registros para exibir.</p>
        <?php endif; ?>
    </div>
</body>
</html>
