<?php
class Article extends Model{
	public function category(){
		return $this->belongs_to('Category');
	}
}