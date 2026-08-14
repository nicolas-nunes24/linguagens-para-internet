<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $cargo;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Getters
    public function getId() {
        return $this->id;
    }
    public function getNome() {
        return $this->nome;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getCargo() {
        return $this->cargo;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function save() {
        if ($this->id) {
            $sql = "UPDATE usuario SET nome = :n, email = :e, cargo = :c WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':n' => $this->nome,
                ':e' => $this->email,
                ':c' => $this->cargo,
                ':id' => $this->id
            ]);
        } else {
            $sql = "INSERT INTO usuario (nome, email, cargo) VALUES (:n, :e, :c)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':n' => $this->nome,
                ':e' => $this->email,
                ':c' => $this->cargo
            ]);
            if ($ok) {
                $this->id = $this->pdo->lastInsertId();
            }
            return $ok;
        }
    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id = $dados['id'];
            $this->nome = $dados['nome'];
            $this->email = $dados['email'];
            $this->cargo = $dados['cargo'];
            return true;
        }
        return false;
    }

    public function delete() {
        if (!$this->id) return false;
        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function all(PDO $pdo) {
        $stmt = $pdo->query("SELECT * FROM usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>