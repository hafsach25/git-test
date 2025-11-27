<html>
    <head>
    <meta charset="UTF-8">
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/demandeur assets/menu_header.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="../../jquery/jquery-3.7.1.min.js"></script>
    <title>BEEX Demandeur</title>
</head>
    <div id="overlay" class="overlay"></div>
   <!-- L'overlay est un élément qui sert de fond semi-transparent apparaissant derrière le menu latéral -->
    <header class="header">
        <div class="header-left" style="display:flex; align-items:center; gap:12px;">
            <button id="menuToggle" class="menu-btn"  title="Menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="logo" style="display:flex; align-items:center; gap:12px;"> <!-- aligner les elements horizentalements, centrer verticalement avec un espace de 12 px entre eux -->
                <img src="../../assets/images/logo_beex2.png" alt="Logo BEEX" style="width:50px;">
                <h4 style="margin:0; color:white;">Espace Demandeur</h4>
            </div>
        </div>
     
    </header>

    <aside id="sidebar" class="sidebar">
        <nav class="menu">
            <a href="#dashboard" class="menu-item active">
                <span class="icon"><i class="bi bi-house-door-fill"></i></span>
                <span>Dashboard</span>
            </a>
            <a href="#mes-demandes" class="menu-item">
                <span class="icon"><i class="bi bi-file-earmark-text"></i></span>
                <span>Mes demandes</span>
            </a>
            <a href="#historique" class="menu-item">
                <span class="icon"><i class="bi bi-clock-history"></i></span>
                <span>Historique</span>
            </a>
             <a href="#mes_info" class="menu-item">
                <span class="icon"><i class="bi bi-person-circle"></i></span>
                <span>Mes informations</span>
            </a>
        </nav>

     
             <button id="logoutBtn" class="btn-logout" title="Déconnexion"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>

        </div>
    </aside>
 
    <script>
    $(function(){
        var $body = $('body');
        var $sidebar = $('#sidebar');
        var $overlay = $('#overlay');

        function openSidebar(){
            $sidebar.addClass('open');
            $overlay.addClass('show');
            $body.addClass('sidebar-open');
            $sidebar.attr('aria-hidden','false');
        }
        function closeSidebar(){
            $sidebar.removeClass('open');
            $overlay.removeClass('show');
            $body.removeClass('sidebar-open');
            $sidebar.attr('aria-hidden','true');
        }

        $('#menuToggle').on('click', function(){
            if($sidebar.hasClass('open')) closeSidebar(); else openSidebar();
        });

        $overlay.on('click', function(){ closeSidebar(); });

        $('#logoutBtn').on('click', function(){
            if(confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = '#logout';
            }
        });

       
    });
    </script>

</html>
