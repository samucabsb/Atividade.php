<?php require "conexao.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos — AdminPro</title>
    <?php include "menu.php"; ?>
</head>
<body>

<?php
$status_map = [
    'pendente'     => ['badge-warning', 'fa-clock',        'Pendente'],
    'em_andamento' => ['badge-info',    'fa-rotate',       'Em Andamento'],
    'concluido'    => ['badge-success', 'fa-check-circle', 'Concluído'],
    'cancelado'    => ['badge-danger',  'fa-ban',          'Cancelado'],
];

$av_colors = ['indigo','green','amber','blue','red','purple'];

// Filtros
$search     = isset($_GET['q'])      ? pg_escape_string($conn, trim($_GET['q'])) : '';
$filter_st  = isset($_GET['status']) ? $_GET['status'] : 'todos';

$where = "WHERE 1=1";
if ($search)               $where .= " AND cliente ILIKE '%$search%'";
if ($filter_st !== 'todos') $where .= " AND status = '" . pg_escape_string($conn, $filter_st) . "'";

$resultado = pg_query($conn, "SELECT * FROM public.pedido $where ORDER BY created_at DESC");
$total     = $resultado ? pg_num_rows($resultado) : 0;

// Counts por status
$r_stats = pg_query($conn, "
    SELECT
        COUNT(*) as total,
        COUNT(*) FILTER (WHERE status='pendente')     as pendentes,
        COUNT(*) FILTER (WHERE status='em_andamento') as andamento,
        COUNT(*) FILTER (WHERE status='concluido')    as concluidos,
        COUNT(*) FILTER (WHERE status='cancelado')    as cancelados,
        COALESCE(SUM(total) FILTER (WHERE status='concluido'), 0) as receita
    FROM public.pedido
");
$stats = $r_stats ? pg_fetch_assoc($r_stats) : [];
?>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Pedidos</h1>
            <div class="topbar-breadcrumb">Dashboard / <span>Pedidos</span></div>
        </div>
        <div class="topbar-right">
            <a href="#" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Novo Pedido
            </a>
        </div>
    </div>

    <div class="content">

        <!-- Stats -->
        <div class="stats-grid stagger" style="grid-template-columns:repeat(4,1fr)">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-indigo"><i class="fas fa-receipt"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total de pedidos</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-amber"><i class="fas fa-clock"></i></div>
                    <?php if (($stats['pendentes'] ?? 0) > 0): ?>
                        <span class="stat-trend trend-down"><i class="fas fa-exclamation"></i> Atenção</span>
                    <?php endif; ?>
                </div>
                <div class="stat-value"><?php echo $stats['pendentes'] ?? 0; ?></div>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-green"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-value"><?php echo $stats['concluidos'] ?? 0; ?></div>
                <div class="stat-label">Concluídos</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon v-green" style="color:#10b981"><i class="fas fa-dollar-sign"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i></span>
                </div>
                <div class="stat-value" style="font-size:22px">R$ <?php echo number_format($stats['receita'] ?? 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Receita confirmada</div>
            </div>
        </div>

        <!-- Filter tabs -->
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px" class="fade-up">
            <?php
            $tabs = [
                'todos'        => ['Todos',        $stats['total']      ?? 0, 'fa-border-all',   'btn-primary'],
                'pendente'     => ['Pendentes',     $stats['pendentes']  ?? 0, 'fa-clock',        'btn-outline'],
                'em_andamento' => ['Em Andamento',  $stats['andamento']  ?? 0, 'fa-rotate',       'btn-outline'],
                'concluido'    => ['Concluídos',    $stats['concluidos'] ?? 0, 'fa-check-circle', 'btn-success'],
                'cancelado'    => ['Cancelados',    $stats['cancelados'] ?? 0, 'fa-ban',          'btn-danger'],
            ];
            foreach ($tabs as $key => [$label, $count, $icon, $btnClass]):
                $active = ($filter_st === $key) ? $btnClass : 'btn-outline';
            ?>
            <a href="pedidos.php?status=<?php echo $key; ?><?php echo $search ? '&q='.urlencode($search) : ''; ?>"
               class="btn <?php echo $active; ?> btn-sm">
                <i class="fas <?php echo $icon; ?>"></i>
                <?php echo $label; ?>
                <span style="opacity:.7;margin-left:2px">(<?php echo $count; ?>)</span>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="card fade-in">
            <div class="card-header">
                <div>
                    <div class="card-title">Lista de Pedidos</div>
                    <div class="card-subtitle"><?php echo $total; ?> pedido(s) encontrado(s)</div>
                </div>
                <div class="card-actions">
                    <form method="GET" style="display:flex;gap:8px">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter_st); ?>">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" placeholder="Buscar cliente..."
                                   value="<?php echo htmlspecialchars($search); ?>"
                                   onchange="this.form.submit()">
                        </div>
                        <?php if ($search): ?>
                            <a href="pedidos.php?status=<?php echo $filter_st; ?>" class="btn btn-outline btn-sm"><i class="fas fa-xmark"></i></a>
                        <?php endif; ?>
                    </form>
                    <select onchange="location='pedidos.php?status='+this.value+'<?php echo $search ? '&q='.urlencode($search) : ''; ?>'">
                        <option value="todos"        <?php echo $filter_st==='todos'        ? 'selected' : ''; ?>>Todos</option>
                        <option value="pendente"     <?php echo $filter_st==='pendente'     ? 'selected' : ''; ?>>Pendente</option>
                        <option value="em_andamento" <?php echo $filter_st==='em_andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="concluido"    <?php echo $filter_st==='concluido'    ? 'selected' : ''; ?>>Concluído</option>
                        <option value="cancelado"    <?php echo $filter_st==='cancelado'    ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:40px"><input type="checkbox" id="checkAll"></th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th style="width:120px;text-align:center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($total > 0 && $resultado): ?>
                        <?php while ($p = pg_fetch_assoc($resultado)):
                            $s     = $status_map[$p['status']] ?? ['badge-gray','fa-circle',$p['status']];
                            $ini   = strtoupper(substr($p['cliente'], 0, 1));
                            $color = $av_colors[ord($ini) % count($av_colors)];
                            $data  = !empty($p['created_at']) ? date('d/m/Y H:i', strtotime($p['created_at'])) : '—';
                        ?>
                        <tr>
                            <td><input type="checkbox" class="row-check" value="<?php echo $p['id']; ?>"></td>
                            <td>
                                <span style="font-weight:700;font-size:13.5px;color:#5b5ef4">#<?php echo str_pad($p['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="av av-sm av-<?php echo $color; ?>"><?php echo $ini; ?></div>
                                    <span style="font-weight:600"><?php echo htmlspecialchars($p['cliente']); ?></span>
                                </div>
                            </td>
                            <td style="font-size:12.5px;color:#9ca3af"><?php echo $data; ?></td>
                            <td>
                                <span class="badge <?php echo $s[0]; ?>">
                                    <i class="fas <?php echo $s[1]; ?>"></i>
                                    <?php echo $s[2]; ?>
                                </span>
                            </td>
                            <td style="font-weight:700;color:#059669">
                                R$ <?php echo number_format($p['total'], 2, ',', '.'); ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:center">
                                    <button class="btn-icon-only" data-tip="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon-only" data-tip="Editar pedido">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-icon-only" data-tip="Excluir" style="color:#ef4444"
                                            onclick="return confirm('Excluir o pedido #<?php echo $p['id']; ?>?')">
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
                                    <i class="fas fa-receipt"></i>
                                    <h3>Nenhum pedido encontrado</h3>
                                    <p><?php echo $search ? 'Tente outro cliente.' : ($filter_st !== 'todos' ? 'Nenhum pedido com esse status.' : 'Clique em "Novo Pedido" para começar.'); ?></p>
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