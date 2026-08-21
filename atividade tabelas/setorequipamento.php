<?php
class SetorEquipamento {
    private $id_setores;
    private $id_equipamento;
    private $quantidade;
    private $ultima_manutencao;

    private $pdo;
    private $existe_no_banco = false; // Controle para saber se faz UPDATE ou INSERT

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters e Setters
    // ==========================================
    public function getIdSetores() { return $this->id_setores; }
    public function getIdEquipamento() { return $this->id_equipamento; }
    public function getQuantidade() { return $this->quantidade; }
    public function getUltimaManutencao() { return $this->ultima_manutencao; }

    public function setIdSetores($id_setores) { $this->id_setores = $id_setores; }
    public function setIdEquipamento($id_equipamento) { $this->id_equipamento = $id_equipamento; }
    public function setQuantidade($quantidade) { $this->quantidade = $quantidade; }
    public function setUltimaManutencao($ultima_manutencao) { $this->ultima_manutencao = $ultima_manutencao; }

    // ==========================================
    // Operações CRUD (Chave Composta)
    // ==========================================
    public function save() {
        if ($this->existe_no_banco) {
            $sql = "UPDATE setor_equipamento SET quantidade = :q, ultima_manutencao = :um WHERE Id_setores = :ids AND Id_equipamento = :ide";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':q'   => $this->quantidade,
                ':um'  => $this->ultima_manutencao,
                ':ids' => $this->id_setores,
                ':ide' => $this->id_equipamento
            ]);
        } else {
            $sql = "INSERT INTO setor_equipamento (Id_setores, Id_equipamento, quantidade, ultima_manutencao) VALUES (:ids, :ide, :q, :um)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':ids' => $this->id_setores,
                ':ide' => $this->id_equipamento,
                ':q'   => $this->quantidade,
                ':um'  => $this->ultima_manutencao
            ]);
            if ($ok) $this->existe_no_banco = true;
            return $ok;
        }
    }

    public function load($id_setores, $id_equipamento) {
        $stmt = $this->pdo->prepare("SELECT * FROM setor_equipamento WHERE Id_setores = :ids AND Id_equipamento = :ide");
        $stmt->execute([':ids' => $id_setores, ':ide' => $id_equipamento]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id_setores        = $dados['Id_setores'];
            $this->id_equipamento    = $dados['Id_equipamento'];
            $this->quantidade        = $dados['quantidade'];
            $this->ultima_manutencao = $dados['ultima_manutencao'];
            $this->existe_no_banco   = true;
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_setores || !$this->id_equipamento) return false;
        $stmt = $this->pdo->prepare("DELETE FROM setor_equipamento WHERE Id_setores = :ids AND Id_equipamento = :ide");
        $ok = $stmt->execute([':ids' => $this->id_setores, ':ide' => $this->id_equipamento]);
        if ($ok) $this->existe_no_banco = false;
        return $ok;
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM setor_equipamento");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>