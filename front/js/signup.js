// Register user
$('#signup-form').submit(e => {
    // Prevent default
    e.preventDefault();
    // Get data from the from
    let username = e.target.username.value;
    let email = e.target.email.value;
    let password = e.target.password.value;
    let cpassword = e.target.cpassword.value;
    // Validate data
    if(username === '') {
        return alert("username is required");
    }

    if(email === '') {
        return alert("email is required");
    }

    if(password === '' === '') {
        return alert("password is required");
    }

    if(cpassword === '') {
        return alert("confirm password is required");
    }

    if(password !== cpassword) {
        return alert("password do not match");
    }

    if(password.length < 8) {
        return alert("password should be at least 8 characters");
    }
    // Create data object
    let data = {
        username: username,
        email: email,
        password: password
    };
    $.ajax({
        url: 'http://localhost/playing/back/controllers/users/post.php/',
        type: 'POST',
        data: JSON.stringify(data),
        success: response => {
          alert("Registration Successfull!");
          window.location.href = "http://localhost/playing/front/pages/signin.html";
        }
    })
})