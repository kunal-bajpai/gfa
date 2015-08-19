<?php
class File
{
	public static function get_files()
	{
		if(isset($_FILES))
			foreach($_FILES as $row)
			{
				$file=new self;
				foreach($row as $key=>$value)
					$file->$key=$value;
				$result[]=$file;
			}
		if(isset($result))
			return $result;
		else
			return NULL;
	}
	
	public static function get_file_array($name)
	{
		if(is_array($_FILES[$name]))
			for($i=0;$i<count($_FILES[$name]['name']);$i++)
			{
				$file = new self;
				foreach($_FILES[$name] as $property=>$array)
					$file->$property = $array[$i];
				$result[]=$file;
			}
		else
			return NULL;
		if(isset($result))
			return $result;
		else
			return NULL;
	}

	public function save_file()
	{
		$this->save_file_in(UPLOAD_DIR);
	}
	
	public function save_file_in($dir)
	{
		if(move_uploaded_file($this->tmp_name,$dir.$this->name))

		{
			Log::log_action("File uploaded ".$dir.$this->name);
			return true;
		}
		else
			return false;
	}

	public function delete_file()
	{
		if(unlink(UPLOAD_DIR.$this->name))
		{
			Log::log_action("File deleted ".UPLOAD_DIR.$this->name);
			return true;
		}
		else
			return false;
	}

	public function file_exists()
	{
		return file_exists(UPLOAD_DIR.$this->name);
	}


	public function is_image()
	{
		$nameArr = explode('.',$this->name);
		return (count($nameArr) > 1 && in_array(strtoupper(end($nameArr)),Imagick::queryFormats()));
	}
	
	public static function rename_if_exists($filename,$dir)
	{
		if(file_exists($dir.$filename))
		{							
			$i=1;
			$nameArray=explode('.',$filename);
			$name=$nameArray[0];
			if(isset($nameArray[1]))
			{
				$format=$nameArray[1];
				while(file_exists($dir.$name.$i.'.'.$format))
					$i++;
				return $name.$i.'.'.$format;
			}
			else
			{
				while(file_exists($dir.$name.$i))
					$i++;
				return $name.$i;
			}
		}
        else
            return $filename;
	}
    
    public function rename_if_exists_in($dir)
    {
        $this->name=File::rename_if_exists($this->name,$dir);
    }

	public function check_type($typeString)
	{
		$typeArray=explode(',',$typeString);
		$nameArray=explode('.',$this->name);
		if(count($nameArray)>1)
			$type=strtolower(end($nameArray));
		else
			$type="";
		return in_array($type,$typeArray);
	}
}
