<?php

// CRUD = Create Read Update Delete

// Criar uma conexão à base dados
$conn = mysqli_connect('127.0.0.1', 'root', '', 'projecto_final');

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
    echo "5 - Criar nova categoria\n";
    echo "6 - Listar todas as categorias\n";
    echo "7 - Associar receita a uma categoria\n";
    echo "8 - Desassociar receita a uma categoria\n";
    echo "9 - Listar receitas por categoria\n";
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


//função para criar categoria
function criarCategoria($conn) {
    $nome = readline("Nome da nova categoria: ");
    $sql = "INSERT INTO Categoria (nome) VALUES ('$nome')";
    if (mysqli_query($conn, $sql)) {
        echo "Categoria criada com sucesso!\n";
    } else {
        echo "Erro ao criar categoria: " . mysqli_error($conn) . "\n";
    }
}


//função para listar as categorias
function listarCategorias($conn) {
    $sql = "SELECT * FROM Categoria";
    $result = mysqli_query($conn, $sql);

    echo "\n--- Categorias ---\n";
    while ($linha = mysqli_fetch_assoc($result)) {
        echo "ID: " . $linha['id'] . " - Nome: " . $linha['nome'] . "\n";
    }
}


//função para associar as receitas a uma categoria
function associarReceitaCategoria($conn) {
    $id_receita = readline("ID da receita: ");
    $id_categoria = readline("ID da categoria: ");

    $sql = "INSERT INTO Receita_Categoria (id_receita, id_categoria) VALUES ('$id_receita', '$id_categoria')";
    if (mysqli_query($conn, $sql)) {
        echo "Associação realizada com sucesso!\n";
    } else {
        echo "Erro ao associar: " . mysqli_error($conn) . "\n";
    }
}


//função para desassociar uma receita a uma categoria
function desassociarReceitaCategoria($conn) {
    $id_receita = readline("ID da receita: ");
    $id_categoria = readline("ID da categoria: ");

    $sql = "DELETE FROM Receita_Categoria WHERE id_receita = '$id_receita' AND id_categoria = '$id_categoria'";
    if (mysqli_query($conn, $sql)) {
        echo "Desassociação realizada com sucesso!\n";
    } else {
        echo "Erro ao desassociar: " . mysqli_error($conn) . "\n";
    }
}


//função para listar as receitas por categoria
function listarReceitasPorCategoria($conn) {
    $id_categoria = readline("ID da categoria: ");

    $sql = "
        SELECT Receita.id, Receita.nome, Receita.descricao
        FROM Receita
        JOIN Receita_Categoria ON Receita.id = Receita_Categoria.id_receita
        WHERE Receita_Categoria.id_categoria = '$id_categoria'
    ";

    $result = mysqli_query($conn, $sql);

    echo "\n--- Receitas na Categoria ---\n";
    while ($linha = mysqli_fetch_assoc($result)) {
        echo "ID: " . $linha['id'] . "\n";
        echo "Nome: " . $linha['nome'] . "\n";
        echo "Descrição: " . $linha['descricao'] . "\n";
        echo "------------------------\n";
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
        case '5':
            criarCategoria($conn);
            break;
        case '6':
            listarCategorias($conn);
            break;
        case '7':
            associarReceitaCategoria($conn);
            break;
        case '8':
            desassociarReceitaCategoria($conn);
            break;
        case '9':
            listarReceitasPorCategoria($conn);
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