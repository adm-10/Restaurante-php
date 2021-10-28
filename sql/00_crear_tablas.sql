Use quevedodb;

-- Crea tablas usuarios

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(11) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_reserva DATE NULL,
    hora_reserva TIME NULL,
    meal  VARCHAR(255) NULL,
    CONSTRAINT usuarios_pk PRIMARY KEY (id)
);

