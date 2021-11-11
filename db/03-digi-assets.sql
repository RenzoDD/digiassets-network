/*
 * Copyright 2021 (c) Renzo Diaz
 * GNU Affero General Public License v3.0
 * Assets Table
 */

DELIMITER //

DROP TABLE IF EXISTS DigiAssets //
CREATE TABLE DigiAssets (
	DigiAssetID	INTEGER		NOT NULL    AUTO_INCREMENT,

    AssetID		VARCHAR(38)	NOT NULL	UNIQUE,
	Height		INTEGER		NOT NULL,
	
    PRIMARY KEY (DigiAssetID)
) //

DROP PROCEDURE IF EXISTS DigiAssets_Create //
CREATE PROCEDURE DigiAssets_Create ( IN AssetID VARCHAR(64), IN Height INTEGER )
BEGIN
    INSERT INTO DigiAssets (AssetID,Height)
    VALUES  (AssetID,Height);

    SELECT  DA.*
    FROM    DigiAssets AS DA
    WHERE   DA.AssetID = AssetID
            AND DA.Height = Height
	LIMIT   1;
END //

DROP PROCEDURE IF EXISTS DigiAssets_Read_DigiAssetID //
CREATE PROCEDURE DigiAssets_Read_DigiAssetID ( IN DigiAssetID INTEGER )
BEGIN
    SELECT  DA.*
    FROM    DigiAssets AS DA
    WHERE   DA.DigiAssetID = DigiAssetID
    LIMIT   1;
END //

DROP PROCEDURE IF EXISTS DigiAssets_Read_AssetID //
CREATE PROCEDURE DigiAssets_Read_AssetID ( IN AssetID VARCHAR(64) )
BEGIN
    SELECT  DA.*
    FROM    DigiAssets AS DA
    WHERE   DA.AssetID = AssetID
    LIMIT   1;
END //

DROP PROCEDURE IF EXISTS DigiAssets_Read_Quantity //
CREATE PROCEDURE DigiAssets_Read_Quantity ( )
BEGIN
    SELECT  COUNT(*) AS Quantity
    FROM    DigiAssets AS DA;
END //

DROP PROCEDURE IF EXISTS DigiAssets_Read_Page //
CREATE PROCEDURE DigiAssets_Read_Page ( IN Page INTEGER, IN Quantity INTEGER )
BEGIN
    DECLARE Offset INTEGER;
    SET Offset = (Page - 1) * Quantity;

    SELECT  DA.*
    FROM    DigiAssets AS DA
    WHERE   DA.AssetID = AssetID
            AND DA.Height = Height
    ORDER BY Height DESC
	LIMIT Offset, Quantity;
END //