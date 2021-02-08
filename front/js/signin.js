// Login user
$('#signin-form').submit(e => {
    // Prevent default
    e.preventDefault();
    // Get data from the form
    let username = e.target.username.value;
    let password = e.target.password.value;
    // Validate data
    if(username === '') {
        return alert("username is required");
    }

    if(password === '' === '') {
        return alert("password is required");
    }

    if(password.length < 8) {
        return alert("password should be at least 8 characters");
    }
    // Create data object
    let data = {
        username: username,
        password: password
    };
    $.ajax({
        url: 'http://localhost/playing/back/controllers/authentication/login.php/',
        type: 'POST',
        data: JSON.stringify(data),
        success: function(response){
            // Redirect to projects page
            location.replace('http://localhost/playing/front/pages/projects.php');
        },
        error: function(){
            // Do something on error
        }
    })
})