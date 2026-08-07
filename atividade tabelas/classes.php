<?php
class Planeta{
    private $id;
    private $nome;
    private $classificacao;
    private $distanciaTerraAnosLuz;

    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    #Getters
    Public function Getid(){
        return $this->id;
    }
    Public function Getplaneta(){
        return $this->planeta;
    }
    Public function Getclassificacao(){
        return $this->classificacao;
    }
    Public function GetdistanciaTerraAnosLuz(){
        return $this->distanciaTerraAnosLuz;
    }

    #Setters
    Private function Setid($id){
        $this->id = $id;
    }
    Private function Setplaneta($planeta){
        $this->planeta = $planeta;
    }
    Private function Setclassificacao($classificacao){
        $this->classificacao = $classificacao;
    }
    Private function SetdistanciaTerraAnosLuz($distanciaTerraAnosLuz){
        $this->distanciaTerraAnosLuz = $distanciaTerraAnosLuz;
    }


    Public function save() {
        if ($this->id){
            $sql = "UPDATE planetas SET planeta = :p, classificacao = :c, distanciaTerraAnosLuz = :d WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt = $return $stmt->execute([
                ':p' => $this->planeta,
                ':c' => $this->classificacao,
                ':d' => $this->distanciaTerraAnosLuz,
                ':id' => $this->id
            ])
        } else{
            $sql = "INSERT INTO planetas (planeta, classificacao, distanciaTerraAnosLuz) VALUES (:p, :c, :d)";
            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute([
                ':p' => $this->planeta,
                ':c' => $this->classificacao,
                ':d' => $this->distanciaTerraAnosLuz
            ]);
            if($ok){
                $this->id = $this->pdo->lastInsertId();
            }
            return $ok;
        }

    }

    public function load($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM planetas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if($dados = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->id = $dados["id"];
            $this->planeta = $dados["planeta"];
            $this->classificacao = $dados['classificacao'];
            $this->distanciaTerraAnosLuz = $dados['distanciaTerraAnosLuz'];
            return true;
        }
        return false;
    }

    public function delete(){
        if(!$this->id) return false;
        $stmt = $this->pdo->prepare("DELETE FROM planetas WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function all(PDO $pdo){
        $stmt = $pdo->query("SELECT * FROM planetas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>