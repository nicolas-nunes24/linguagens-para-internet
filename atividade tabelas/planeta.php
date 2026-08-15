<?php
class Planeta {
    private $id_planeta;
    private $nomePlaneta;
    private $classificacao;
    private $distanciaAnosLuzTerra; // Aligned with SQL schema

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters
    // ==========================================
    public function getIdPlaneta() {
        return $this->id_planeta;
    }
    public function getNomePlaneta() {
        return $this->nomePlaneta;
    }
    public function getClassificacao() {
        return $this->classificacao;
    }
    public function getDistanciaAnosLuzTerra() {
        return $this->distanciaAnosLuzTerra;
    }

    // ==========================================
    // Setters
    // ==========================================
    public function setIdPlaneta($id_planeta) {
        // FIXED: Was $this->id = $id_planeta
        $this->id_planeta = $id_planeta; 
    }
    public function setNomePlaneta($nomePlaneta) {
        $this->nomePlaneta = $nomePlaneta;
    }
    public function setClassificacao($classificacao) {
        $this->classificacao = $classificacao;
    }
    public function setDistanciaAnosLuzTerra($distanciaAnosLuzTerra) {
        $this->distanciaAnosLuzTerra = $distanciaAnosLuzTerra;
    }

    // ==========================================
    // CRUD Operations
    // ==========================================
    public function save() {
        // FIXED: Was checking $this->id instead of $this->id_planeta
        if ($this->id_planeta) { 
            $sql = "UPDATE Planeta SET nomePlaneta = :np, Classificacao = :c, DistanciaAnosLuzTerra = :d WHERE Id_planeta = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':np' => $this->nomePlaneta,
                ':c'  => $this->classificacao,
                ':d'  => $this->distanciaAnosLuzTerra,
                ':id' => $this->id_planeta
            ]);
        } else {
            $sql = "INSERT INTO Planeta (nomePlaneta, Classificacao, DistanciaAnosLuzTerra) VALUES (:np, :c, :d)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':np' => $this->nomePlaneta,
                ':c'  => $this->classificacao,
                ':d'  => $this->distanciaAnosLuzTerra
            ]);
            
            if ($ok) {
                // FIXED: Was assigning to $this->id
                $this->id_planeta = $this->pdo->lastInsertId(); 
            }
            return $ok;
        }
    }

    public function load($id_planeta) {
        $stmt = $this->pdo->prepare("SELECT * FROM Planeta WHERE Id_planeta = :id");
        $stmt->execute([':id' => $id_planeta]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // FIXED: Updated keys to match SQL schema capitalization to prevent undefined index errors
            $this->id_planeta            = $dados['Id_planeta'];
            $this->nomePlaneta           = $dados['nomePlaneta']; 
            $this->classificacao         = $dados['Classificacao'];
            $this->distanciaAnosLuzTerra = $dados['DistanciaAnosLuzTerra'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_planeta) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Planeta WHERE Id_planeta = :id");
        return $stmt->execute([':id' => $this->id_planeta]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM Planeta");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>