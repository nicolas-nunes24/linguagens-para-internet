<?php
class Amostra {
    private $id_amostra;
    private $id_setores;
    private $id_usuario;
    private $codigo_amostra;
    private $descricao;
    private $data_coleta;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // Getters
    // ==========================================
    public function getIdAmostra() { return $this->id_amostra; }
    public function getIdSetores() { return $this->id_setores; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getCodigoAmostra() { return $this->codigo_amostra; }
    public function getDescricao() { return $this->descricao; }
    public function getDataColeta() { return $this->data_coleta; }

    // ==========================================
    // Setters
    // ==========================================
    public function setIdAmostra($id_amostra) { $this->id_amostra = $id_amostra; }
    public function setIdSetores($id_setores) { $this->id_setores = $id_setores; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setCodigoAmostra($codigo_amostra) { $this->codigo_amostra = $codigo_amostra; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setDataColeta($data_coleta) { $this->data_coleta = $data_coleta; }

    // ==========================================
    // Operações CRUD
    // ==========================================
    public function save() {
        if ($this->id_amostra) {
            $sql = "UPDATE Amostras SET Id_setores = :ids, Id_usuario = :idu, codigo_amostra = :cod, descricao = :desc, data_coleta = :dt WHERE Id_amostra = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':ids'  => $this->id_setores,
                ':idu'  => $this->id_usuario,
                ':cod'  => $this->codigo_amostra,
                ':desc' => $this->descricao,
                ':dt'   => $this->data_coleta,
                ':id'   => $this->id_amostra
            ]);
        } else {
            $sql = "INSERT INTO Amostras (Id_setores, Id_usuario, codigo_amostra, descricao, data_coleta) VALUES (:ids, :idu, :cod, :desc, :dt)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':ids'  => $this->id_setores,
                ':idu'  => $this->id_usuario,
                ':cod'  => $this->codigo_amostra,
                ':desc' => $this->descricao,
                ':dt'   => $this->data_coleta
            ]);
            
            if ($ok) {
                $this->id_amostra = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Amostras WHERE Id_amostra = :id");
        $stmt->execute([':id' => $id]);
        
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id_amostra     = $dados['Id_amostra'];
            $this->id_setores     = $dados['Id_setores'];
            $this->id_usuario     = $dados['Id_usuario'];
            $this->codigo_amostra = $dados['codigo_amostra'];
            $this->descricao      = $dados['descricao'];
            $this->data_coleta    = $dados['data_coleta'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id_amostra) return false;
        $stmt = $this->pdo->prepare("DELETE FROM Amostras WHERE Id_amostra = :id");
        return $stmt->execute([':id' => $this->id_amostra]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM Amostras");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>