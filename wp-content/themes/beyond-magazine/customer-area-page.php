<?php
/**
 * Template Name: Customer area
 */
?>
<?php
get_header();
$customerArea =  new customerArea();
if (is_user_logged_in()){

  $user = wp_get_current_user();
  if ( in_array( 'customer', $user->roles )) {

      //The user has the "customer" role
      ?>
      <div>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Il mio profilo</a></li>
  <li role="presentation"><a href="#subscriptions" aria-controls="subscriptions" role="tab" data-toggle="tab">Abbonamenti</a></li>
  <li role="presentation"><a href="#bookings" aria-controls="bookings" role="tab" data-toggle="" class="disabled-tab">I miei ordini</a></li>
  <li role="presentation"><a href="#recipes" aria-controls="recipes" role="tab" data-toggle="tab">Le mie ricette</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content" data-ng-app="customerAreaApp">
  <div role="tabpanel" class="tab-pane active" id="profile" data-ng-controller="profileController">
    <script user-data id="userData" type="application/json">
    <?php
      echo $customerArea->getUserData()
     ?>
    </script>
    <h3>Il mio profilo</h3>
    <h4>Ciao {{userData.display_name}}</h4>
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-2 control-label" for="user.first_name">Nome</label>
        <div class="col-sm-10">
          <input type="text" placeholder="Nome" id="user.first_name" class="form-control" name="user.first_name" data-ng-model="userData.first_name"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="user.last_name">Cognome</label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" placeholder="Nome" id="user.last_name" class="form-control" name="user.last_name" data-ng-model="userData.last_name"/>
            <div class="input-group-addon">.00</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="user.user_email">Indirizzo e-mail</label>
        <div class="col-sm-10">
          <input type="text" placeholder="Nome" id="user.user_email" class="form-control" name="user.user_email" data-ng-model="userData.user_email"/>
        </div>
      </div>
      <button type="submit" data-ng-click="submit()" class="btn btn-default">Salva</button>
    </form>

  </div>
  <div role="tabpanel" class="tab-pane" id="subscriptions"><h3>Abbonamenti</h3></div>
  <div role="tabpanel" class="tab-pane" id="bookings"><h3>I miei ordini</h3></div>
  <div role="tabpanel" class="tab-pane" id="recipes"><h3>Le mie ricette</h3></div>
</div>

</div>
      <?php
  } else {
    echo "you're not a customer, you're something else";
  }


}
get_footer();
?>
