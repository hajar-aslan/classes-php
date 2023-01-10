<?php 

class User {
	// les attributs 
	private $id;
	public $login;
	public $email;
	public $firstname;
	public $lastname;

	private $conn;

	public function __construct() // connecter à la base de donnée
	{
		$db_hostname = "localhost";
		$db_username = "root";
		$db_password = "";
		$db_database = "classes";

		$this->conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
		
		if (!$this->conn) {
    		die("erreur de connexion a la base de donner $db_database => " . mysqli_connect_error() );
		}

 		// echo "connexion à la base de donnée réussit!!";

		// $requete= 'SELECT* FROM utilisateurs';
	}
	
	public function register($login,$password,$email,$firstname,$lastname) //inscription utilisateur
	{	


		if(isset($login) && isset($password) && isset($email) && isset($firstname) && isset($lastname)) {
			$login = mysqli_real_escape_string($this->conn,htmlspecialchars($login));
			$password = mysqli_real_escape_string($this->conn,htmlspecialchars($password));
			// $confirmation = mysqli_real_escape_string($this->conn,htmlspecialchars($confirmation));
			$email = mysqli_real_escape_string($this->conn,htmlspecialchars($email));
			$firstname = mysqli_real_escape_string($this->conn,htmlspecialchars($firstname));
			$lastname = mysqli_real_escape_string($this->conn,htmlspecialchars($lastname));

			if($login !== "" && $password !== "" && $email !== "" && $firstname !== "" && $lastname !== ""){
				// if($password == $confirmation){
				$requete = "SELECT count(*) FROM utilisateurs where login = '".$login."'";
				$exec_requete = $this->conn -> query($requete);
				$reponse      = mysqli_fetch_array($exec_requete);
				$count = $reponse['count(*)'];

				if($count==0){
					$password = password_hash($password, PASSWORD_DEFAULT);
					$requete = "INSERT INTO utilisateurs (`login`, `password`, `email`, `firstname`, `lastname`) 
					VALUES ('$login', '$password', '$email', '$firstname', '$lastname')";
					$exec_requete = $this->conn -> query($requete);

					echo "Utilisateur enregistré";

					$resultat = mysqli_query($this->conn, "SELECT * FROM `utilisateurs` WHERE login = '$login'");
					$user = mysqli_fetch_assoc($resultat);
					
					return $user;


				} else {
					echo "Utilisateur deja existant";
				}
			}
			else{
				echo "Utilisateur ou mot de passe vide";
			}
		}

	// mysqli_close($this->conn); // fermer la connexion

	}


