<?php
class Equipamento {
    private $id_equipamento;
    private $nome;
    private $tipo;
    private $fabricante;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters
    // ==========================================
    public function getIdEquipamento() {
        return $this->id_equipamento;
    }
    public function getNome() {
        return $this->nome;
    }
    public function getTipo() {
        return $this->tipo;
    }
    public function getFabricante() {
        return $this->fabricante;
    }

    // ==========================================
    // Setters
    // ==========================================
    public function setIdEquipamento($id_equipamento) {
        $this->id_equipamento = $id_equipamento;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
    public function setFabricante($fabricante) {
        $this->fabricante = $fabricante;
    }

    // ==========================================
    // Operações CRUD
    // ==========================================
    public function save() {
        if ($this->id_equipamento) {
            $sql = "UPDATE Equipamentos SET nome = :n, tipo = :t, fabricante = :f WHERE Id_equipamento = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':n'  => $this->nome,
                ':t'  => $this->tipo,
                ':f'  => $this->fabricante,
                ':id' => $this->id_equipamento
            ]);
        } else {
            $sql = "INSERT INTO Equipamentos (nome, tipo, fabricante) VALUES (:n, :t, :f)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':n' => $this->nome,
                ':t' => $this->tipo,
                ':f' => $this->fabricante
            ]);
            
            if ($ok) {
                $this->id_equipamento = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Equipamentos WHERE Id_equipamento = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id_equipamento = $dados['Id_equipamento'];
            $this->nome           = $dados['nome'];
            $this->tipo           = $dados['tipo'];
            $this->fabricante     = $dados['fabricante'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_equipamento) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Equipamentos WHERE Id_equipamento = :id");
        return $stmt->execute([':id' => $this->id_equipamento]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM Equipamentos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>