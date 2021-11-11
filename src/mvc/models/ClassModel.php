<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * Class model
 */

require_once __DIR__.'/DatabaseModel.php';

class ClassModel extends DatabaseModel
{
    public $ClassID;

    public $ClassAtribute1;
    public $ClassAtribute2;
    public $ClassAtribute3;
    
	private function FillData($destiny, $origin)
	{
		if (isset($origin['ClassID']))
			$destiny->ClassID = $origin['ClassID'];

		if (isset($origin['ClassAtribute1']))
			$destiny->ClassAtribute1 = $origin['ClassAtribute1'];
			
		if (isset($origin['ClassAtribute2']))  
			$destiny->ClassAtribute2 = $origin['ClassAtribute2'];
		
		if (isset($origin['ClassAtribute3']))  
			$destiny->ClassAtribute3 = $origin['ClassAtribute3'];
	}

	public function Create($ClassAtribute1,$ClassAtribute2)
	{
		try
		{
			$query = $this->db->prepare("CALL Class_Create(:ClassAtribute1,:ClassAtribute2)");
			$query->bindParam(":ClassAtribute1", $ClassAtribute1, PDO::PARAM_STR);
			$query->bindParam(":ClassAtribute2", $ClassAtribute2, PDO::PARAM_STR);
			
			if (!$query->execute())
				return false;
				
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if (sizeof($result) == 0)
				return false;
			
			$this->FillData($this, $result[0]);
			
			return true;
		}
		catch (Exception $e)
		{ return false; }
	}

    public function RealAll()
    {
        try
        {
            $query = $this->db->prepare("CALL Class_Read_All()");
            
            if (!$query->execute())
                return [];
                
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            
            $A = [];
            foreach ($result as $row)
            {
                $obj = new ClassModel();
                $obj->FillData($obj, $row);
                $A[$obj->ClassID] = $obj;
            }

            return $A;
        }
        catch (Exception $e)
        { return []; }
    }

	public function ReadClassID($ClassID)
	{
		try
		{
			$query = $this->db->prepare("CALL Class_Read_ClassID(:ClassID)");
			$query->bindParam(":ClassID", $ClassID, PDO::PARAM_INT);
			
			if (!$query->execute())
				return null;
				
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if (sizeof($result) == 0)
				return null;
			
			$obj = new ClassModel();
			$obj->FillData($obj, $result[0]);
			
			return $obj;
		}
		catch (Exception $e)
		{ return null; }
	}

    public function ModifyClassAtribute1($ClassID,$ClassAtribute1)
    {
		try
		{
			$query = $this->db->prepare("CALL Class_Modify_ClassAtribute1(:ClassID,:ClassAtribute1)");
			$query->bindParam(":ClassID",        $ClassID,        PDO::PARAM_INT);
			$query->bindParam(":ClassAtribute1", $ClassAtribute1, PDO::PARAM_STR);
	
			if (!$query->execute())
				return false;
				
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if (sizeof($result) == 0)
				return false;
			
			$this->FillData($this, $result[0]);
			
			return true;
		}
		catch (Exception $e)
		{ return false; } 
    }

	public function DeleteClassID($ClassID)
	{
		try
		{
			$query = $this->db->prepare("CALL Class_Delete_ClassID(:ClassID)");
			$query->bindParam(":ClassID", $ClassID, PDO::PARAM_INT);
			
			if (!$query->execute())
				return false;
				
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			return (sizeof($result) == 0);
		}
		catch (Exception $e)
		{ return false; }
	}
}

?>