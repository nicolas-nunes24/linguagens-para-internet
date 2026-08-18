<?php
class Setor {
    private $id_setores; 
    private $id_bases;
    private $nomeSetor;
    private $nivel_seguranca;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters
    // ==========================================
    public function getIdSetores() {
        return $this->id_setores;
    }
    public function getIdBases() {
        return $this->id_bases;
    }
    public function getNomeSetor() {
        return $this->nomeSetor;
    }
    public function getNivelSeguranca() {
        return $this->nivel_seguranca;
    }

    // ==========================================
    // Setters
    // ==========================================
    public function setIdSetores($id_setores) {
        $this->id_setores = $id_setores;
    }
    public function setIdBases($id_bases) {
        $this->id_bases = $id_bases;
    }
    public function setNomeSetor($nomeSetor) {
        $this->nomeSetor = $nomeSetor;
    }
    public function setNivelSeguranca($nivel_seguranca) {
        $this->nivel_seguranca = $nivel_seguranca;
    }

    // ==========================================
    // Operações CRUD
    // ==========================================
    public function save() {
        if ($this->id_setores) {
            // Atualiza um registro existente
            $sql = "UPDATE Setores SET Id_bases = :idb, nomeSetor = :ns, nivel_seguranca = :niv WHERE Id_setores = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':idb' => $this->id_bases,
                ':ns'  => $this->nomeSetor,
                ':niv' => $this->nivel_seguranca,
                ':id'  => $this->id_setores
            ]);
        } else {
            // Insere um novo registro
            $sql = "INSERT INTO Setores (Id_bases, nomeSetor, nivel_seguranca) VALUES (:idb, :ns, :niv)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':idb' => $this->id_bases,
                ':ns'  => $this->nomeSetor,
                ':niv' => $this->nivel_seguranca
            ]);
            
            if ($ok) {
                $this->id_setores = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Setores WHERE Id_setores = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // As chaves do array devem corresponder exatamente aos nomes das colunas no banco
            $this->id_setores      = $dados['Id_setores'];
            $this->id_bases        = $dados['Id_bases'];
            $this->nomeSetor       = $dados['nomeSetor'];
            $this->nivel_seguranca = $dados['nivel_seguranca'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_setores) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Setores WHERE Id_setores = :id");
        return $stmt->execute([':id' => $this->id_setores]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM Setores");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>