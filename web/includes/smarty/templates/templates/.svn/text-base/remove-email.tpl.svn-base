<div id='main-full'>
    { if $error eq 1 }
        Database error, please contact us for support.<br /><br />
    { elseif $error eq 2 }
        You have either entered an invalid email or the email you entered is not registered at iSENSE.<br /><br />
    { /if }
    { if $success eq 1 }
        You have been successfully removed from the email list. <br /><br />
    { /if }

    Enter your email to remove yourself from our email list.<br />

    <form action='remove-email.php' method='post'>
        Email: <input type = 'text' name='email'>
        <input type='submit'>
    </form>
</div>