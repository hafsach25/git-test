 <?php  include "dashboard_stat_cards.php";
     $id_demandeur = $_SESSION['user_id'];
     $dashboardStatCards = new DashboardStatCards(); 
     $stats=$dashboardStatCards->getStatCards($id_demandeur);
    $_SESSION['stats'] = $stats;?>