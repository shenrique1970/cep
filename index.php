<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca cep</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<style>

</style>

<body>
    <header>
        <h1>Buscar CEP</h1>
    </header>
    <?php
        $data = []; // inicializa para evitar erro

        if (isset($_GET['cep'])) {
            $cep = preg_replace('/[^0-9]/', '', $_GET['cep']);
            $url = "https://viacep.com.br/ws/$cep/json/";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
        }

        $bairro = $_GET['bairro'] ?? ($data['bairro'] ?? '');
        $cidade = $_GET['cidade'] ?? ($data['localidade'] ?? '');
        $estado = $_GET['estado'] ?? ($data['uf'] ?? '');
        $logradouro = $_GET['logradouro'] ?? ($data['logradouro'] ?? '');
    ?>
    <section>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="GET">
            <!-- Campo para inserção do CEP -->
            <label for="cep">CEP:</label><br>
            <input type="text" id="cep" name="cep" placeholder="Ex: 01001-000" pattern="\d{5}-?\d{3}" required><br>

            <!-- Demais campos, preenchidos automaticamente após a pesquisa -->
            <label for="endereco">Rua/Avenida:</label><br>
            <input type="text" id="logradouro" name="logradouro" readonly value="<?= htmlspecialchars($data['logradouro']) ?>"><br><br>

            <label for="bairro">Bairro:</label><br>
            <input type="text" id="bairro" name="bairro" readonly value="<?= $bairro ?>"><br><br>

            <label for="cidade">Cidade:</label><br>
            <input type="text" id="cidade" name="cidade" readonly value="<?= htmlspecialchars($data['localidade']) ?>"><br><br>

            <label for="estado">Estado:</label><br>
            <input type="text" id="estado" name="estado" readonly value="<?= htmlspecialchars($data['uf']) ?>"><br><br>

            <!-- Campos que precisam ser preenchidos manualmente -->
            <label for="numero">Número:</label><br>
            <input type="text" id="numero" name="numero" required><br><br>

            <label for="complemento">Complemento:</label><br>
            <input type="text" id="complemento" name="complemento"><br><br>

            <input type="submit" value="Enviar">
        </form>
    </section>
    
    <article>
        <?php
        if (isset($data)) {
            echo "<p>Buscando informações para o CEP: $cep</p>";
        }
        if (isset($data) && !isset($data['erro'])) {
            echo "<h2>Informações do CEP:</h2>";
            echo "<ul>";
            echo "<li>Logradouro: " . htmlspecialchars($data['logradouro']) . "</li>";
            echo "<li>Bairro: " . htmlspecialchars($data['bairro']) . "</li>";
            echo "<li>Cidade: " . htmlspecialchars($data['localidade']) . "</li>";
            echo "<li>Estado: " . htmlspecialchars($data['uf']) . "</li>";
            echo "</ul>";
        } elseif (isset($data) && isset($data['erro'])) {
            echo "<p>CEP não encontrado.</p>";
        }
        ?>
    </article>

</body>

</html>