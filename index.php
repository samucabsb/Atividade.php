<?php require "conexao.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — AdminPro</title>
    <?php include "menu.php"; ?>
</head>
<body>

<?php
// ── Buscar métricas do dashboard ──────────────────────────────────────────────
$r_produtos  = pg_query($conn, "SELECT COUNT(*) as total, COUNT(*) FILTER (WHERE ativo = true) as ativos FROM public.produto");
$d_produtos  = pg_fetch_assoc($r_produtos);

$r_usuarios  = pg_query($conn, "SELECT COUNT(*) as total FROM public.usuario");
$d_usuarios  = pg_fetch_assoc($r_usuarios);

$r_pedidos   = pg_query($conn, "SELECT COUNT(*) as total, COALESCE(SUM(total),0) as receita,
                COUNT(*) FILTER (WHERE status = 'pendente') as pendentes
                FROM public.pedido");
$d_pedidos   = pg_fetch_assoc($r_pedidos);

// Últimos 5 pedidos
$r_ultimos   = pg_query($conn, "SELECT * FROM public.pedido ORDER BY created_at DESC LIMIT 5");

// Últimos 5 produtos
$r_uprod     = pg_query($conn, "SELECT * FROM public.produto ORDER BY id DESC LIMIT 5");

$status_map = [
    'pendente'     => ['badge-warning', 'fa-clock', 'Pendente'],
    'em_andamento' => ['badge-info',    'fa-spinner', 'Em Andamento'],
    'concluido'    => ['badge-success', 'fa-check',   'Concluído'],
    'cancelado'    => ['badge-danger',  'fa-xmark',   'Cancelado'],
];
?>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Dashboard</h1>
            <div class="topbar-breadcrumb">Olá, <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span> — bem-vindo de volta!</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="fas fa-calendar-days" style="margin-right:6px;color:#9ca3af"></i>
                <?php echo date('d/m/Y'); ?>
            </div>
        </div>
    </div>

    <div class="content">

        <!-- Stats -->
        <div class="stats-grid stagger">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-indigo"><i class="fas fa-box-open"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> Ativo</span>
                </div>
                <div class="stat-value"><?php echo $d_produtos['total'] ?? 0; ?></div>
                <div class="stat-label">Produtos cadastrados</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-blue"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-value"><?php echo $d_usuarios['total'] ?? 0; ?></div>
                <div class="stat-label">Usuários no sistema</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-amber"><i class="fas fa-receipt"></i></div>
                    <?php if (($d_pedidos['pendentes'] ?? 0) > 0): ?>
                    <span class="stat-trend trend-down"><i class="fas fa-clock"></i> <?php echo $d_pedidos['pendentes']; ?> pend.</span>
                    <?php endif; ?>
                </div>
                <div class="stat-value"><?php echo $d_pedidos['total'] ?? 0; ?></div>
                <div class="stat-label">Pedidos realizados</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-green"><i class="fas fa-circle-dollar-to-slot"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i></span>
                </div>
                <div class="stat-value">R$ <?php echo number_format($d_pedidos['receita'] ?? 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Receita total</div>
            </div>
        </div>

        <!-- Grid de cards -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="fade-in">

            <!-- Últimos pedidos -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title"><i class="fas fa-receipt" style="color:#5b5ef4;margin-right:8px;font-size:13px"></i>Últimos Pedidos</div>
                        <div class="card-subtitle">5 pedidos mais recentes</div>
                    </div>
                    <a href="pedidos.php" class="btn btn-outline btn-sm">Ver todos</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($r_ultimos && pg_num_rows($r_ultimos) > 0): ?>
                            <?php while ($p = pg_fetch_assoc($r_ultimos)):
                                $s = $status_map[$p['status']] ?? ['badge-gray', 'fa-circle', $p['status']];
                            ?>
                            <tr>
                                <td><span style="font-size:12px;color:#9ca3af;font-weight:600">#<?php echo $p['id']; ?></span></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:9px">
                                        <div class="av av-sm av-indigo"><?php echo strtoupper(substr($p['cliente'],0,1)); ?></div>
                                        <span style="font-weight:500"><?php echo htmlspecialchars($p['cliente']); ?></span>
                                    </div>
                                </td>
                                <td><span class="badge <?php echo $s[0]; ?>"><i class="fas <?php echo $s[1]; ?>"></i><?php echo $s[2]; ?></span></td>
                                <td style="font-weight:600">R$ <?php echo number_format($p['total'],2,',','.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <h3>Nenhum pedido</h3>
                                    <p>Os pedidos aparecerão aqui</p>
                                </div>
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Últimos produtos -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title"><i class="fas fa-box-open" style="color:#10b981;margin-right:8px;font-size:13px"></i>Produtos Recentes</div>
                        <div class="card-subtitle">Últimos cadastrados</div>
                    </div>
                    <a href="produtos.php" class="btn btn-outline btn-sm">Ver todos</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($r_uprod && pg_num_rows($r_uprod) > 0): ?>
                            <?php while ($prod = pg_fetch_assoc($r_uprod)): ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:9px">
                                        <?php if ($prod['foto']): ?>
                                            <img src="<?php echo htmlspecialchars($prod['foto']); ?>" class="product-photo" alt="">
                                        <?php else: ?>
                                            <div class="product-no-photo"><i class="fas fa-image"></i></div>
                                        <?php endif; ?>
                                        <span style="font-weight:500"><?php echo htmlspecialchars($prod['nome']); ?></span>
                                    </div>
                                </td>
                                <td style="font-weight:600;color:#10b981">R$ <?php echo number_format($prod['preco'],2,',','.'); ?></td>
                                <td>
                                    <?php if ($prod['ativo'] == 't'): ?>
                                        <span class="badge badge-success"><i class="fas fa-check"></i>Ativo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fas fa-xmark"></i>Inativo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h3>Nenhum produto</h3>
                                    <p>Cadastre produtos para vê-los aqui</p>
                                </div>
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /content -->
</div><!-- /main -->

</body>
</html>