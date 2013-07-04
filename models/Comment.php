<?php
class Comment extends Model{
	public function category(){
		return $this->belongs_to('Article');
	}
}