<?php
	if( ! isset( $page ) ) :  # формалізм, схожий на Python
		echo 'Invalid access' ;
		endif;
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PV-111</title>
		<!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<!-- Import Google Icon Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!-- Local styles -->
		<link rel="stylesheet" href="/style.css" />
	</head>
	<body>
		<nav>
			<div class="nav-wrapper blue">
			<a href="/" class="brand-logo left">Test site PV-111</a>
			<ul id="nav-mobile" class="right ">
				<li <?php if($page=='about.php') echo 'class="active"'; ?> >
					<a href="/about">About</a>
				</li>
				<li <?php if($page=='forms.php') echo 'class="active"'; ?> >
					<a href="/forms">Forms</a>
				</li>
				<li <?php if($page=='db.php') echo 'class="active"'; ?> >
					<a href="/db">DB</a>
				</li>
				<li <?php if($page=='') echo 'class="active"'; ?> >
					<a href="/oop">OOP</a>
				</li>
				<?php if( isset( $_CONTEXT[ 'user' ] ) ) : /* авторизований режим */
					$avatar = empty( $_CONTEXT['user']['avatar'] ) 
						? 'no_photo.png' 
						: $_CONTEXT['user']['avatar'] ;  
				?>	
				<li>		
					<img class="circle" style="max-height:50px;margin:5px" src="/avatars/<?= $avatar ?>" alt="avatar"/>
				</li>
				<li>
					<a class="waves-effect waves-light btn modal-trigger blue"  href="#logout-modal">
						<i class="material-icons">logout</i>
					</a>
				</li>
				<?php else : /* гостьовий режим */ ?>
				<li>
					<!-- Modal Trigger -->
					<a class="waves-effect waves-light btn modal-trigger blue" href="#auth-modal">
						<i class="material-icons">login</i>
					</a>
				</li>
				<?php endif ?>	
			</ul>
			</div>
		</nav>
		<div class="container">
			<?php include $page ; ?>
		</div>
	
	
		<!-- Modal Structure -->
		<div id="auth-modal" class="modal">
			<div class="modal-content">
			<h4>Вхід у систему</h4>
			<div class="row">
				<div class="input-field col s6">
				<i class="material-icons prefix">account_circle</i>
				<input id="auth-login" name="auth-login" type="text" >
				<label for="auth-login">Логін</label>		 
				</div>
				<div class="input-field col s6">
				<i class="material-icons prefix">pin</i>
				<input id="auth-password" name="auth-password" type="password" >
				<label for="auth-password">Пароль</label>
				</div>
			</div>
			</div>
			<div class="modal-footer">
			<span id='auth-rejected-message' style="visibility:hidden;color:maroon;display:inline-block;width:50%;text-align:left">Авторизацію відхилено</span>
			<a href="#!" class="modal-close waves-effect waves-green btn-flat">Закрити</a>
			<a href="#!" id="auth-button" class="waves-effect waves-green btn-flat">Вхід</a>
			</div>
		</div>
		<!-- Modal logout -->
		<div id="logout-modal" class="modal">
			<div class="modal-content">
				<h4>Вийти з системи?</h4>
				<div class="row">
					<a href="#!" class="modal-close waves-effect waves-red btn-flat red-text">Ні</a>
					<a href="#!" id="logout-button" class="waves-effect waves-green btn-flat green-text">Так</a>
				</div>
			</div>
		</div>

		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var elems = document.querySelectorAll('.modal');
				var instances = M.Modal.init(elems, {});
				const authButton = document.getElementById("auth-button");
				if(authButton) authButton.addEventListener('click', authClick);
				else console.error("Element '#auth-button' not found");

				const logoutButton = document.getElementById("logout-button");
				if(logoutButton) logoutButton.addEventListener('click', logoutClick);
				else console.error("Element '#auth-button' not found");
			});
			function authClick() {
				const authLogin = document.getElementById("auth-login");
				if(!authLogin) throw "Element '#auth-login' not found" ;
				const authPassword = document.getElementById("auth-password");
				if(!authPassword) throw "Element '#auth-password' not found" ;
				const login = authLogin.value ;
				const password = authPassword.value ;
				if( login.length == 0 ) {
					alert( 'Введіть логін' ) ;
					return ;
				}
				if( password.length == 0 ) {
					alert( 'Введіть пароль' ) ;
					return ;
				}
				fetch( `/auth?login=${login}&password=${password}`, {
					method: 'GET',		
				}).then( r => {
					const msg = document.getElementById('auth-rejected-message');
					if( r.status != 200 ) {
						msg.style.visibility = 'visible';
					}
					else
					{
						msg.style.visibility = 'hidden';
						r.text().then( console.log );
						location.reload();
					}
				} ); 
			}
			function logoutClick() {
				let l = "";
				let p = "";
				fetch( `/auth?login=${l}&password=${p}`, {
					method: 'GET',
				}).then( r => {
					if( r.status == 200 )
						r.text().then( console.log );
						location.reload();
				} ); 
			}
		</script>
	</body>
	<footer class="page-footer blue">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">PV-111</h5>
                <p class="grey-text text-lighten-4">Test site</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                  <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2014 Copyright Text
            <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
            </div>
          </div>
        </footer>
</html>