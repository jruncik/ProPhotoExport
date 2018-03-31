<?php
class FileInfo
{
	public function __construct ($id, $fullPath)
	{
		$this->id = $id;
		$this->fullPath = $fullPath;
		$this->fileName = '';
	}
	
	public function GetFileId()
	{
		return $this->id;
	}
	
	public function GetFileFullPath()
	{
		return $this->fullPath;
	}

	public function SetFileName($fileName)
	{
		$this->fileName = $fileName;
	}
	
	public function GetFileName()
	{
		return $this->fileName;
	}
	
	public function ToString()
	{
		return "ID: " . $this->id . ", FileName: " . $this->fileName . ", FullPath: " . $this->fullPath;
	}
	
	private $id;
	private $fullPath;
	private $fileName;
}
?>