
CREATE TABLE `token_autorizados` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `token` VARCHAR(150) NOT NULL,
   `status` ENUM('S', 'N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC));
  

CREATE TABLE `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(150) NOT NULL,
    `senha` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `login_UNIQUE` (`login` ASC));

