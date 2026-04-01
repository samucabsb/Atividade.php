<?php require "conexao.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários — AdminPro</title>
    <?php include "menu.php"; ?>
</head>
<body>

<?php
$av_colors = ['indigo','green','amber','blue','red','purple'];

// Busca
$search = isset($_GET['q']) ? pg_escape_string($conn, trim($_GET['q'])) : '';
$where  = $search ? "WHERE username ILIKE '%$search%'" . (isset($_GET['email']) ? " OR email ILIKE '%$search%'" : "") : "";

$resultado = pg_query($conn, "SELECT * FROM public.usuario $where ORDER BY id ASC");
$total     = pg_num_rows($resultado);
$r_total   = pg_query($conn, "SELECT COUNT(*) as t FROM public.usuario");
$grand     = pg_fetch_assoc($r_total);
?>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <h1>Usuários</h1>
            <div class="topbar-breadcrumb">Dashboard / <span>Usuários</span></div>
        </div>
        <div class="topbar-right">
            <a href="#" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus"></i> Novo Usuário
            </a>
        </div>
    </div>

    <div class="content">

        <!-- Stats mini -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;" class="stagger">
            <div class="stat-card" style="padding:18px 20px">
                <div class="stat-header">
                    <div class="stat-icon v-blue"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-value" style="font-size:24px"><?php echo $grand['t'] ?? 0; ?></div>
                <div class="stat-label">Total de usuários</div>
            </div>
            <div class="stat-card" style="padding:18px 20px">
                <div class="stat-header">
                    <div class="stat-icon v-green"><i class="fas fa-user-check"></i></div>
                </div>
                <?php
                $r_ativos = pg_query($conn, "SELECT COUNT(*) as t FROM public.usuario WHERE ativo = true");
                $d_ativos = pg_fetch_assoc($r_ativos);
                ?>
                <div class="stat-value" style="font-size:24px"><?php echo $d_ativos['t'] ?? $grand['t'] ?? 0; ?></div>
                <div class="stat-label">Usuários ativos</div>
            </div>
            <div class="stat-card" style="padding:18px 20px">
                <div class="stat-header">
                    <div class="stat-icon v-indigo"><i class="fas fa-user-shield"></i></div>
                </div>
                <div class="stat-value" style="font-size:24px">1</div>
                <div class="stat-label">Administradores</div>
            </div>
        </div>

        <div class="card fade-in">
            <div class="card-header">
                <div>
                    <div class="card-title">Lista de Usuários</div>
                    <div class="card-subtitle"><?php echo $total; ?> usuário(s) encontrado(s)</div>
                </div>
                <div class="card-actions">
                    <form method="GET" style="display:flex;gap:8px">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" placeholder="Buscar usuário..."
                                   value="<?php echo htmlspecialchars($search); ?>"
                                   onchange="this.form.submit()">
                        </div>
                        <?php if ($search): ?>
                            <a href="usuarios.php" class="btn btn-outline btn-sm"><i class="fas fa-xmark"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Usuário</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Cadastro</th>
                            <th style="width:110px;text-align:center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($total > 0): ?>
                        <?php $i = 0; while ($u = pg_fetch_assoc($resultado)): $i++;
                            $ini   = strtoupper(substr($u['username'], 0, 1));
                            $color = $av_colors[ord($ini) % count($av_colors)];
                            $uid   = $u['id'] ?? $i;
                        ?>
                        <tr>
                            <td><span style="font-size:11.5px;color:#9ca3af;font-weight:600"><?php echo $uid; ?></span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="av av-sm av-<?php echo $color; ?>"><?php echo $ini; ?></div>
                                    <div>
                                        <div style="font-weight:600;font-size:13.5px"><?php echo htmlspecialchars($u['username']); ?></div>
                                        <?php if (!empty($u['email'])): ?>
                                            <div style="font-size:11.5px;color:#9ca3af"><?php echo htmlspecialchars($u['email']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#6b7280;font-size:13px">
                                <?php echo !empty($u['email']) ? htmlspecialchars($u['email']) : '<span style="color:#d1d5db">—</span>'; ?>
                            </td>
                            <td>
                                <span class="badge badge-purple"><i class="fas fa-shield-halved"></i>Admin</span>
                            </td>
                            <td>
                                <?php
                                $ativo = !isset($u['ativo']) || $u['ativo'] === 't' || $u['ativo'] === true || $u['ativo'] === '1';
                                ?>
                                <?php if ($ativo): ?>
                                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:7px"></i>Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:7px"></i>Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:12.5px;color:#9ca3af">
                                <?php
                                if (!empty($u['created_at'])) {
                                    echo date('d/m/Y', strtotime($u['created_at']));
                                } else {
                                    echo '<span style="color:#d1d5db">—</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:center">
                                    <button class="btn-icon-only" data-tip="Editar usuário">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-icon-only" data-tip="Redefinir senha">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <?php if ($u['username'] !== $_SESSION['username']): ?>
                                    <button class="btn-icon-only" data-tip="Excluir" style="color:#ef4444"
                                            onclick="return confirm('Excluir o usuário \'<?php echo $u['username']; ?>\'?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn-icon-only" data-tip="Usuário atual" style="opacity:.3;cursor:default">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h3>Nenhum usuário encontrado</h3>
                                    <p><?php echo $search ? 'Tente outro termo.' : 'Adicione usuários para vê-los aqui.'; ?></p>
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

</body>
</html>