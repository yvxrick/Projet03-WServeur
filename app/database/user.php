<?php
require "database.php";
class user {
    /**
     * Connection à la base de donnée
     * @var mysqli
     */
    private $con;
    private $email;
    public function __construct($email) {
        $this->con = Database::Connect();
        $this->email = $email;
    }
    /**
     * Retorune un booléan indiquant si l'utilisateur existe dans la DB
     * @return bool
     */
    public function exists() {
        $stmt = $this->con->prepare("SELECT NoUtilisateur FROM utilisateurs WHERE Courriel = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    /**
     * Retourne le mot de passe d'un utilisateur.
     * @return string
     */
    public function get_passwd() {
        $password = null;
        if ($this->exists()) {
            $stmt = $this->con->prepare("SELECT MotDePasse FROM utilisateurs WHERE Courriel = ?");
            $stmt->bind_param("s", $this->email);
            $stmt->execute();
            $stmt->bind_result($password);
            $stmt->fetch();
            return $password;
        }
        return null;
    }
    /**
     * Retourne l'adresse courriel d'un utilisateur
     * @return string
     */
    public function get_email() {
        return $this->email;
    }

    /**
     * Retourne le ID de l'utilisateur
     * @return int
     */
    public function get_id() {
        if ($this->exists()) {
            $email = $this->email;
            return intval($this->con->query("SELECT NoUtilisateur FROM utilisateurs WHERE Courriel = '$email'")->fetch_row()[0]);
        }
        return false;
    }
    /**
     * Retourne un booléan indiquant si l'utilisateur est authentifé (à vérifié son courriel)
     * @return bool
     */
    public function is_authenticated() {
        if ($this->exists()) {
            $status = intval($this->con->query(sprintf("SELECT statut FROM utilisateurs WHERE Courriel = '%s'", $this->email))->fetch_row()[0]);
            return $status === 9;
        }
        return false;
    }
    /**
     * Incrémente le nombre de connexions de l'utilisateur et inscrit sa date et heure de connexion dans la table connexions.
     * @return void
     */
    public function add_connection() {
        if ($this->exists()) {
            $query_utilisateurs = sprintf("UPDATE utilisateurs SET NbConnexions = NbConnexions + 1 WHERE NoUtilisateur = %d", $this->get_id());
            $query_connexions_normal = sprintf("UPDATE connexions SET Connexion = NOW() WHERE NoUtilisateur = %d", $this->get_id());
            $query_connexions_first_con = sprintf("INSERT INTO connexions(NoUtilisateur, Connexion) VALUES(%d, NOW())", $this->get_id());

            $this->con->query($query_utilisateurs); // Toujours la meme
            if ($this->first_connection()) {
                $this->con->query($query_connexions_first_con);
            } else {
                $this->con->query($query_connexions_normal);
            }
        }
    }

    /**
     * Retorune un booléan indiquant si c'est la première connexion de l'utilisateur.
     * @return bool
     */
    public function first_connection() {
        $query = sprintf("SELECT * FROM connexions WHERE NoUtilisateur = %d", $this->get_id());
        $this->con->query($query);
        return $this->con->affected_rows < 1;
    }

    

}