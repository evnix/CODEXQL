<?php

class codexql_Base
{
//codex table(cxt)|codex format(cxf)
public $container;
public $seperator;
public $PATH="DATA/";
public $database=null;
public $errors=array();
//--------------------------------------------------------------------------------------------
public function use_database($database)
{
	require("streamconfig.dll");
	$this->container=$GLOBALSTREAM_container;
	$this->seperator=$GLOBALSTREAM_seperator;
	if(is_dir($this->PATH.$database))
	{
	$this->database=$database;
	return true;
	}
	return false;
}
//-------------------------------------------------------------------------------------------
public function create_database($database)
{
	if(is_dir($this->PATH.$database))
	{
	$this->errors[]="\nDatabase already exists\n";
	return false;
	}
	return mkdir($this->PATH.$database);
}
//---------------------------------------------------------------------------------------------
public function create_table($table,$val)
{
	if(is_file($this->PATH.$this->database."/".$table.".cxt") || $this->database==null)
	{
	$this->errors[]="\nTable already exists or no database in use: use_database()\n";
	return false;
	}
	$fp=fopen($this->PATH.$this->database."/".$table.".cxt","w");
	fclose($fp);
	$val=str_replace(" ","",$val);
	$fp=fopen($this->PATH.$this->database."/".$table.".cxf","w");
	fwrite($fp,$val);
	fclose($fp);
	return true;
}
//------------------------------------------------------------------------------------------------
public function drop_table($table)
{
	if(is_file($this->PATH.$this->database."/".$table.".cxt")==false || $this->database==null)
	{
	$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
	return false;
	}

	@unlink($this->PATH.$this->database."/".$table.".cxf");
	return unlink($this->PATH.$this->database."/".$table.".cxt"); 
}
//-------------------------------------------------------------------------------------------------
public function drop_database($database)
{
	if(!is_dir($this->PATH.$database))
	{
	$this->errors[]="\nDatabase doesn't exist\n";
	return false;
	}
	$files=scandir($this->PATH.$database."/");
	foreach($files as $file)
	{
	@unlink($this->PATH.$database."/".$file);
	}
	return rmdir($this->PATH.$database."/");

}
//--------------------------------------------------------------------------------------------------
public function show_databases()
{
$bases=array();
$databases=scandir($this->PATH);
	foreach($databases as $database)
	{
	if(is_dir($this->PATH.$database."/") && $database!=".." && $database!="." )
	{
	$bases[]=$database;
	}
	}
return $bases;
}
//---------------------------------------------------------------------------------------------------
public function show_tables($databasex)
{
$tableR=array();
	if(!is_dir($this->PATH.$databasex."/"))
	{
	$this->errors[]="\nDatabase doesn't exist\n";
	return false;
	}
$count=0;	
$tables=scandir($this->PATH.$databasex."/");
foreach($tables as $table)
{
	if(is_file($this->PATH.$databasex."/".$table))
	{
	$str=str_replace(".cxf","",$table,$count);
	
		if($count>0)
		{
		$tableR[]=$str;
		}
	}
}	
return $tableR;
}
//---------------------------------------------------------------------------------------------------
}

?>