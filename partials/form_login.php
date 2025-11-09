<section class="login-section">
    <h2>
    INICIA SESION
    </h2>
    <div class="login-wrap">
        <form class="login-form"action="/portal.php?action=autentificar" method="post">
            <lable for="login">Login</lable> 
            <br></br>
            <input type="text" id="login" name="login" required value="<?= $_POST['login'] ?? '' ?>">
            <br></br>
            <label for="passwd">Password</label>
            <br></br>
            <input type="password" id="passwd" name="passwd"required>
            <br></br>
            <button type="submit">Entra </button>

        </form>



    </div>




</section>