	public function connect($login,$password) //connexion utilisateur
	{
		// si l'utilisateur est deja connecter
		if ($this->isConnected()) {
			echo "Utilisateur est deja connecter";
			return FALSE;
		} 

			
		if (isset($login) && isset($password)){
			function validate ($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
			$login = validate($login);
			$password = validate($password);

			$resultat = mysqli_query($this->conn, "SELECT * FROM `utilisateurs` WHERE login = '$login'");

			if ($resultat->num_rows > 0) {
				// utilisateur / login existe dans la base de donnée
				$user = mysqli_fetch_assoc($resultat);
				
				$hash_password = $user['password'];

				// echo "password -> " . $password;
				// echo "hash_password -> " . $hash_password;

				// var_dump($user);
				
				if (password_verify($password, $hash_password)) {
					// utilisateur est connecté
					$_SESSION['id'] = $user['id'];
					// donne aux attribute de cette classe les valeurs
					// correspondantes a celles de l'utilisateur connect
					$this->id = $user['id'];
					$this->login = $user['login'];
					$this->email = $user['email'];
					$this->firstname = $user['firstname'];
					$this->lastname = $user['lastname'];
					
					echo "Utilisateur ({$this->login}) est connecté";
					
				}else {
					echo "mot de passe incorrecte";
				}


			} else {
				echo "login ou mot de passe incorrecte";
			}

		}
		
	}

	public function disconnect() //déconnexion utilsateur
	{
		if ($this->isConnected()) {
			echo "{$this->login} a été deconnecté";
			session_unset();

		}else {
			echo "Il n'y a pas d'utilisateur connecté";
		}
	}

	public function delete() // suppression utilsateur
	{
		// echo "deleting...";

		if ($this->isConnected()) {
			$resultat = mysqli_query($this->conn, "DELETE FROM `utilisateurs` WHERE login = '{$this->login}'");

			// var_dump($resultat);

			echo "{$this->login} a été supprimé";

			// deconnecté le user
			$this->disconnect();



		}else {
			echo "Il n'y a pas d'utilisateur connecté";
		}

	}


	public function update($login, $password, $email, $firstname, $lastname) // mise à jour utilisateur 
	{

		if(isset($login) && isset($password) && isset($email) && isset($firstname) && isset($lastname)) {
			$login = mysqli_real_escape_string($this->conn,htmlspecialchars($login));
			$password = mysqli_real_escape_string($this->conn,htmlspecialchars($password));
			// $confirmation = mysqli_real_escape_string($this->conn,htmlspecialchars($confirmation));
			$email = mysqli_real_escape_string($this->conn,htmlspecialchars($email));
			$firstname = mysqli_real_escape_string($this->conn,htmlspecialchars($firstname));
			$lastname = mysqli_real_escape_string($this->conn,htmlspecialchars($lastname));

			if($login !== "" && $password !== "" && $email !== "" && $firstname !== "" && $lastname !== ""){
				// if($password == $confirmation){
				$requete = "SELECT count(*) FROM utilisateurs where login = '".$login."'";
				$exec_requete = $this->conn -> query($requete);
				$reponse      = mysqli_fetch_array($exec_requete);
				$count = $reponse['count(*)'];

				if($count==0 || $login == $this->login) {
					$password = password_hash($password, PASSWORD_DEFAULT);
					$requete = "UPDATE `utilisateurs` 
					SET `login` = '$login', `password` = '$password', `email` = '$email', `firstname` = '$firstname', `lastname` = '$lastname'
					WHERE id = '{$this->id}'";

					$exec_requete = $this->conn -> query($requete);

					// donne aux attribute de cette classe les valeurs
					// correspondantes a celles de l'utilisateur connect
					$this->login = $login;
					$this->email = $email;
					$this->firstname = $firstname;
					$this->lastname = $lastname;

					echo "Profil modifié";

					$resultat = mysqli_query($this->conn, "SELECT * FROM `utilisateurs` WHERE login = '$login'");
					$user = mysqli_fetch_assoc($resultat);
					
					return $user;


				} else {
					echo "Utilisateur deja existant";
				}
			}
			else{
				echo "Utilisateur ou mot de passe vide";
			}
		}

	// mysqli_close($this->conn); // fermer la connexion
	}


	public function isConnected() // pour savoir si l'utilisateur est connecté ou non 
	{
		$userConnected = false;

		if (isset($_SESSION['id'])) {

			$id = $_SESSION['id'];
			$query = "SELECT * FROM `utilisateurs` WHERE id = '$id'";

			$result = mysqli_query($this->conn, $query);
			$user = mysqli_fetch_assoc($result);

			if ($user) {
				// utilisateur est connecté
				$userConnected = true;        
			}

		}

		return $userConnected;
	}

	public function getAllInfos() // chercher toute les infos utilisateur
	{
		$allInfos = [];

		if ($this->isConnected()) {
			$allInfos = [
				"id" => $this->id,
				"login" => $this->login,
				"email" => $this->email,
				"firstname" => $this->firstname,
				"lastname" => $this->lastname
			];
		}

		return $allInfos;
	}


	public function getLogin() // recuperer login utilisateur 
	{
		return $this->login;
	}
	public function getEmail() // recuperer email utilsateur 
	{
		return $this->email;
	}
	public function getFirstname() // recuperer prénom
	{
		return $this->firstname;
	}
	public function getLastname() // recuperer le nom
	{
		return $this->lastname;
	}

}









?>