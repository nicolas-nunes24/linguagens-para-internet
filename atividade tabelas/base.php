<?php
class Base {
    private $id_bases; 
    private $id_planeta;
    private $nomeBase;
    private $anofundacao;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters
    // ==========================================
    public function getIdBases() {
        return $this->id_bases;
    }
    public function getIdPlaneta() {
        // FIXED: Was trying to return $this->idplaneta instead of $this->id_planeta
        return $this->id_planeta;
    }
    public function getNomeBase() {
        return $this->nomeBase;
    }
    public function getAnoFundacao() {
        return $this->anofundacao;
    }

    // ==========================================
    // Setters
    // ==========================================
    public function setIdBases($id_bases) {
        $this->id_bases = $id_bases;
    }
    public function setIdPlaneta($id_planeta) {
        $this->id_planeta = $id_planeta;
    }
    
    // FIXED: Renamed from setEmail()
    public function setNomeBase($nomeBase) {
        $this->nomeBase = $nomeBase;
    }
    
    // FIXED: Renamed from setCargo()
    public function setAnoFundacao($anofundacao) {
        $this->anofundacao = $anofundacao;
    }

    // ==========================================
    // CRUD Operations
    // ==========================================
    public function save() {
        if ($this->id_bases) {
            // FIXED: Typo "nomobase" changed to "nomeBase". Updated column names to match SQL schema.
            $sql = "UPDATE Bases SET Id_planeta = :idp, nomeBase = :nb, Anofundacao = :af WHERE Id_bases = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':idp' => $this->id_planeta,
                ':nb'  => $this->nomeBase,
                ':af'  => $this->anofundacao,
                ':id'  => $this->id_bases
            ]);
        } else {
            $sql = "INSERT INTO Bases (Id_planeta, nomeBase, Anofundacao) VALUES (:idp, :nb, :af)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':idp' => $this->id_planeta,
                ':nb'  => $this->nomeBase,
                ':af'  => $this->anofundacao
            ]);
            if ($ok) {
                $this->id_bases = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Bases WHERE Id_bases = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id_bases    = $dados['Id_bases'];
            $this->id_planeta  = $dados['Id_planeta'];
            $this->nomeBase    = $dados['nomeBase'];
            $this->anofundacao = $dados['Anofundacao'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_bases) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Bases WHERE Id_bases = :id");
        return $stmt->execute([':id' => $this->id_bases]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM Bases");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>