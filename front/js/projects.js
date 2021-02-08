let cookies = new Map();

document.cookie.split('; ').map(value => {
    let keyValPair = value.split('=');
    cookies.set(`${keyValPair[0]}`, keyValPair[1]);
})

loadProjects();

$('#signout').click(e => {
    e.preventDefault();
    $.ajax({
        url: 'http://localhost/playing/back/controllers/authentication/logout.php/',
        type: 'GET',
        success: () => {
            location.replace('http://localhost/playing/');
        }
    })
})

$('#add-new-project').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'none');
    $('.background').css('overflow', 'hidden');
    $('.form-container').css('display', 'block');
})

$('#cancel-add-project').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'initial');
    $('.background').css('overflow-y', 'scroll');
    $('.form-container').css('display', 'none');
})

$('#project-form').submit(e => {
    e.preventDefault();
    let pname = e.target.pname.value;
    let pdesc = e.target.pdesc.value;
    let creator = cookies.get('user');

    if(pname === '') {
        return alert('Project name is required');
    }

    let data = {
        name: pname,
        description: pdesc,
        creator: creator
    };

    $.ajax({
        url: 'http://localhost/playing/back/controllers/projects/post.php/',
        type: 'POST',
        data: JSON.stringify(data),
        success: function(response){
            $('.hide-on-click').css('display', 'initial');
            $('.background').css('overflow-y', 'scroll');
            $('.form-container').css('display', 'none');

            if(pname.length > 15) {
                pname = pname.substr(0, 15);
                pname = pname + '...';
            }

            let html = $("<div class=\"project\" id=\"p-"+ response.id + "\"><h5>"+ pname +"</h5></div>").hide().fadeIn(1000);
            if($('.project').last().length === 0) {
                $('#projects').append(html);
            } else {
                $('.project').last().after(html);
            }

        },
        error: function(){
            alert('FAILURE');
        }
    })
})

function loadProjects() {
    $.ajax({
        url: 'http://localhost/playing/back/controllers/projects/get.php/name/id/?username='+cookies.get('user'),
        type: 'GET',
        success: function(response){
            response.success.map(val => {
                let pname = val.name;
                if(pname.length > 15) {
                    pname = pname.substr(0, 15);
                    pname = pname + '...';
                }
                let html = "<div class=\"project\" id=\"p-"+ val.id + "\"><h5>"+ pname +"</h5></div>";
                if($('.project').last().length === 0) {
                    $('#projects').append(html);
                } else {
                    $('.project').last().after(html);
                }

                $(document).on("click", "#p-"+val.id, function(event) {
                    clickOnProject(event, val.id);
                });
                
            });
        },
        error: function(){
            return;
        }
    })
}

function clickOnProject(e, id) {
    e.preventDefault();
    location.replace('http://localhost/playing/front/pages/project.php?id='+id);
}