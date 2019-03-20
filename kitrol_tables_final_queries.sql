DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `suppID` int(11) NOT NULL AUTO_INCREMENT,
  `suppName` varchar(100) NOT NULL,
  `contactNo` varchar(25) NOT NULL,
  `contactperson` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `streetNo` varchar(50) NOT NULL,
  `barangayID` int(11) NOT NULL,
  `cityID` int(11) NOT NULL,
  `provinceID` int(11) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`suppID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `productCode` varchar(20) NOT NULL,
  `description` varchar(150) NOT NULL,
  `umsr` varchar(10) NOT NULL,
  `lastcost` float NOT NULL,
  `lowestcost` float NOT NULL,
  `highcost` float NOT NULL,
  `avecost` float NOT NULL COMMENT '((lastorderQty*lastcost) + (prevRemqty*avecost)) / (lastorderqty + prevRemqty)',
  `markupType` enum('Percentage','Peso') NOT NULL DEFAULT 'Percentage',
  `markup` float NOT NULL,
  `lowestprice` float NOT NULL,
  `highprice` float NOT NULL,
  `aveprice` float NOT NULL COMMENT 'avecost + markup',
  `qty` float NOT NULL,
  `reorderLvl` float NOT NULL COMMENT 'general lvl',
  `isVAT` enum('1','0') NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`productID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `custID` int(11) NOT NULL AUTO_INCREMENT,
  `companyName` varchar(100) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `suffix` varchar(15) NOT NULL,
  `bday` datetime NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `contactNo` varchar(25) NOT NULL,
  `streetNo` varchar(100) NOT NULL,
  `provinceID` int(11) NOT NULL,
  `cityID` int(11) NOT NULL,
  `barangayID` int(11) NOT NULL,
  `creditLimit` decimal(10,3) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`custID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `loyalty`;
CREATE TABLE IF NOT EXISTS `loyalty` (
  `loyaltyID` int(11) NOT NULL AUTO_INCREMENT,
  `loyaltyNo` varchar(15) NOT NULL,
  `custID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rankID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `rankPoints` float NOT NULL,
  `points` float NOT NULL,
  `pesoValue` decimal(10,3) NOT NULL,
  `dateRenewed` datetime NOT NULL,
  `expiry` datetime NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`loyaltyID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `loyalty_rank`;
CREATE TABLE IF NOT EXISTS `loyalty_rank` (
  `rankID` int(11) NOT NULL AUTO_INCREMENT,
  `rankName` varchar(100) NOT NULL,
  `pointsRequired` float NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`rankID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `owners`;
