<?php

$conn = new mysqli('localhost', 'root', '', 'gestao_alunos');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $id);  
    $stmt->execute();
    $resultado = $stmt->get_result();
    $aluno = $resultado->fetch_assoc();

    if (!$aluno) {
        echo "Aluno não encontrado!";
        exit();
    }

    $stmt->close();
} else {

    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];

    if (empty($nome) || empty($email) || empty($contacto)) {
        echo "Todos os campos são obrigatórios.";
    } else {

        $stmt = $conn->prepare("UPDATE alunos SET nome = ?, email = ?, contacto = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $email, $contacto, $id);  

        if ($stmt->execute()) {

            header('Location: index.php');
            exit();
        } else {
            echo "Erro ao atualizar o aluno: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR"> 
<head>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <style>
        form {
            width: 300px;
            margin: 0 auto;
        }
        label, input {
            display: block;
            margin: 10px 0;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            margin-top: 20px;
            display: block;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Editar Aluno</h1>

    <!-- Formulário para editar o aluno -->
    <form method="post" action="editar.php?id=<?php echo $id; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>

        <label for="contacto">Contacto:</label>
        <input type="text" name="contacto" id="contacto" value="<?php echo htmlspecialchars($aluno['contacto']); ?>" required>

        <input type="submit" value="Atualizar">
    </form>

    <!-- Link para voltar à página principal -->
    <a class="back-link" href="index.php">Voltar à lista de alunos</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php

$conn->close();
?>