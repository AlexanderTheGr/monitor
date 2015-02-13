SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`esoda`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `esoda` (
  `id` INT NOT NULL ,
  `sinalasomenosID` INT NULL ,
  `creationDate` DATE NULL ,
  `invoiceDate` DATE NULL ,
  `plannedInvoiceDate` DATE NULL ,
  `invoiceAmount` DECIMAL(6,2) NULL ,
  `invoiceVAT` DECIMAL(6,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `eispraxeis`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `eispraxeis` (
  `id` INT NOT NULL ,
  `esodoID` INT NULL ,
  `dueDate` DATE NULL ,
  `plannedDueDate` DATE NULL ,
  `paidDate` DATE NULL ,
  `parentEispraxi` INT NULL COMMENT 'Parent Eispraxi. Π.χ. αν μια ειπραξεη εσπασε σε 2 μικροτερες, τοτε η parent γινεται cancelled, και δημιουργούνται δυο μικρότερες με parent αυτην που ακυρωθηκε.' ,
  `status` INT NULL COMMENT 'Active, Paid, Cancelled' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_eispraxeis_xreoseis` (`esodoID` ASC) ,
  CONSTRAINT `fk_eispraxeis_xreoseis`
    FOREIGN KEY (`esodoID` )
    REFERENCES `esoda` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `exoda`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `exoda` (
  `id` INT NOT NULL ,
  `sinalasomenosID` INT NULL ,
  `creationDate` DATE NULL ,
  `invoiceDate` DATE NULL ,
  `planendInvoiceDate` DATE NULL ,
  `invoiceAmount` DECIMAL(6,2) NULL ,
  `invoiceVAT` DECIMAL(6,2) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pliromes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pliromes` (
  `id` INT NOT NULL ,
  `exodoID` INT NULL ,
  `dueDate` DATE NULL ,
  `plannedDueDate` DATE NULL ,
  `paidDate` DATE NULL ,
  `parentPliromi` INT NULL ,
  `status` INT NULL COMMENT 'Active, Paid, Cancelled' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pliromes_exoda1` (`exodoID` ASC) ,
  CONSTRAINT `fk_pliromes_exoda1`
    FOREIGN KEY (`exodoID` )
    REFERENCES `exoda` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `paymentplans`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `paymentplans` (
  `id` INT NOT NULL ,
  `agreement` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_paymentplans_agreement1_idx` (`agreement` ASC) ,
  CONSTRAINT `fk_paymentplans_agreement1`
    FOREIGN KEY (`agreement` )
    REFERENCES `agreement` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `duePlans`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `duePlans` (
  `id` INT NOT NULL ,
  `agreementID` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_duePlans_agreement1_idx` (`agreementID` ASC) ,
  CONSTRAINT `fk_duePlans_agreement1`
    FOREIGN KEY (`agreementID` )
    REFERENCES `agreement` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `pos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pos` (
  `id` INT NOT NULL ,
  `posTitle` VARCHAR(255) NULL ,
  `posDescription` TEXT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `agreement`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `agreement` (
  `id` INT NOT NULL ,
  `posID` INT NULL ,
  `title` VARCHAR(255) NULL ,
  `description` TEXT NULL ,
  `activeDuePlan` INT NULL ,
  `activePaymentPlan` INT NULL ,
  `sourceID` INT NULL ,
  `sinallasomenosID` INT NULL ,
  `ts` TIMESTAMP NULL ,
  `paymentplans_id` INT NOT NULL ,
  `duePlans_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_agreement_paymentplans1_idx` (`paymentplans_id` ASC) ,
  INDEX `fk_agreement_duePlans1_idx` (`duePlans_id` ASC) ,
  INDEX `fk_agreement_pos1_idx` (`posID` ASC) ,
  CONSTRAINT `fk_agreement_paymentplans1`
    FOREIGN KEY (`paymentplans_id` )
    REFERENCES `paymentplans` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_agreement_duePlans1`
    FOREIGN KEY (`duePlans_id` )
    REFERENCES `duePlans` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_agreement_pos1`
    FOREIGN KEY (`posID` )
    REFERENCES `pos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `Transactor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Transactor` (
  `id` INT NOT NULL ,
  `balance` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Transactor=Συναλλασσόμενος';


-- -----------------------------------------------------
-- Table `duePlanDetails`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `duePlanDetails` (
  `id` INT NOT NULL ,
  `transactorID` INT NULL ,
  `duePlan` INT NULL ,
  `type` INT NULL COMMENT 'Type is: Χρέωση ή Πίστωση' ,
  `amount` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `datetime` DATETIME NULL ,
  `user` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_duePlanDetails_duePlans1_idx` (`duePlan` ASC) ,
  INDEX `fk_duePlanDetails_sinallasomenoi1_idx` (`transactorID` ASC) ,
  CONSTRAINT `fk_duePlanDetails_duePlans1`
    FOREIGN KEY (`duePlan` )
    REFERENCES `duePlans` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_duePlanDetails_sinallasomenoi1`
    FOREIGN KEY (`transactorID` )
    REFERENCES `Transactor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `businessUnits`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `businessUnits` (
  `id` INT NOT NULL ,
  `unitName` VARCHAR(255) NULL COMMENT 'e.g. OUAOU' ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `pppayments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pppayments` (
  `id` INT NOT NULL ,
  `transactorID` INT NULL ,
  `accountGroup` INT NULL ,
  `paymentPlanID` INT NULL ,
  `type` INT NULL COMMENT 'Χρέωση ή Πίστωση' ,
  `amount` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `dueDate` DATE NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_pppayments_paymentplans1_idx` (`paymentPlanID` ASC) ,
  INDEX `fk_pppayments_sinallasomenoi1_idx` (`transactorID` ASC) ,
  INDEX `fk_pppayments_accountGroups1_idx` (`accountGroup` ASC) ,
  CONSTRAINT `fk_pppayments_paymentplans1`
    FOREIGN KEY (`paymentPlanID` )
    REFERENCES `paymentplans` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pppayments_sinallasomenoi1`
    FOREIGN KEY (`transactorID` )
    REFERENCES `Transactor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pppayments_accountGroups1`
    FOREIGN KEY (`accountGroup` )
    REFERENCES `businessUnits` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `cashAccounts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cashAccounts` (
  `id` INT NOT NULL ,
  `title` VARCHAR(255) NULL ,
  `accountNumber` VARCHAR(255) NULL ,
  `accountType` VARCHAR(45) NULL COMMENT 'Αυτό το κρατάω για να μπεί χαρακτηρισμός του τύπου. Π.χ. ταμιευτηρίου, τρεχούμενος, κλπ' ,
  `balance` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `lastModidief` DATETIME NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `cashTransactions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cashTransactions` (
  `int` INT NOT NULL ,
  `transactorID` INT NULL ,
  `businessUnit` INT NULL COMMENT 'This is the unit for which this transaction is registered.' ,
  `datetime` DATETIME NULL ,
  `amount` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `cashAccount` INT NULL COMMENT 'This is the account where cash is been deposited or withdrawn.' ,
  `transactionReference` VARCHAR(255) NULL COMMENT 'Αυτό μπορεί να είναι ο κωδικός συναλλαγής (π.χ. σε μία κατάθεση ή μια μεταφορά χρημάτων ή μια πληρωμή από πιστωτική κάρτα).' ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`int`) ,
  INDEX `fk_realPayments_sinallasomenoi1_idx` (`transactorID` ASC) ,
  INDEX `fk_realPayments_accountGroups1_idx` (`businessUnit` ASC) ,
  INDEX `fk_cashTransactions_cashAccounts1` (`cashAccount` ASC) ,
  CONSTRAINT `fk_realPayments_sinallasomenoi1`
    FOREIGN KEY (`transactorID` )
    REFERENCES `Transactor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_realPayments_accountGroups1`
    FOREIGN KEY (`businessUnit` )
    REFERENCES `businessUnits` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cashTransactions_cashAccounts1`
    FOREIGN KEY (`cashAccount` )
    REFERENCES `cashAccounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `collectors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `collectors` (
  `id` INT NOT NULL ,
  `collectorTitle` VARCHAR(255) NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `cashTransfers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cashTransfers` (
  `id` INT NOT NULL ,
  `originAccount` INT NULL ,
  `destinationAccount` INT NULL ,
  `amount` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `datetime` DATETIME NULL ,
  `user` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cashTransfers_cashAccounts1` (`originAccount` ASC) ,
  INDEX `fk_cashTransfers_cashAccounts2` (`destinationAccount` ASC) ,
  CONSTRAINT `fk_cashTransfers_cashAccounts1`
    FOREIGN KEY (`originAccount` )
    REFERENCES `cashAccounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cashTransfers_cashAccounts2`
    FOREIGN KEY (`destinationAccount` )
    REFERENCES `cashAccounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `cashAccountsBalanceChanges`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `cashAccountsBalanceChanges` (
  `id` INT NOT NULL ,
  `accountId` INT NULL ,
  `previousBalance` DECIMAL(6,2) NULL ,
  `newBalance` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `datetime` DATETIME NULL ,
  `user` INT NULL ,
  `notes` TEXT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cashAccountsBalances_cashAccounts1` (`accountId` ASC) ,
  CONSTRAINT `fk_cashAccountsBalances_cashAccounts1`
    FOREIGN KEY (`accountId` )
    REFERENCES `cashAccounts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `transactorBalanceChanges`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `transactorBalanceChanges` (
  `id` INT NOT NULL ,
  `transactorID` INT NULL ,
  `previousBalance` DECIMAL(6,2) NULL ,
  `newBalance` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `datetime` DATETIME NULL ,
  `user` INT NULL ,
  `notes` TEXT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sinallasomenoiBalanceChanges_sinallasomenoi1` (`transactorID` ASC) ,
  CONSTRAINT `fk_sinallasomenoiBalanceChanges_sinallasomenoi1`
    FOREIGN KEY (`transactorID` )
    REFERENCES `Transactor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `banks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `banks` (
  `id` INT NOT NULL ,
  `bankName` VARCHAR(255) NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `checks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `checks` (
  `id` INT NOT NULL ,
  `checkNumber` VARCHAR(255) NULL ,
  `issuingBank` INT NULL ,
  `expirationDate` DATE NULL ,
  `amount` DECIMAL(6,2) NULL ,
  `currency` INT NULL ,
  `cashTransaction` INT NULL ,
  `user` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_checks_cashTransactions1` (`cashTransaction` ASC) ,
  INDEX `fk_checks_banks1` (`issuingBank` ASC) ,
  CONSTRAINT `fk_checks_cashTransactions1`
    FOREIGN KEY (`cashTransaction` )
    REFERENCES `cashTransactions` (`int` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_checks_banks1`
    FOREIGN KEY (`issuingBank` )
    REFERENCES `banks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '<double-click to overwrite multiple objects>';


-- -----------------------------------------------------
-- Table `posTransactorsMatching`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `posTransactorsMatching` (
  `id` INT NOT NULL ,
  `posID` INT NULL ,
  `posTransactorReference` VARCHAR(255) NULL ,
  `transactorID` INT NULL ,
  `ts` TIMESTAMP NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_posTransactorsMatching_pos1` (`posID` ASC) ,
  INDEX `fk_posTransactorsMatching_Transactor1` (`transactorID` ASC) ,
  CONSTRAINT `fk_posTransactorsMatching_pos1`
    FOREIGN KEY (`posID` )
    REFERENCES `pos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posTransactorsMatching_Transactor1`
    FOREIGN KEY (`transactorID` )
    REFERENCES `Transactor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Ο πίνακας αυτός χρησιμοποιείται για να κάνει ταύτιση των ίδι' /* comment truncated */;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
