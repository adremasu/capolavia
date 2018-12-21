<?php
/**
 *
 */
class BookingUser
{
  public $user;

  public function __construct($userId)
  {
    $args = array(
    	'meta_key'     => 'EUID',
    	'meta_value'   => $userId
     );
     if ($userId){
       $this->user = get_users($args);
     } else {
       $this->user = '';
     }
     unset($this->user[0]->data->user_pass);
  }

  public function get_user_data(){
    $this->user_data = $this->user[0]->data;
    return $this->user_data;
  }

}

 ?>
