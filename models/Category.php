<?php
class Category extends Model{
	public function articles(){
        return $this->has_many('Article');
    }
}