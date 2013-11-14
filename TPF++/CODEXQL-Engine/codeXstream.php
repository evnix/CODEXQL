<?php
require("sql.php");
class codeXstream extends codexql_Base
{
public $filepath;
public $record;
public $real_insert=array();
public $isinsert=false;
//------------------------------------------------------------------------
public function addrecord($file,$string)
{
$thereal=null;
foreach($string as $val)
{
	if($thereal!=null){ $thereal.=$this->seperator; }
	$thereal.=$val;
}
$this->record[]=$thereal;
$this->isinsert=true;
/* $fp = fopen($file,'ab');
fwrite($fp,$thereal.$this->container);
fclose($fp); */
}
//--------------------------------------------------------------------------
public function read($file)
{
	$this->filepath=$file;
	$this->record=explode($this->container,file_get_contents($file));
}
//---------------------------------------------------------------------------
public function getrecord($int)
{
if(!isset($this->record[$int]) || $this->record[$int]=="")
{
return false;
}
return explode($this->seperator,$this->record[$int]);

}
//--------------------------------------------------------------------------
public function deleterecord($int)
{
$this->isinsert=false;

if(!isset($this->record[$int]) || $this->record[$int]=="")
{
return false;
}
unset($this->record[$int]);

}
//--------------------------------------------------------------------------
public function updaterecord($int,$string)
{
$this->isinsert=false;

$ray=$this->getrecord($int);
$i=0;
foreach($string as $val)
{
if($val!=null)
{
$ray[$i]=$val;
}
++$i;
}

$thereal=null;
foreach($ray as $val)
{
	if($thereal!=null){ $thereal.=$this->seperator; }
	$thereal.=$val;
}

$this->record[$int]=$thereal;

}
//-------------------------------------------------------------------------
public function deletecolumn($x)
{
$this->isinsert=false;

$i=0;
while($this->getrecord($i)!=false)
{
$ray=$this->getrecord($i);
unset($ray[$x]);
$thereal=null;
foreach($ray as $val)
{
	if($thereal!=null){ $thereal.=$this->seperator; }
	$thereal.=$val;
}

$this->record[$i]=$thereal; 

++$i;
}
$this->commit_changes();
}
//--------------------------------------------------------------------------
public function addcolumn()
{
$this->isinsert=false;
$i=0;
while($this->getrecord($i)!=false)
{
$ray=$this->getrecord($i);

$thereal=null;
foreach($ray as $val)
{
	if($thereal!=null){ $thereal.=$this->seperator; }
	$thereal.=$val;
}

$this->record[$i]=$thereal.$this->seperator; 

++$i;
}

$this->commit_changes();
}
//--------------------------------------------------------------------------
public function commit_changes()
{

if($this->filepath==null || $this->database==null)
{
$this->errors[]="\nCommit changes: Table Doesn't exists or no database in use: use_database()\n";
return false;
}
 $data=null;
	foreach ($this->record as $k => $v) 
	{  
	if($v!="")
	{ $data.=$v.$this->container; }	
	
	}
	$mode="r+";
	if($this->isinsert==true)
	{
		$mode="ab";
	}
	
	
$fp = fopen($this->filepath,$mode);
flock($fp,LOCK_EX); //lock the file for exclusive writing

 if($this->isinsert!=true)
 {
 $sf=fopen($this->filepath,"w");
 fclose($sf);
 }
fwrite($fp,$data);
flock($fp,LOCK_UN); // release the lock
fclose($fp);
$this->clean();
clearstatcache();

}
//--------------------------------------------------------------------------
}

?>