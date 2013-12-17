<?php
	class PdoFactory{
		var $pdo;
		var $dsn='mysql:dbname=mydb;host=127.0.0.1';
		var $user='root';
		var $password='root';
		function __construct(){
			try{
				$this->pdo=new PDO($this->dsn,$this->user,$this->password);
				}catch(PDOException $e){
					echo $e->getMessage();
				}
			}
		/*
		*  @param $data array()
		   @param $table table name
		   @param int	
		*/
		function insert($data,$table){
			if(!is_array($data) || !is_string($table)){
				return false;
			}
			$keys=implode(',',array_keys($data));
			$values=array();
			foreach($data as $key=>$value){
				array_push($values,'"'.$value.'"');
			}
			$colums=implode(',',$values);
			
			$table=trim($table);
			$sql='insert into '.trim($table).' ('.$keys.')'.' values '.'('.$colums.')';
			$sth=$this->pdo->prepare($sql);
			$sth->execute();
			//返回插入id
			return $this->pdo->lastInsertId();
		
		}
		/*
		** @param $data array()
		   @param $table table name
		   @return rs array();
		*/
		function find($data,$table){
			if(!is_array($data) || !is_string($table)){
				return false;		
			}
			
			if(count($data)==0){
				return false;
			}
			$values=array();
			foreach($data as $key=>$value){
				array_push($values,$key.'='.'"'.$value.'"');
			}
			$conditions=implode(' and ',$values);
			$sql='select * from '.trim($table).' where '.$conditions;
			$sth=$this->pdo->prepare($sql);
			$sth->execute();
			$rs=$sth->fetchAll();
			return $rs;
		}
		/*
		**  @param $update array()
		**  @param $where  array()
		**  @param $table table name;
		**  return if sucess return 1
		*/
		function update($update,$where,$table){
			if(!is_array($update) || !is_string($table) || !is_array($where)){
				return false;
			}
			$values=array();
			$conditions=array();
			if(count($update)==0 || count($where)==0){
				return false;
			}
			foreach($update as $key=>$value){
				array_push($values,$key.'='.'"'.$value.'"');
			}
			$update=implode(' and ',$values);
			foreach($where as $key=>$value){
				array_push($conditions,$key.'='."'".$value."'");
			}
			$where=implode(' and ',$conditions);	
			$sql='update '.trim($table). ' set '.$update.' where '.$where;
			$sth=$this->pdo->prepare($sql);
			$state=$sth->execute();
			return $state;
		}	
	}
