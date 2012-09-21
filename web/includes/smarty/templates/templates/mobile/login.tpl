<head>
    <link rel="stylesheet" href="./html/css/mobile/jquery.mobile.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
    <script src="/html/js/isense.js"></script>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
</head>

<div data-theme="a" data-role="header"><h3>Login</h3></div>

{ if $user.guest }
    <form method="post" data-ajax="false">
    <div data-role="content" style="padding: 15px">
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
                <label for="textinput1">Username</label>
                <input name="email" id="textinput1" placeholder="example@gmail.com" value="{$email}" type="text">
            </fieldset>
        </div>
        <div data-role="fieldcontain">
            <fieldset data-role="controlgroup">
                <label for="textinput2">Password</label>
                <input name="password" id="textinput2" type="password">
            </fieldset>
        </div>
        <input type="submit" name="submit" value="Login">
    </div>
    </form>
{ else }
    <div data-role="content" style="padding: 15px">
        <p><b>You are currently logged in as:</b> { $user.email }</p>
        <a href="logout.php" data-ajax="false" data-role="button">Logout</a>
    </div>
{ /if }