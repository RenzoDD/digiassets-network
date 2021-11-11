/*
 * Copyright 2021 (c) Renzo Diaz
 * GNU Affero General Public License v3.0
 * Assets Table
 */

DELIMITER //

DROP TABLE IF EXISTS IpfsCids //
CREATE TABLE IpfsCids (
	IpfsCidID	INTEGER		NOT NULL	AUTO_INCREMENT,

	DigiAssetID	INTEGER		NOT NULL,

    CID			VARCHAR(64)	NOT NULL	UNIQUE,
    Data        TEXT,

	PRIMARY KEY (IpfsCidID),
	FOREIGN KEY (DigiAssetID) REFERENCES DigiAssets	(DigiAssetID)
) //

DROP PROCEDURE IF EXISTS IpfsCids_Create //
CREATE PROCEDURE IpfsCids_Create ( IN DigiAssetID INTEGER, IN CID VARCHAR(64) )
BEGIN
    INSERT INTO IpfsCids (DigiAssetID,CID)
    VALUES  (DigiAssetID,CID);

    SELECT  IC.*
    FROM    IpfsCids AS IC
    WHERE   IC.DigiAssetID = DigiAssetID
            AND IC.CID = CID
	LIMIT   1;
END //

DROP PROCEDURE IF EXISTS IpfsCids_Read_All //
CREATE PROCEDURE IpfsCids_Read_All ( )
BEGIN
    SELECT  IC.*
    FROM    IpfsCids AS IC;
END //

DROP PROCEDURE IF EXISTS IpfsCids_Read_DigiAssetID //
CREATE PROCEDURE IpfsCids_Read_DigiAssetID ( IN DigiAssetID INTEGER )
BEGIN
    SELECT  IC.*
    FROM    IpfsCids AS IC
    WHERE   IC.DigiAssetID = DigiAssetID;
END //

DROP PROCEDURE IF EXISTS IpfsCids_Read_CID //
CREATE PROCEDURE IpfsCids_Read_CID ( IN CID VARCHAR(64) )
BEGIN
    SELECT  IC.*
    FROM    IpfsCids AS IC
    WHERE   IC.CID = CID
	LIMIT	1;
END //

DROP PROCEDURE IF EXISTS IpfsCids_Read_Null_Data //
CREATE PROCEDURE IpfsCids_Read_Null_Data (  )
BEGIN
    SELECT  IC.*
    FROM    IpfsCids AS IC
    WHERE   IC.DATA IS NULL;
END //

DROP PROCEDURE IF EXISTS IpfsCids_Update_Data //
CREATE PROCEDURE IpfsCids_Update_Data ( IN IpfsCidID INTEGER, IN Data TEXT )
BEGIN
    UPDATE  IpfsCids AS IC
    SET     IC.Data = Data
    WHERE   IC.IpfsCidID = IpfsCidID;

    SELECT  IC.*
    FROM    IpfsCids AS IC
    WHERE   IC.IpfsCidID = IpfsCidID
            AND IC.Data = Data
	LIMIT	1;
END //