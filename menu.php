<?php
$current = basename($_SERVER['PHP_SELF']);
function is_active($page, $current) {
    return ($page === $current) ? 'active' : '';
}
$username = $_SESSION["username"] ?? 'Admin';
$initial  = strtoupper(substr($username, 0, 1));
$colors   = ['indigo','green','amber','blue','red','purple'];
$color    = $colors[ord($initial) % count($colors)];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css">

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-layer-group"></i></div>
        <div>
            <div class="logo-text">Admin<em>Pro</em></div>
            <div class="logo-subtitle">SISTEMA DE GESTÃO</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>

        <a href="index.php" class="nav-item <?php echo is_active('index.php', $current); ?>">
            <i class="fas fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-label">Cadastros</div>

        <a href="usuarios.php" class="nav-item <?php echo is_active('usuarios.php', $current); ?>">
            <i class="fas fa-users"></i>
            <span>Usuários</span>
        </a>

        <a href="produtos.php" class="nav-item <?php echo is_active('produtos.php', $current); ?>">
            <i class="fas fa-box-open"></i>
            <span>Produtos</span>
        </a>

        <div class="nav-section-label">Vendas</div>

        <a href="pedidos.php" class="nav-item <?php echo is_active('pedidos.php', $current); ?>">
            <i class="fas fa-receipt"></i>
            <span>Pedidos</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="av av-md av-<?php echo $color; ?>"><?php echo $initial; ?></div>
        <div class="sidebar-user">
            <div class="sidebar-username"><?php echo htmlspecialchars($username); ?></div>
            <div class="sidebar-role">Administrador</div>
        </div>
        <a href="login.php" class="logout-btn" data-tip="Sair">
            <i class="fas fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</aside>