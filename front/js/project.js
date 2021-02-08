const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const project_id = urlParams.get('id');

let cookies = new Map();

document.cookie.split('; ').map(value => {
    let keyValPair = value.split('=');
    cookies.set(`${keyValPair[0]}`, keyValPair[1]);
})

loadUsers(project_id);
loadFiles(project_id);
loadNotes(project_id);
loadLinks(project_id);

// load all users
function loadUsers(id) {
    $.ajax({
        url: 'http://localhost/playing/back/controllers/project_users/get.php?project_id=' + id,
        type: 'GET',
        success: response => {
            let html = "<div class=\"collaborator\"><i class=\"fas fa-user\"></i></div>";
            if($('.collaborator').last().length === 0) {
                $('#collaborators-container').append(html);
            } else {
                $('.collaborator').last().after(html);
            }
        },
        error: () => {
            return;
        }
    })
}

// load all files
function loadFiles(id) {
    $.ajax({
        url: 'http://localhost/playing/back/controllers/files/get.php/name/?project_id=' + id,
        type: 'GET',
        success: response => {
            response.success.map(val => {
                let name = val.name;
                if(name.length >= 15) {
                    name = name.substring(0, 15);
                    name = name + '...';
                } 
                let html = "<div class=\"file item\"><div class=\"left\"><h5>"+name+"</h5></div><div class=\"right\"><i class=\"fas fa-trash del-file\"></i></div></div>";
                if($('.file').last().length === 0) {
                    $('#files').append(html);
                } else {
                    $('.file').last().after(html);
                }
            })
        },
        error: () => {
            return;
        }
    })
}

// load all notes
function loadNotes(id) {
    $.ajax({
        url: 'http://localhost/playing/back/controllers/notes/get.php/title/?project_id=' + id,
        type: 'GET',
        success: response => {
            response.success.map(val => {
                let title = val.title;
                if(title.length >= 15) {
                    title = title.substring(0, 15);
                    title = title + '...';
                } 
                let html = "<div class=\"note item\"><div class=\"left\"><h5>"+title+"</h5></div><div class=\"right\"><i class=\"fas fa-trash del-note\"></i></div></div>";
                if($('.note').last().length === 0) {
                    $('#notes').append(html);
                } else {
                    $('.note').last().after(html);
                }
            })
        },
        error: () => {
            return;
        }
    })
}

// load all links
function loadLinks(id) {
    $.ajax({
        url: 'http://localhost/playing/back/controllers/links/get.php/name/?project_id=' + id,
        type: 'GET',
        success: response => {
            response.success.map(val => {
                let name = val.name;
                if(name.length >= 15) {
                    name = name.substring(0, 15);
                    name = name + '...';
                } 
                let html = "<div class=\"link item\"><div class=\"left\"><h5>"+name+"</h5></div><div class=\"right\"><i class=\"fas fa-trash del-link\"></i></div></div>";
                if($('.link').last().length === 0) {
                    $('#links').append(html);
                } else {
                    $('.link').last().after(html);
                }
            })
        },
        error: () => {
            return;
        }
    })
}

$('#note-form').submit(e => {
    e.preventDefault();
    let ntitle = e.target.ntitle.value;
    let nbody = e.target.nbody.value;
    let author = cookies.get('user');

    if(ntitle === '') {
        return alert('Note title is required');
    }

    let data = {
        title: ntitle,
        body: nbody,
        author: author,
        project_id: project_id
    };

    $.ajax({
        url: 'http://localhost/playing/back/controllers/notes/post.php/',
        type: 'POST',
        data: JSON.stringify(data),
        success: function(response){
            $('.hide-on-click').css('display', 'initial');
            $('.background').css('overflow-y', 'scroll');
            $('#noteform').css('display', 'none');

            if(ntitle.length >= 15) {
                ntitle = ntitle.substring(0, 15);
                ntitle = ntitle + '...';
            }

            let html = $("<div class=\"note item\"><div class=\"left\"><h5>"+ntitle+"</h5></div><div class=\"right\"><i class=\"fas fa-trash del-note\" id=\"n-"+ response.id + "\"></i></div></div>").hide().fadeIn(1000);
            if($('.note').last().length === 0) {
                $('#notes').append(html);
            } else {
                $('.note').last().after(html);
            }
        },
        error: function(){
            alert('FAILURE');
        }
    })
})

$('#add-user').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'none');
    $('.background').css('overflow', 'hidden');
    $('#userform').css('display', 'block');
})

$('#note-icon').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'none');
    $('.background').css('overflow', 'hidden');
    $('#noteform').css('display', 'block');
})

$('#link-icon').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'none');
    $('.background').css('overflow', 'hidden');
    $('#linkform').css('display', 'block');
})

$('#cancel-add-user').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'initial');
    $('.background').css('overflow-y', 'scroll');
    $('#userform').css('display', 'none');
})

$('#cancel-add-note').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'initial');
    $('.background').css('overflow-y', 'scroll');
    $('#noteform').css('display', 'none');
})

$('#cancel-add-link').click(e => {
    e.preventDefault();
    $('.hide-on-click').css('display', 'initial');
    $('.background').css('overflow-y', 'scroll');
    $('#linkform').css('display', 'none');
})