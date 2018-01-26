<?php class customerArea {
  public function __construct($id = null){
    if (is_numeric($id) && get_user_by('ID', $id) && current_user_can('administrator')){
      $this->user = get_user_by('ID', $id);
    } elseif (current_user_can('customer')) {
      $_user = wp_get_current_user();
      $user = get_userdata($_user->ID);
      $this->user = $user->data;
      $this->user->first_name = $user->first_name;
      $this->user->last_name = $user->last_name;
      $metas = get_the_author_meta( 'customer', $_user->ID );
      foreach ($metas as $key=>$value) {
        $this->user->data->$key = $value;
      }
    }
  }

  public function canEdit(){
    if (current_user_can('customer')) {
      return true;
    }
  }

  public function getUserData() {

    return json_encode($this->user);
  }

}
?>
