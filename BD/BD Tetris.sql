-- DROP DATABASE IF EXISTS tetris;

-- CREATE DATABASE tetris;

USE id22234683_tetris;

DROP TABLE IF EXISTS users;
-- Tabla con los datos de la cuenta
CREATE TABLE `users` (
  `id_user` INTEGER NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(30) UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `token_user` VARCHAR(32) NOT NULL,
  `token_password` VARCHAR(32) NOT NULL,
  `password_request` BOOLEAN DEFAULT FALSE NOT NULL,
  `fecha_alta` DATETIME NOT NULL,
  `active_user` BOOLEAN DEFAULT FALSE NOT NULL,
  `admin` BOOLEAN DEFAULT FALSE NOT NULL,
  CONSTRAINT PK_USERS PRIMARY KEY (`id_user`)
);
DROP TABLE IF EXISTS user_data;

-- Tabla con los datos del usuario
CREATE TABLE `user_data` (
  `id_user` INTEGER NOT NULL,
  `name` VARCHAR(60) NOT NULL,
  `country` VARCHAR(60) NOT NULL,
  `email` VARCHAR(256),
  `games` INT DEFAULT 0,
  `best_score` INT DEFAULT 0,
  `best_level` INT DEFAULT 0,
  `best_lines_clear` INT DEFAULT 0,
  `best_time` TIME DEFAULT 0,
  CONSTRAINT PK_USER_DATA PRIMARY KEY (`id_user`),
  CONSTRAINT FK_USER_DATA_USERS_ID_USER FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

DROP TABLE IF EXISTS games;

-- Tabla con los datos de las partidas jugadas
CREATE TABLE `games` (
  `id_user` INTEGER NOT NULL,
  `score` INT NOT NULL,
  `max_level` INT NOT NULL,
  `lines_clear` INT NOT NULL,
  `time_game` TIME NOT NULL,
  `date_game` DATETIME NOT NULL,
  CONSTRAINT PK_GAMES PRIMARY KEY (`id_user`, `date_game`),
  CONSTRAINT FK_GAMES_USER_DATA_ID_USER FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

DELIMITER //
-- * Actualizar Best_Score
DROP FUNCTION IF EXISTS fn_find_best_score;//
CREATE FUNCTION fn_find_best_score (v_id_user INTEGER)
RETURNS INT
BEGIN
  DECLARE v_best_score INTEGER;
  SELECT MAX(score) INTO v_best_score FROM games WHERE id_user = v_id_user;
  IF v_best_score IS NULL THEN
    SET v_best_score = 0;
  END IF;
  RETURN v_best_score;
END;
//

DROP PROCEDURE IF EXISTS pr_set_best_score;//
CREATE PROCEDURE pr_set_best_score (v_id_user INTEGER)
BEGIN
  UPDATE user_data SET best_score = fn_find_best_score(v_id_user) WHERE id_user = v_id_user;
END;
//


-- * Actualizar Best_Level
DROP FUNCTION IF EXISTS fn_find_best_level;//
CREATE FUNCTION fn_find_best_level (v_id_user INTEGER)
RETURNS INT
BEGIN
  DECLARE v_best_level INTEGER;
  SELECT MAX(max_level) INTO v_best_level FROM games WHERE id_user = v_id_user;
  IF v_best_level IS NULL THEN
    SET v_best_level = 0;
  END IF;
  RETURN v_best_level;
END;
//

DROP PROCEDURE IF EXISTS pr_set_best_level;//
CREATE PROCEDURE pr_set_best_level (v_id_user INTEGER)
BEGIN
  UPDATE user_data SET best_level = fn_find_best_level(v_id_user) WHERE id_user = v_id_user;
END;
//


-- * Actualizar Best_Lines_Clear
DROP FUNCTION IF EXISTS fn_find_best_lines_clear;//
CREATE FUNCTION fn_find_best_lines_clear (v_id_user INTEGER)
RETURNS INT
BEGIN
  DECLARE v_best_lines_clear INTEGER;
  SELECT MAX(lines_clear) INTO v_best_lines_clear FROM games WHERE id_user = v_id_user;
  IF v_best_lines_clear IS NULL THEN
    SET v_best_lines_clear = 0;
  END IF;
  RETURN v_best_lines_clear;
END;
//


DROP PROCEDURE IF EXISTS pr_set_best_lines_clear;//
CREATE PROCEDURE pr_set_best_lines_clear (v_id_user INTEGER)
BEGIN
  UPDATE user_data SET best_lines_clear = fn_find_best_lines_clear(v_id_user) WHERE id_user = v_id_user;
END;
//


-- * Actualizar Best_Time
DROP FUNCTION IF EXISTS fn_find_best_time;//
CREATE FUNCTION fn_find_best_time (v_id_user INTEGER)
RETURNS INT
BEGIN
  DECLARE v_best_time INTEGER;
  SELECT MAX(time_game) INTO v_best_time FROM games WHERE id_user = v_id_user;
  IF v_best_time IS NULL THEN
    SET v_best_time = '00:00:00';
  END IF;
  RETURN v_best_time;
END;
//

DROP PROCEDURE IF EXISTS pr_set_best_time;//
CREATE PROCEDURE pr_set_best_time (v_id_user INTEGER)
BEGIN
  UPDATE user_data SET best_time = fn_find_best_time(v_id_user) WHERE id_user = v_id_user;
END;
//

DROP TRIGGER IF EXISTS tr_add_game;//

-- Trigger que actualiza los datos al añadir una partida
CREATE TRIGGER tr_add_game AFTER INSERT ON games
FOR EACH ROW
BEGIN
  DECLARE v_best_score, v_best_level, v_best_lines_clear, v_best_time, v_games INT;
  SELECT best_score, best_level, best_lines_clear, best_time, games INTO v_best_score, v_best_level, v_best_lines_clear, v_best_time, v_games FROM user_data WHERE id_user = NEW.id_user;
  IF v_best_score < NEW.score THEN
    UPDATE user_data SET best_score = NEW.score WHERE id_user = NEW.id_user;
  END IF;
  IF v_best_level < NEW.max_level THEN
    UPDATE user_data SET best_level = NEW.max_level WHERE id_user = NEW.id_user;
  END IF;
  IF v_best_lines_clear < NEW.lines_clear THEN
    UPDATE user_data SET best_lines_clear = NEW.lines_clear WHERE id_user = NEW.id_user;
  END IF;
  IF v_best_time < NEW.time_game THEN
    UPDATE user_data SET best_time = NEW.time_game WHERE id_user = NEW.id_user;
  END IF;
  UPDATE user_data SET games = v_games + 1 WHERE id_user = NEW.id_user;
END;
//

DROP TRIGGER IF EXISTS tr_del_game;//

-- Trigger que actualiza los datos al borrar una partida
CREATE TRIGGER tr_del_game AFTER DELETE ON games
FOR EACH ROW
BEGIN
  DECLARE v_best_score, v_best_level, v_best_lines_clear, v_best_time, v_games INT;
  SELECT best_score, best_level, best_lines_clear, best_time, games INTO v_best_score, v_best_level, v_best_lines_clear, v_best_time, v_games FROM user_data WHERE id_user = OLD.id_user;
  IF v_best_score = OLD.score THEN
    CALL pr_set_best_score(OLD.id_user);
  END IF;
  IF v_best_level = OLD.max_level THEN
    CALL pr_set_best_level(OLD.id_user);
  END IF;
  IF v_best_lines_clear = OLD.lines_clear THEN
    CALL pr_set_best_lines_clear(OLD.id_user);
  END IF;
  IF v_best_time = OLD.time_game THEN
    CALL pr_set_best_time(OLD.id_user);
  END IF;
  UPDATE user_data SET games = v_games - 1 WHERE id_user = OLD.id_user;
END;
//

DROP TRIGGER IF EXISTS tr_mod_game;//

-- Trigger que actualiza los datos al modificar una partida
CREATE TRIGGER tr_mod_game AFTER UPDATE ON games
FOR EACH ROW
BEGIN
  DECLARE v_best_score, v_best_level, v_best_lines_clear, v_best_time int;
  SELECT best_score, best_level, best_lines_clear, best_time INTO v_best_score, v_best_level, v_best_lines_clear, v_best_time FROM user_data WHERE id_user = OLD.id_user;

  IF v_best_score = OLD.score THEN
    CALL pr_set_best_score(OLD.id_user);
  ELSEIF v_best_score < NEW.score THEN
    UPDATE user_data SET best_score = NEW.score WHERE id_user = NEW.id_user;
  END IF;

  IF v_best_level = OLD.max_level THEN
    CALL pr_set_best_level(OLD.id_user);
  ELSEIF v_best_level < NEW.max_level THEN
    UPDATE user_data SET best_level = NEW.max_level WHERE id_user = NEW.id_user;
  END IF;

  IF v_best_lines_clear = OLD.lines_clear THEN
    CALL pr_set_best_lines_clear(OLD.id_user);
  ELSEIF v_best_lines_clear < NEW.lines_clear THEN
    UPDATE user_data SET best_lines_clear = NEW.lines_clear WHERE id_user = NEW.id_user;
  END IF;

  IF v_best_time = OLD.time_game THEN
    CALL pr_set_best_time(OLD.id_user);
  ELSEIF v_best_time < NEW.time_game THEN
    UPDATE user_data SET best_time = NEW.time_game WHERE id_user = NEW.id_user;
  END IF;

END;
//
