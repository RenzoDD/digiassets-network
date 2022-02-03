<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * GNU Affero General Public License v3.0
 * DigiAsset Model
 */

require_once __DIR__ . '/DatabaseModel.php';

class DigiAssetModel extends DatabaseModel
{
	public $DigiAssetID;

	public $AssetID;
	public $Height;
	public $Name;
	public $Creator;
	public $Description;

	private function FillData($destiny, $origin)
	{
		if (isset($origin['DigiAssetID']))
			$destiny->DigiAssetID = $origin['DigiAssetID'];

		if (isset($origin['AssetID']))
			$destiny->AssetID = $origin['AssetID'];

		if (isset($origin['Height']))
			$destiny->Height = $origin['Height'];

		if (isset($origin['Name']))
			$destiny->Name = $origin['Name'];

		if (isset($origin['Creator']))
			$destiny->Creator = $origin['Creator'];

		if (isset($origin['Description']))
			$destiny->Description = $origin['Description'];
	}

	public function Create($AssetID, $Height)
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Create(:AssetID,:Height)");
			$query->bindParam(":AssetID", $AssetID, PDO::PARAM_STR);
			$query->bindParam(":Height",  $Height,  PDO::PARAM_INT);

			if (!$query->execute())
				return false;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return false;

			$this->FillData($this, $result[0]);

			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function ReadDigiAssetID($DigiAssetID)
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Read_DigiAssetID(:DigiAssetID)");
			$query->bindParam(":DigiAssetID", $DigiAssetID, PDO::PARAM_INT);

			if (!$query->execute())
				return null;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return null;

			$obj = new DigiAssetModel();
			$obj->FillData($obj, $result[0]);

			return $obj;
		} catch (Exception $e) {
			return null;
		}
	}
	public function ReadAssetID($AssetID)
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Read_AssetID(:AssetID)");
			$query->bindParam(":AssetID", $AssetID, PDO::PARAM_STR);

			if (!$query->execute())
				return null;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return null;

			$obj = new DigiAssetModel();
			$obj->FillData($obj, $result[0]);

			return $obj;
		} catch (Exception $e) {
			return null;
		}
	}
	public function ReadQuantity()
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Read_Quantity()");

			if (!$query->execute())
				return -1;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			return $result[0]["Quantity"];
		} catch (Exception $e) {
			return -1;
		}
	}
	public function ReadAll($Page, $Quantity)
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Read_Page(:Page,:Quantity)");
			$query->bindParam(":Page",     $Page,     PDO::PARAM_INT);
			$query->bindParam(":Quantity", $Quantity, PDO::PARAM_INT);

			if (!$query->execute())
				return [];

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return [];

			$A = [];
			foreach ($result as $row) {
				$obj = new DigiAssetModel();
				$obj->FillData($obj, $row);
				$A[$obj->DigiAssetID] = $obj;
			}

			return $A;
		} catch (Exception $e) {
			return [];
		}
	}
	public function ReadLast()
	{
		try {
			$query = $this->db->prepare("CALL DigiAssets_Read_Last( )");

			if (!$query->execute())
				return null;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return null;

			$obj = new DigiAssetModel();
			$obj->FillData($obj, $result[0]);

			return $obj;
		} catch (Exception $e) {
			return null;
		}
	}
}
