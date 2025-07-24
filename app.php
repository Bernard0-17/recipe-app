<?php

// CRUD = Create Read Update Delete

// Criar uma conexão à base dados
$conn = mysqli_connect('127.0.0.1', 'root', '', '5425_bernardo');

//verificar se a conexão foi concluida
if ($conn) {
    echo "Conexão com a base de dados concluída!\n";
} else {
    echo "Erro na conexão com a base de dados\n";
}

// função para mostrar Menu
function menu() {
    echo "\n===== Menu de Receitas =====\n";
    echo "1 - Criar nova receita\n";
    echo "2 - Listar todas as receitas\n";
    echo "3 - Atualizar receita\n";
    echo "4 - Apagar receita\n";
    echo "0 - Sair\n";
    $option = readline("Escolha uma opção: ");
    return $option;
}

//função para criar uma receita
function criarReceita($conn) {
    $nome = readline("Nome da receita: ");
    $descricao = readline("Descrição: ");
    $tempo = readline("Tempo estimado (minutos): ");
    $doses = readline("Número de doses: ");

    $sql = "INSERT INTO Receita (nome, descricao, tempo_estimado, numero_doses) VALUES ('$nome', '$descricao', '$tempo', '$doses')";
    if (mysqli_query($conn, $sql)) {
        echo "Receita criada com sucesso!\n";
    } else {
        echo "Erro ao criar receita: " . mysqli_error($conn) . "\n";
    }
}


//função para listar/mostrar as receitas
function listarReceitas($conn) {
    $sql = "SELECT * FROM Receita";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Erro na consulta: " . mysqli_error($conn) . "\n";
        return;
    }

    echo "\n--- Lista de Receitas ---\n";
    while ($linha = mysqli_fetch_assoc($result)) {
        echo "ID: " . $linha['id'] . "\n";
        echo "Nome: " . $linha['nome'] . "\n";
        echo "Descrição: " . $linha['descricao'] . "\n";
        echo "Tempo estimado: " . $linha['tempo_estimado'] . " minutos\n";
        echo "Número de doses: " . $linha['numero_doses'] . "\n";
        echo "------------------------\n";
    }
}


//função para atualizar uma receita
function atualizarReceita($conn) {
    $id = readline("ID da receita a atualizar: ");
    
    // Buscar receita existente
    $sql_check = "SELECT * FROM Receita WHERE id = '$id'";
    $result_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result_check) == 0) {
        echo "Receita não encontrada.\n";
        return;
    }

    $nome = readline("Novo nome da receita: ");
    $descricao = readline("Nova descrição: ");
    $tempo = readline("Novo tempo estimado (minutos): ");
    $doses = readline("Novo número de doses: ");

    $sql = "UPDATE Receita SET nome='$nome', descricao='$descricao', tempo_estimado='$tempo', numero_doses='$doses' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Receita atualizada com sucesso!\n";
    } else {
        echo "Erro ao atualizar receita: " . mysqli_error($conn) . "\n";
    }
}


//função para apagar receita
function apagarReceita($conn) {
    $id = readline("ID da receita a apagar: ");

    // Verificar existência
    $sql_check = "SELECT * FROM Receita WHERE id = '$id'";
    $result_check = mysqli_query($conn, $sql_check);
    if (mysqli_num_rows($result_check) == 0) {
        echo "Receita não encontrada.\n";
        return;
    }

    $sql = "DELETE FROM Receita WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Receita apagada com sucesso!\n";
    } else {
        echo "Erro ao apagar receita: " . mysqli_error($conn) . "\n";
    }
}


do {
    $menu = menu();

    switch ($menu) {
        case '1':
            criarReceita($conn);
            break;
        case '2':
            listarReceitas($conn);
            break;
        case '3':
            atualizarReceita($conn);
            break;
        case '4':
            apagarReceita($conn);
            break;
        case '0':
            echo "Sair.\n";
            break;
        default:
            echo "Opção inválida. Tente novamente.\n";
            break;
    }

} while ($menu !== '0');




// fechar conexão.
mysqli_close($conn);