CREATE TABLE IF NOT EXISTS `owners` (
  `ownerID` int(11) NOT NULL,
  `sessionID` varchar(36) DEFAULT NULL,
  `userName` varchar(30) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `userPswd` varchar(36) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `lastName` varchar(25) DEFAULT NULL,
  `firstName` varchar(25) DEFAULT NULL,
  `middleName` varchar(25) DEFAULT NULL,
  `loginAttempt` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ownerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `bankID` int(11) NOT NULL,
  `bankName` varchar(100) NOT NULL,
  `bankAcronym` varchar(25) DEFAULT NULL,
  `loginAttempt` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `bankAccountID` int(11) NOT NULL,
  `bankID` int(11) NOT NULL,
  `accountName` varchar(100) NOT NULL,
  `accountNo` varchar(25) DEFAULT NULL,
  `accountType` tinyint(4) NOT NULL DEFAULT '0',
  `bankAccountType` enum('Income','Expense') NOT NULL DEFAULT 'Income',
  `email` varchar(50) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bankAccountID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;








DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `catID` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`catID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `dipstick_inventory`;
CREATE TABLE IF NOT EXISTS `dipstick_inventory` (
  `dipID` int(11) NOT NULL,
  `inTime` datetime NOT NULL,
  `outTime` datetime NOT NULL,
  `shiftID` int(11) NOT NULL,
  `openingQty` float NOT NULL,
  `closingQty` float NOT NULL,
  `varianceQty` float NOT NULL,
  `openingMsr` float NOT NULL,
  `closingMsr` float NOT NULL,
  `varianceMsr` float NOT NULL,
  `dipper` varchar(100) NOT NULL,
  `verifiedBy` varchar(100) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dipID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `shift`;
CREATE TABLE IF NOT EXISTS `shift` (
  `shiftID` int(11) NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `bankAcronym` varchar(25) DEFAULT NULL,
  `loginAttempt` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`shiftID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `poheaders`;
CREATE TABLE IF NOT EXISTS `poheaders` (
  `poID` int(11) NOT NULL,
  `poNo` varchar(15) NOT NULL,
  `poDate` date NOT NULL,
  `suppID` int(11) NOT NULL,
  `grossAmount` decimal(10,3) NOT NULL,
  `discount` decimal(10,3) NOT NULL,
  `netAmount` decimal(10,3) NOT NULL,
  `paymentTerms` tinyint(4) NOT NULL,
  `dueDate` datetime NOT NULL,
  `amountPaid` decimal(10,3) NOT NULL,
  `balance` decimal(10,3) NOT NULL,
  `datePaid` datetime NOT NULL,  
  `createdBy` varchar(36) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`poID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `podetails`;
CREATE TABLE IF NOT EXISTS `podetails` (
  `id` int(11) NOT NULL,
  `poID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `qty` float NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `amount` decimal(10,3) NOT NULL,
  `delQty` float NOT NULL,
  `delStatus` tinyint(4),
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;





DROP TABLE IF EXISTS `rrheaders`;
CREATE TABLE IF NOT EXISTS `rrheaders` (
  `rrID` int(11) NOT NULL,
  `rrNo` varchar(15) NOT NULL,
  `rrDate` date NOT NULL,
  `drNo` varchar(15) NOT NULL,
  `grossAmount` decimal(10,3) NOT NULL,
  `discount` decimal(10,3) NOT NULL,
  `netAmount` decimal(10,3) NOT NULL,
  `paymentTerms` tinyint(4) NOT NULL,
  `dueDate` datetime NOT NULL,
  `amountPaid` decimal(10,3) NOT NULL,
  `balance` decimal(10,3) NOT NULL,
  `datePaid` datetime NOT NULL,  
  `createdBy` varchar(36) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`rrID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rrdetails`;
CREATE TABLE IF NOT EXISTS `rrdetails` (
  `id` int(11) NOT NULL,
  `poID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `qty` float NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `amount` decimal(10,3) NOT NULL,
  `delQty` float NOT NULL,
  `delStatus` tinyint(4),
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS `rrheaders`;
CREATE TABLE IF NOT EXISTS `rrheaders` (
  `rrID` int(11) NOT NULL,
  `rrNo` varchar(15) NOT NULL,
  `rrDate` date NOT NULL,
  `drNo` varchar(20) NOT NULL,
  `drDate` date NOT NULL,
  `suppID` int(11) NOT NULL,
  `poID` int(11) NOT NULL,
  `plateNo` varchar(20) NOT NULL,
  `driverAssistant` varchar(150) NOT NULL,
  `timeDelivered` datetime NOT NULL,
  `createdBy` varchar(36) NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`rrID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `rrdetails`;
CREATE TABLE IF NOT EXISTS `rrdetails` (
  `id` int(11) NOT NULL,
  `rrID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,  
  `qty` float NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `apheaders`;
CREATE TABLE IF NOT EXISTS `apheaders` (
  `apID` int(11) NOT NULL,
  `invoiceNo` varchar(15) NOT NULL,
  `invoiceDate` date NOT NULL,
  `suppID` int(11) NOT NULL,
  `totalAmount` decimal(15,3) NOT NULL,
  `discount` decimal(15,3) NOT NULL,
  `netAmount` decimal(15,3) NOT NULL,
  `amountPaid` decimal(15,3) NOT NULL,
  `balance` decimal(15,3) NOT NULL,
  `paymentTerms` tinyint(4) NOT NULL,
  `dueDate` datetime NOT NULL,
  `createdBy` varchar(36) NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`apID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `apdetails`;
CREATE TABLE IF NOT EXISTS `apdetails` (
  `id` int(11) NOT NULL,
  `apID` int(11) NOT NULL,
  `poID` int(11) NOT NULL,
  `rrID` int(11) NOT NULL,  
  `amount` decimal(10,3) NOT NULL,
  `amountPaid` decimal(10,3) NOT NULL,
  `balance` decimal(10,3) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,  
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;













DROP TABLE IF EXISTS `bank_deposits`;
CREATE TABLE IF NOT EXISTS `bank_deposits` (
  `depositID` int(11) NOT NULL AUTO_INCREMENT,  
  `bankAccountID` int(11) NOT NULL,
  `depositDate` datetime NOT NULL,
  `amount` decimal(10,3) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateInserted` datetime NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`depositID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `stockcards`;
CREATE TABLE IF NOT EXISTS `stockcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `productID` int(11) NOT NULL,
  `begBal` float NOT NULL,
  `increase` float NOT NULL COMMENT 'add',
  `decrease` float NOT NULL COMMENT 'subtruct',
  `endBal` float NOT NULL,
  `refNo` varchar(30) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `soheaders`;
CREATE TABLE IF NOT EXISTS `soheaders` (
  `soID` int(11) NOT NULL,
  `soNo` varchar(15) NOT NULL,
  `soDate` date NOT NULL,
  `poNo` varchar(15) NOT NULL,
  `poDate` date NOT NULL,
  `custID` int(11) NOT NULL,
  `grossAmount` decimal(10,3) NOT NULL,
  `discount` decimal(10,3) NOT NULL,
  `netAmount` decimal(10,3) NOT NULL,
  `paymentTerms` tinyint(4) NOT NULL,
  `dueDate` datetime NOT NULL,
  `amountPaid` decimal(10,3) NOT NULL,
  `balance` decimal(10,3) NOT NULL,
  `datePaid` datetime NOT NULL,  
  `createdBy` varchar(36) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`soID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sodetails`;
CREATE TABLE IF NOT EXISTS `sodetails` (
  `id` int(11) NOT NULL,
  `soID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `qty` float NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `amount` decimal(10,3) NOT NULL,
  `delQty` int(11) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `drheaders`;
CREATE TABLE IF NOT EXISTS `drheaders` (
  `drID` int(11) NOT NULL,
  `drNo` varchar(15) NOT NULL,
  `drDate` date NOT NULL,
  `custID` int(11) NOT NULL,
  `soID` int(11) NOT NULL,
  `plateNo` varchar(20) NOT NULL,
  `driverName` varchar(150) NOT NULL,
  `driverAssistant` varchar(150) NOT NULL,
  `departure` datetime NOT NULL,
  `createdBy` varchar(36) NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: pending; 2: confirm; 3: partial delivery; 4: fully delivery 5:cancelled',
  PRIMARY KEY (`drID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `drdetails`;
CREATE TABLE IF NOT EXISTS `drdetails` (
  `id` int(11) NOT NULL,
  `drID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,  
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `arheaders`;
CREATE TABLE IF NOT EXISTS `arheaders` (
  `arID` int(11) NOT NULL,
  `invoiceNo` varchar(15) NOT NULL,
  `invoiceDate` date NOT NULL,
  `custID` int(11) NOT NULL,
  `totalAmount` decimal(15,3) NOT NULL,
  `discount` decimal(15,3) NOT NULL,
  `netAmount` decimal(15,3) NOT NULL,
  `amountPaid` decimal(15,3) NOT NULL,
  `balance` decimal(15,3) NOT NULL,
  `paymentTerms` tinyint(4) NOT NULL,
  `dueDate` datetime NOT NULL,
  `createdBy` varchar(36) NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`arID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `ardetails`;
CREATE TABLE IF NOT EXISTS `ardetails` (
  `id` int(11) NOT NULL,
  `arID` int(11) NOT NULL,
  `soID` int(11) NOT NULL,
  `drID` int(11) NOT NULL,  
  `amount` decimal(10,3) NOT NULL,
  `amountPaid` decimal(10,3) NOT NULL,
  `balance` decimal(10,3) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,  
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `collection_headers`;
CREATE TABLE IF NOT EXISTS `collection_headers` (
  `collectionID` int(11) NOT NULL,
  `collectionNo` varchar(15) NOT NULL,
  `collectionDate` date NOT NULL,
  `orNo` varchar(15) NOT NULL,
  `orDate` date NOT NULL,
  `arID` int(11) NOT NULL,
  `totalAmount` decimal(15,3) NOT NULL,  
  `createdBy` varchar(36) NOT NULL,  
  `confirmedBy` varchar(36) NOT NULL,
  `dateConfirmed` datetime NOT NULL,    
  `cancelledBy` varchar(36) NOT NULL,
  `dateCancelled` datetime NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`collectionID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `collection_details`;
CREATE TABLE IF NOT EXISTS `collection_details` (
  `id` int(11) NOT NULL,
  `collectionID` int(11) NOT NULL,
  `arDetailsID` int(11) NOT NULL,
  `soID` int(11) NOT NULL,  
  `drID` int(11) NOT NULL,  
  `amount` decimal(10,3) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,    
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `payment_headers`;
CREATE TABLE IF NOT EXISTS `payment_headers` (
  `paymentID` int(11) NOT NULL,
  `paymentNo` varchar(15) NOT NULL,
  `paymentDate` date NOT NULL,
  `orNo` varchar(15) NOT NULL,
  `orDate` date NOT NULL,
  `apID` int(11) NOT NULL,
  `totalAmount` decimal(15,3) NOT NULL,  
  `paymentMethod` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1:Cash 0:Check',
  `bankAccountID` int(11) NOT NULL,
  `checkNo` varchar(15) NOT NULL,
  `checkDate` date NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,  
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`paymentID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `payment_details`;
CREATE TABLE IF NOT EXISTS `payment_details` (
  `id` int(11) NOT NULL,
  `paymentID` int(11) NOT NULL,
  `apDetailsID` int(11) NOT NULL,
  `poID` int(11) NOT NULL,  
  `rrID` int(11) NOT NULL,  
  `amount` decimal(10,3) NOT NULL,
  `dateInserted` datetime NOT NULL,
  `dateLastEdit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dateDeleted` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,    
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS `unit_measurements`;
CREATE TABLE IF NOT EXISTS `unit_measurements` (
  `umsrID` int(11) NOT NULL AUTO_INCREMENT,
  `umsr` varchar(25) NOT NULL,
  `createdBy` varchar(36) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `deletedBy` varchar(36) NOT NULL,
  `dateDeleted` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`umsrID`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

INSERT INTO `unit_measurements` (`umsrID`, `umsr`, `createdBy`, `dateCreated`, `deletedBy`, `dateDeleted`, `status`) VALUES
(1, 'Gallon', '27586b429a8512a1d74e9060874d9a36', '2014-11-13 13:57:27', '', '0000-00-00 00:00:00', 1),
(2, 'Ounces', '27586b429a8512a1d74e9060874d9a36', '2014-11-13 13:59:22', '', '0000-00-00 00:00:00', 1),
(3, 'Fluid Ounces', '27586b429a8512a1d74e9060874d9a36', '2014-11-13 13:59:54', '', '0000-00-00 00:00:00', 1),
(4, 'Liter', '27586b429a8512a1d74e9060874d9a36', '2014-11-13 14:04:35', '', '0000-00-00 00:00:00', 1),
(5, 'Vial', '27586b429a8512a1d74e9060874d9a36', '2014-11-13 14:20:31', '', '0000-00-00 00:00:00', 1),
(6, 'Bottle', 'cf372fdca644f30c8cc97bcf1ad58b1f', '2014-11-14 14:09:56', '', '0000-00-00 00:00:00', 1),
(7, 'Tank', '27586b429a8512a1d74e9060874d9a36', '2014-11-03 15:41:41', '', '0000-00-00 00:00:00', 1),
(8, 'lbs', '27586b429a8512a1d74e9060874d9a36', '2014-11-03 15:43:10', '', '0000-00-00 00:00:00', 1),
(9, 'Container', '4153be9f3e1e1c93730bb9c739b05714', '2014-11-04 15:08:28', '', '0000-00-00 00:00:00', 1);






























