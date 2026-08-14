<?php
class Planeta {
    private $id;
    private $nomeplaneta;
    private $classificacao;
    private $distanciaTerraAnosLuz;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Getters
    public function getId() {
        return $this->id;
    }
    public function getNomePlaneta() {
        return $this->nomeplaneta;
    }
    public function getClassificacao() {
        return $this->classificacao;
    }
    public function getDistanciaTerraAnosLuz() {
        return $this->distanciaTerraAnosLuz;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setNomePlaneta($nomeplaneta) {
        $this->nomeplaneta = $nomeplaneta; // Fixed from $this->planeta
    }
    public function setClassificacao($classificacao) {
        $this->classificacao = $classificacao;
    }
    public function setDistanciaTerraAnosLuz($distanciaTerraAnosLuz) {
        $this->distanciaTerraAnosLuz = $distanciaTerraAnosLuz;
    }

    public function save() {
        if ($this->id) {
            $sql = "UPDATE planeta SET nomeplaneta = :np, classificacao = :c, distanciaTerraAnosLuz = :d WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':np' => $this->nomeplaneta,
                ':c'  => $this->classificacao,
                ':d'  => $this->distanciaTerraAnosLuz,
                ':id' => $this->id
            ]);
        } else {
            $sql = "INSERT INTO planeta (nomeplaneta, classificacao, distanciaTerraAnosLuz) VALUES (:np, :c, :d)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':np' => $this->nomeplaneta, // Fixed from ':p' to ':np'
                ':c'  => $this->classificacao,
                ':d'  => $this->distanciaTerraAnosLuz
            ]);
            if ($ok) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM planeta WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = $dados['id'];
            $this->nomeplaneta = $dados['nomeplaneta']; // Fixed from $dados['planeta']
            $this->classificacao = $dados['classificacao'];
            $this->distanciaTerraAnosLuz = $dados['distanciaTerraAnosLuz'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id) return false;
        $stmt = $this->pdo->prepare("DELETE FROM planeta WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM planeta");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>