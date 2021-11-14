<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * GNU Affero General Public License v3.0
 * IPFS CID Model
 */

require_once __DIR__ . '/DatabaseModel.php';

class IpfsCidModel extends DatabaseModel
{
	public $IpfsCidID;

	public $DigiAssetID;

	public $CID;
	public $Data;

	private function FillData($destiny, $origin)
	{
		if (isset($origin['IpfsCidID']))
			$destiny->IpfsCidID = $origin['IpfsCidID'];

		if (isset($origin['DigiAssetID']))
			$destiny->DigiAssetID = $origin['DigiAssetID'];

		if (isset($origin['CID']))
			$destiny->CID = $origin['CID'];

		if (isset($origin['Data']))
			$destiny->Data = $origin['Data'];
	}

	public function Create($DigiAssetID, $CID)
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Create(:DigiAssetID,:CID)");
			$query->bindParam(":DigiAssetID", $DigiAssetID, PDO::PARAM_INT);
			$query->bindParam(":CID",         $CID,         PDO::PARAM_STR);

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

	public function ReadAll()
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Read_All()");

			if (!$query->execute())
				return [];

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return [];

			$A = [];
			foreach ($result as $row) {
				$obj = new IpfsCidModel();
				$obj->FillData($obj, $row);
				$A[$obj->IpfsCidID] = $obj;
			}

			return $A;
		} catch (Exception $e) {
			return [];
		}
	}

	public function ReadDigiAssetID($DigiAssetID)
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Read_DigiAssetID(:DigiAssetID)");
			$query->bindParam(":DigiAssetID", $DigiAssetID, PDO::PARAM_INT);

			if (!$query->execute())
				return [];

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return [];

			$A = [];
			foreach ($result as $row) {
				$obj = new IpfsCidModel();
				$obj->FillData($obj, $row);
				$A[$obj->IpfsCidID] = $obj;
			}

			return $A;
		} catch (Exception $e) {
			return [];
		}
	}

	public function ReadCID($CID)
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Read_CID(:CID)");
			$query->bindParam(":CID", $CID, PDO::PARAM_STR);

			if (!$query->execute())
				return null;

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return null;

			$obj = new IpfsCidModel();
			$obj->FillData($obj, $result[0]);

			return null;
		} catch (Exception $e) {
			return null;
		}
	}

	public function ReadNullData()
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Read_Null_Data()");

			if (!$query->execute())
				return [];

			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			if (sizeof($result) == 0)
				return [];

			$A = [];
			foreach ($result as $row) {
				$obj = new IpfsCidModel();
				$obj->FillData($obj, $row);
				$A[$obj->IpfsCidID] = $obj;
			}

			return $A;
		} catch (Exception $e) {
			return [];
		}
	}

	public function UpdateData($IpfsCidID, $Data)
	{
		try {
			$query = $this->db->prepare("CALL IpfsCids_Update_Data(:IpfsCidID,:Data)");
			$query->bindParam(":IpfsCidID", $IpfsCidID, PDO::PARAM_INT);
			$query->bindParam(":Data",      $Data,      PDO::PARAM_STR);

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
}
