<?php
// Configurações de conexão ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";  // Substitua se você tiver senha configurada
$dbname = "curso_contato";  // Nome do banco de dados

// Conectando ao banco de dados usando PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Definindo o modo de erro para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
    exit;
}

// Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];

    // Validar os dados (básico)
    if (empty($nome) || empty($email) || empty($mensagem)) {
        echo "<p>Por favor, preencha todos os campos.</p>";
    } else {
        // Preparar e executar a consulta para salvar os dados no banco
        $sql = "INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mensagem', $mensagem);

        // Executar a inserção no banco de dados
        if ($stmt->execute()) {
            echo "<p>Mensagem enviada com sucesso!</p>";
            header("Location: obrigado.html");
            exit();
            
        } else {
            echo "<p>Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.</p>";
        }
    }
} else {
    echo "<p>Por favor, envie o formulário.</p>";
}
?>
