

CREATE TABLE Planeta(
Id_planeta INT PRIMARY KEY,
nomePlaneta VARCHAR(20),
Classificacao VARCHAR(50),
DistanciaAnosLuzTerra DOUBLE    
);

CREATE TABLE Usuario(
Id_usuario INT PRIMARY KEY,
Nome VARCHAR(20) NOT NULL,
Email VARCHAR(50) UNIQUE NOT NULL,
Cargo VARCHAR(20) NOT NULL    
);

CREATE TABLE Bases(
Id_bases INT PRIMARY KEY,
Id_planeta INT ,
nomeBase VARCHAR(20),
Anofundacao INT,
FOREIGN KEY (Id_planeta) REFERENCES Planeta(Id_planeta)   
);

CREATE TABLE Setores(
Id_setores INT PRIMARY KEY,
Id_bases INT,
nomeSetor VARCHAR (50) NOT NULL,
nivel_segurança INT NOT NULL,    
FOREIGN KEY (Id_bases) REFERENCES Bases(Id_bases) 
);

CREATE TABLE Equipamentos (
    Id_equipamento INT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50), -- Ex: Espectrômetro, Traje EVA, Rover
    fabricante VARCHAR(100)
);

-- ==========================================
-- 5. TABELA AMOSTRAS (Relação 1..n com Setores e Usuários)

CREATE TABLE Amostras (
    Id_amostra INT PRIMARY KEY,
    Id_setores INT NOT NULL,
    Id_usuario INT NOT NULL,
    codigo_amostra VARCHAR(50) UNIQUE NOT NULL,
    descricao TEXT,
    data_coleta DATE,
    FOREIGN KEY (Id_setores) REFERENCES Setores(Id_setores) ON DELETE CASCADE,
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario) ON DELETE SET NULL
);

-- TABELA ASSOCIATIVA (Relação n..n entre Setores e Equipamentos)
CREATE TABLE setor_equipamento (
  	Id_setores INT NOT NULL,
    Id_equipamento INT NOT NULL,
    quantidade INT DEFAULT 1,
    ultima_manutencao DATE,
    PRIMARY KEY (Id_setores, Id_equipamento),
    FOREIGN KEY (Id_setores) REFERENCES Setores(Id_setores) ON DELETE CASCADE,
    FOREIGN KEY (Id_equipamento) REFERENCES Equipamentos(Id_equipamento) ON DELETE CASCADE
);