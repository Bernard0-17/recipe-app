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
    echo "10 - Adicionar ingrediente\n";
    echo "11 - Listar ingredientes\n";
    echo "12 - Associar ingrediente a receita\n";
    echo "13 - Atualizar ingrediente de uma receita\n";
    echo "14 - Remover ingrediente de uma receita\n";
    echo "15 - Mostrar detalhes de uma receita\n";
    echo "16 - Listar receitas por ingrediente\n";
    echo "17 - Pesquisar receitas por parte do título\n";
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


//função para criar uma categoria
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

//função para consulta de ingrediente
function executarConsulta($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    if (!$result) echo "Erro: " . mysqli_error($conn) . "\n";
    return $result;
}




//função para criar ingrediente
function criarIngrediente($conn) {
    $nome = readline("Nome do ingrediente: ");
    $sql = "INSERT INTO Ingrediente (nome) VALUES ('$nome')";
    executarConsulta($conn, $sql);
    echo "Ingrediente criado com sucesso.\n";
}


//função para listar ingrediente
function listarIngredientes($conn) {
    $sql = "SELECT * FROM Ingrediente";
    $result = executarConsulta($conn, $sql);
    echo "\n--- Ingredientes ---\n";
    while ($linha = mysqli_fetch_assoc($result)) {
        echo "ID: {$linha['id']} - Nome: {$linha['nome']}\n";
    }
}


//função para associar ingrediente a uma receita
function associarIngredienteReceita($conn) {
    $id_receita = readline("ID da receita: ");
    $id_ingrediente = readline("ID do ingrediente: ");
    $quantidade = readline("Quantidade: ");
    $unidade = readline("Unidade de medida: ");
    $sql = "INSERT INTO Receita_Ingrediente (id_receita, id_ingrediente, quantidade, unidade_medida) VALUES ('$id_receita', '$id_ingrediente', '$quantidade', '$unidade')";
    executarConsulta($conn, $sql);
    echo "Ingrediente associado com sucesso.\n";
}


//função para atualizar ingrediente de uma receita
function atualizarIngredienteReceita($conn) {
    $id_receita = readline("ID da receita: ");
    $id_ingrediente = readline("ID do ingrediente: ");
    $quantidade = readline("Nova quantidade: ");
    $unidade = readline("Nova unidade: ");
    $sql = "UPDATE Receita_Ingrediente SET quantidade='$quantidade', unidade_medida='$unidade' WHERE id_receita='$id_receita' AND id_ingrediente='$id_ingrediente'";
    executarConsulta($conn, $sql);
    echo "Ingrediente atualizado com sucesso.\n";
}


//função para remover ingrediente de uma receita
function removerIngredienteReceita($conn) {
    $id_receita = readline("ID da receita: ");
    $id_ingrediente = readline("ID do ingrediente: ");
    $sql = "DELETE FROM Receita_Ingrediente WHERE id_receita='$id_receita' AND id_ingrediente='$id_ingrediente'";
    executarConsulta($conn, $sql);
    echo "Ingrediente removido da receita com sucesso.\n";
}


//função para ver detalhes de uma receita
function verDetalhesReceita($conn) {
    $entrada = readline("ID ou nome da receita: ");
    $sql = "SELECT * FROM Receita WHERE id='$entrada' OR nome='$entrada'";
    $result = executarConsulta($conn, $sql);
    $receita = mysqli_fetch_assoc($result);
    if (!$receita) return;

    echo "\n--- Detalhes da Receita ---\n";
    echo "Nome: {$receita['nome']}\n";
    echo "Descrição: {$receita['descricao']}\n";

    $id = $receita['id'];
    $sql_ing = "SELECT Ingrediente.nome, Receita_Ingrediente.quantidade, Receita_Ingrediente.unidade_medida FROM Receita_Ingrediente JOIN Ingrediente ON Receita_Ingrediente.id_ingrediente = Ingrediente.id WHERE Receita_Ingrediente.id_receita = '$id'";
    $res_ing = executarConsulta($conn, $sql_ing);

    echo "\nIngredientes:\n";
    while ($linha = mysqli_fetch_assoc($res_ing)) {
        echo "- {$linha['nome']}: {$linha['quantidade']} {$linha['unidade_medida']}\n";
    }
}


//função listar por categoria
function listarPorCategoria($conn) {
    $entrada = readline("ID ou nome da categoria: ");
    $sql = "SELECT Receita.id, Receita.nome FROM Receita JOIN Receita_Categoria ON Receita.id = Receita_Categoria.id_receita JOIN Categoria ON Categoria.id = Receita_Categoria.id_categoria WHERE Categoria.id='$entrada' OR Categoria.nome='$entrada'";
    $res = executarConsulta($conn, $sql);
    while ($linha = mysqli_fetch_assoc($res)) echo "- {$linha['nome']} (ID: {$linha['id']})\n";
}



//função listar por ingrediente
function listarPorIngrediente($conn) {
    $nome = readline("Nome do ingrediente: ");
    $sql = "SELECT Receita.id, Receita.nome FROM Receita JOIN Receita_Ingrediente ON Receita.id = Receita_Ingrediente.id_receita JOIN Ingrediente ON Ingrediente.id = Receita_Ingrediente.id_ingrediente WHERE Ingrediente.nome='$nome'";
    $res = executarConsulta($conn, $sql);
    while ($linha = mysqli_fetch_assoc($res)) echo "- {$linha['nome']} (ID: {$linha['id']})\n";
}


//função pesquisar por titulo
function pesquisarPorTitulo($conn) {
    $termo = readline("Parte do título: ");
    $sql = "SELECT id, nome FROM Receita WHERE LOWER(nome) LIKE LOWER('%$termo%')";
    $res = executarConsulta($conn, $sql);
    while ($linha = mysqli_fetch_assoc($res)) echo "- {$linha['nome']} (ID: {$linha['id']})\n";
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
        case '10':
            criarIngrediente($conn);
            break;
        case '11':
            listarIngredientes($conn);
            break;
        case '12':
            associarIngredienteReceita($conn);
            break;
        case '13':
            atualizarIngredienteReceita($conn);
            break;
        case '14':
            removerIngredienteReceita($conn);
            break;
        case '15':
            verDetalhesReceita($conn);
            break;
        case '16':
            listarPorIngrediente($conn);
            break;
        case '17':
            pesquisarPorTitulo($conn);
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