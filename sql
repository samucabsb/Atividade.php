CREATE TABLE IF NOT EXISTS public.produto (
    id INTEGER GENERATED ALWAYS AS IDENTITY, -- Padrão moderno e seguro
    nome VARCHAR(100) NOT NULL,
    preco NUMERIC(10, 2) NOT NULL DEFAULT 0.00, -- Preciso para dinheiro
    foto VARCHAR(255), -- Aumentado para suportar URLs longas
    ativo BOOLEAN DEFAULT TRUE, -- "status" é vago, "ativo" é mais claro
    
    CONSTRAINT produto_pkey PRIMARY KEY (id)
);

INSERT INTO public.produto (nome, preco, foto, ativo) VALUES 
('Mouse Gamer', 150.00, 'https://via.placeholder.com/150', true),
('Teclado Mecânico', 350.50, NULL, true),
('Monitor 24 Pol', 899.90, 'https://via.placeholder.com/150', false);

CREATE TABLE IF NOT EXISTS public.usuario (
    id INTEGER GENERATED ALWAYS AS IDENTITY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    
    CONSTRAINT usuario_pkey PRIMARY KEY (id)
);

-- Inserindo o seu usuário de teste para o login funcionar
INSERT INTO public.usuario (username, password) VALUES 
('samuel', '123456'),
('admin', 'admin');


-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS public.pedido (
  id         SERIAL PRIMARY KEY,
  cliente    VARCHAR(100) NOT NULL,
  status     VARCHAR(20) DEFAULT 'pendente'
             CHECK (status IN ('pendente','em_andamento','concluido','cancelado')),
  total      NUMERIC(10,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Dados de exemplo
INSERT INTO public.pedido (cliente, status, total) VALUES
  ('João Silva',    'concluido',    350.00),
  ('Maria Souza',   'pendente',      89.90),
  ('Carlos Lima',   'em_andamento', 445.50),
  ('Ana Ferreira',  'concluido',    120.00),
  ('Pedro Alves',   'cancelado',    200.00);

-- Colunas extras na tabela usuario (opcional)
ALTER TABLE public.usuario ADD COLUMN IF NOT EXISTS id      SERIAL;
ALTER TABLE public.usuario ADD COLUMN IF NOT EXISTS email   VARCHAR(100);
ALTER TABLE public.usuario ADD COLUMN IF NOT EXISTS ativo   BOOLEAN DEFAULT true;
ALTER TABLE public.usuario ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT NOW();
