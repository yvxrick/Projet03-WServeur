<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/database.php";
class annonces {
    private $con;
    public function __construct() {
        $this->con = Database::Connect();
    }

    /**
     * Retorune un array contenant toutes les annonces.
     * @return array
     */
    public function get_all_cards_ads() {
        return $this->con->query("SELECT a.NoAnnonce, a.DescriptionAbregee, a.Prix, a.Photo, a.Etat, u.Nom, u.Prenom, a.Parution ,c.Description AS Categorie FROM annonces a JOIN utilisateurs u ON a.NoUtilisateur = u.NoUtilisateur JOIN categories c ON a.Categorie = c.NoCategorie;")->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Retourune le HTML pour les cartes de toutes les annonces passées en paramètre.
     * @param $array_ads Array contenant les annonces
     */
    public function load_cards_ads_html($ads) {
    $HTML = "";

    foreach ($ads as $ad) {
        if (intval($ad['Etat']) !== 1) continue; // skip les annonces non actives

        $img = !empty($ad['Photo']) 
            ? $ad['Photo'] 
            : "placeholder.jpg";

        $HTML .= "<div class='ad-card' onclick='window.location.href = `https://projet03-wserveur.alwaysdata.net/private/view_ad.php?id={$ad['NoAnnonce']}`'>";
        $HTML .= "<img src='https://projet03-wserveur.alwaysdata.net/private/ads-images/$img'>";
        $HTML .= "<p class='ad-title'>{$ad['DescriptionAbregee']}</p>";
        $HTML .= "<p class='ad-author'>{$ad['Prenom']} {$ad['Nom']}</p>";
        $HTML .= "<p class='ad-category'>{$ad['Categorie']}</p>";
        $HTML .= "<p class='ad-price'>" . number_format($ad['Prix'], 2) . " $</p>";
        $HTML .= "<p class='ad-author'>ID: {$ad['NoAnnonce']}</p>";
        $HTML .= "<p class='ad-author'>Paru le: {$ad['Parution']}</p>";
        $HTML .= "</div>";
    }

        return $HTML;
    }









    /**
     * Arrange les annonces par leur dates de parutions chronologique.
     * @param mixed $ads
     * @return void
     */
    public static function sortByDDP_DESC(&$ads) {
        usort($ads, function($a, $b) {
            $time_a = intval(strtotime($a["Parution"]));
            $time_b = intval(strtotime($b["Parution"]));
            if ($time_a == $time_b) {
                $id_a = intval($a["NoAnnonce"]);
                $id_b = intval($b["NoAnnonce"]);
                return $id_a <=> $id_b;
            }
            return $time_b <=> $time_a;
        }); 
    }
}