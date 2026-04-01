<?php require "conexao.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos — AdminPro</title>
    <?php include "menu.php"; ?>
    <style>
        .price-cell { font-weight: 700; color: #059669; font-size: 13.5px; }
        .product-name { font-weight: 600; color: #111827; }
        .product-id { font-size: 11.5px; color: #9ca3af; font-weight: 600; }
    </style>
</head>
<body>

<?php
// Busca e filtros
$search = isset($_GET['q']) ? pg_escape_string($conn, trim($_GET['q'])) : '';
$filter = isset($_GET['status']) ? $_GET['status'] : 'todos';

$where = "WHERE 1=1";
if ($search)         $where .= " AND nome ILIKE '%$search%'";
if ($filter === 'ativo')   $where .= " AND ativo = true";
if ($filter === 'inativo') $where .= " AND ativo = false";

$resultado  = pg_query($conn, "SELECT * FROM public.produto $where ORDER BY id ASC");
$total      = pg_num_rows($resultado);
$r_counts   = pg_query($conn, "SELECT COUNT(*) FILTER (WHERE ativo=true) as ativos, COUNT(*) FILTER (WHERE ativo=false) as inativos, COUNT(*) as total FROM public.produto");
$counts     = pg_fetch_assoc($r_counts);
?>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Produtos</h1>
            <div class="topbar-breadcrumb">Dashboard / <span>Produtos</span></div>
        </div>
        <div class="topbar-right">
            <a href="#" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>
    </div>

    <div class="content">

        <!-- Summary chips -->
        <div style="display:flex;gap:12px;margin-bottom:22px;" class="fade-up">
            <a href="produtos.php" class="btn <?php echo $filter==='todos' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">
                <i class="fas fa-border-all"></i> Todos <span style="opacity:.7;margin-left:2px">(<?php echo $counts['total']; ?>)</span>
            </a>
            <a href="produtos.php?status=ativo" class="btn <?php echo $filter==='ativo' ? 'btn-success' : 'btn-outline'; ?> btn-sm">
                <i class="fas fa-check-circle"></i> Ativos <span style="opacity:.7;margin-left:2px">(<?php echo $counts['ativos']; ?>)</span>
            </a>
            <a href="produtos.php?status=inativo" class="btn <?php echo $filter==='inativo' ? 'btn-danger' : 'btn-outline'; ?> btn-sm">
                <i class="fas fa-times-circle"></i> Inativos <span style="opacity:.7;margin-left:2px">(<?php echo $counts['inativos']; ?>)</span>
            </a>
        </div>

        <div class="card fade-up" style="animation-delay:.05s">
            <div class="card-header">
                <div>
                    <div class="card-title">Lista de Produtos</div>
                    <div class="card-subtitle"><?php echo $total; ?> produto(s) encontrado(s)</div>
                </div>
                <div class="card-actions">
                    <form method="GET" style="display:flex;gap:8px">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter); ?>">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" placeholder="Buscar produto..."
                                   value="<?php echo htmlspecialchars($search); ?>"
                                   onchange="this.form.submit()">
                        </div>
                        <?php if ($search): ?>
                            <a href="produtos.php" class="btn btn-outline btn-sm" data-tip="Limpar busca">
                                <i class="fas fa-xmark"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                    <button class="btn btn-outline btn-sm" data-tip="Exportar" onclick="window.print()">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="checkAll"></th>
                            <th style="width:52px">Foto</th>
                            <th>Produto</th>
                            <th>ID</th>
                            <th>Preço</th>
                            <th>Status</th>
                            <th style="width:120px;text-align:center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($total > 0): ?>
                        <?php while ($linha = pg_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><input type="checkbox" class="row-check" value="<?php echo $linha['id']; ?>"></td>
                            <td>
                                <?php if ($linha['foto']): ?>
                                    <img src="<?php echo htmlspecialchars($linha['foto']); ?>" class="product-photo" alt="<?php echo htmlspecialchars($linha['nome']); ?>">
                                <?php else: ?>
                                    <div class="product-no-photo"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="product-name"><?php echo htmlspecialchars($linha['nome']); ?></div>
                            </td>
                            <td><span class="product-id">#<?php echo $linha['id']; ?></span></td>
                            <td class="price-cell">R$ <?php echo number_format($linha['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <?php if ($linha['ativo'] == 't'): ?>
                                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px"></i>Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:7px"></i>Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:center">
                                    <button class="btn-icon-only" data-tip="Editar">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <?php if ($linha['foto']): ?>
                                    <a href="<?php echo htmlspecialchars($linha['foto']); ?>" target="_blank" class="btn-icon-only" data-tip="Ver foto">
                                        <i class="fas fa-image"></i>
                                    </a>
                                    <?php endif; ?>
                                    <button class="btn-icon-only" data-tip="Excluir" style="color:#ef4444" onclick="return confirm('Excluir este produto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h3>Nenhum produto encontrado</h3>
                                    <p><?php echo $search ? 'Tente outro termo de busca.' : 'Clique em "Novo Produto" para adicionar.'; ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked);
    });
</script>

</body>
</html>