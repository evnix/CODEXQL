<?php
/*
ORDER BY email LIMIT 10,100

select_where("users","$age>12 and $name!=null ","")
*/
require("codeXstream.php");
class codeXql extends codeXstream
{
public $query_vars=array();
public $query_blocks=array();
public $fields=array();
public $insertvalue=null;
public $matches=0;
public function insert_parser($str)
{	 
//username=uone&password=pone&email=eeee
parse_str($str,$this->query_vars);
//var_dump($this->query_vars);

}


//---------------------------------------------------------------------------------------------------------
public function insert($table,$string)
{
if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
return false;
}
$this->filepath=$this->PATH.$this->database."/".$table.".cxt";
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));
$this->insert_parser($string);
if(!($this->check_if_field_exists($this->fields))) //check if fields are valid and create an array!
{
return false;
}
//=======================pass on the array to be written
$this->addrecord($this->PATH.$this->database."/".$table.".cxt",$this->real_insert);

}
//---------------------------------------------------------------------------------------------------------
public function check_if_field_exists($fields)
{
$thereal=null;
$nums=array();
$i=0;
	foreach($this->query_vars as $key=>$val)
	{
	$needle=array_search($key,$fields);
		if($needle===false) //LOL php can't understand the difference between false and 0 
		{
		$this->errors[]="\nField $key Doesn't exist:\n";
		return false;
		}
	$this->real_insert[$needle]=$val;
	//$nums[]=$needle;
	}
	
	foreach($fields as $val)
	{
	if(!isset($this->real_insert[$i]))
	{
	$this->real_insert[$i]=null;
	}
	++$i;
	}
	
	ksort($this->real_insert);
	return true;
}
//---------------------------------------------------------------------------------------------------------
public function select_where($table,$string=null)
{
$TRAY=array();
$XRAY=array();
if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
$this->clean();
return false;
}
$this->read($this->PATH.$this->database."/".$table.".cxt");
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));

if($string==null || $string=="")
{
$string="1==1";
}

$isvar=false;
$str45='
$io=0;
foreach($this->fields as $field)
{
$$field=$ray[$io];
$XRAY[$field]=$ray[$io];
++$io;
}
if('.$string.')
{
$isvar=true;
}
else
{
$isvar=false;
}
';

$i=0; $check=false; $cnt=0; $this->matches=0;
while($this->getrecord($i)!=false)
{
	$ray=$this->getrecord($i);
	eval($str45);
	if($isvar==true)
	{
	$TRAY[]=$XRAY;
	$check=true;
	++$cnt;
	}
	++$i;
}
if($check==false)
{
$this->clean();
return false;
}
$this->matches=$cnt;
$this->clean();
return $TRAY;

}
//----------------------------------------------------------------------------------------------------------
public function order_by($data,$str,$order=SORT_ASC)
{
foreach ($data as $key => $row) 
{
    $volume[$key]  = $row[$str];
}
array_multisort($volume,$order,$data);
return $data;
}
//----------------------------------------------------------------------------------------------------------
public function limit($data,$from=null,$count=null)
{
if($count==null)
{
$count=$from;
$from=0;
}

$from++;
if(!isset($data[$from]))
{
return false;
}
$ray=array();
$i=0;
while(isset($data[$from]) && $i<$count)
{
$ray[]=$data[$from];
++$i;
++$from;
}
return $ray;
}
//----------------------------------------------------------------------------------------------------------
public function delete_where($table,$string)
{
$TRAY=array();
$XRAY=array();
if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
return false;
}
$this->read($this->PATH.$this->database."/".$table.".cxt");
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));

if($string==null || $string=="")
{
$string="1==1";
}

$isvar=false;
$str45='
$io=0;
foreach($this->fields as $field)
{
$$field=$ray[$io];
$XRAY[$field]=$ray[$io];
++$io;
}
if('.$string.')
{
$isvar=true;
}
else
{
$isvar=false;
}
';

$i=0; $check=false; $rows=0;
while($this->getrecord($i)!=false)
{
	$ray=$this->getrecord($i);
	eval($str45);
	if($isvar==true)
	{
	$this->deleterecord($i);
	$check=true;
	++$rows;
	}
	++$i;
}
return $rows;
}
//----------------------------------------------------------------------------------------------------------
public function update_where($table,$param,$string)
{

if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
return false;
}

$this->read($this->PATH.$this->database."/".$table.".cxt");
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));
$this->insert_parser($param);

if(!($this->check_if_field_exists($this->fields))) //check if fields are valid and create an array!
{
return false;
}


if($string==null || $string=="")
{
$string="1==1";
}

$isvar=false;
$str45='
$io=0;
foreach($this->fields as $field)
{
$$field=$ray[$io];
$XRAY[$field]=$ray[$io];
++$io;
}
if('.$string.')
{
$isvar=true;
}
else
{
$isvar=false;
}
';

$i=0; $check=false; $rows=0;
while($this->getrecord($i)!=false)
{
	$ray=$this->getrecord($i);
	eval($str45);
	if($isvar==true)
	{
	$this->updaterecord($i,$this->real_insert);
	$check=true;
	++$rows;
	}
	++$i;
}



return $rows;
}
//----------------------------------------------------------------------------------------------------------
public function delete_column($table,$string)
{
if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
return false;
}
$this->read($this->PATH.$this->database."/".$table.".cxt");
$this->filepath=$this->PATH.$this->database."/".$table.".cxt";
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));
$i=0;
foreach($this->fields as $field)
{
if($field==$string)
{
unset($this->fields[$i]);
break;
}
++$i;
}

$thereal=null;
foreach($this->fields as $val)
{
	if($thereal!=null){ $thereal.=","; }
	$thereal.=$val;
}
$fp = fopen($this->PATH.$this->database."/".$table.".cxf","wb");
fwrite($fp,$thereal);
fclose($fp);
$this->deletecolumn($i);
	
}
//----------------------------------------------------------------------------------------------------------
public function add_column($table,$string)
{
if(is_file($this->PATH.$this->database."/".$table.".cxf")==false)
{
$this->errors[]="\nTable Doesn't exists or no database in use: use_database()\n";
return false;
}
$this->read($this->PATH.$this->database."/".$table.".cxt");
$this->filepath=$this->PATH.$this->database."/".$table.".cxt";
$this->fields=explode(",",file_get_contents($this->PATH.$this->database."/".$table.".cxf"));
$i=0;
foreach($this->fields as $field)
{
if($field==$string)
{
$this->errors[]="\n$string already exists\n";
return false;
}
++$i;
}
$fp = fopen($this->PATH.$this->database."/".$table.".cxf","ab");
fwrite($fp,",".$string);
fclose($fp);
$this->addcolumn();
}
//----------------------------------------------------------------------------------------------------------
public function clean()
{
$this->query_vars=null;
$this->query_blocks=null;
$this->fields=null;
$this->insertvalue=null;
$this->filepath=null;
$this->record=null;
$this->real_insert=null;
$this->isinsert=false;
}


}



?>