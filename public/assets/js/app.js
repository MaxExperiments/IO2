/**
 * Fichier contenant les scripts ajax de la page
 */

var sendAjax = function ($url, $method) {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open($method,$url,true);
        xhr.setRequestHeader('Content-type','application/json')
        xhr.onload = function () {
            if (xhr.status == 200) {
                resolve(xhr.response);
            } else {
                reject(xhr.status);
            }
        }
        xhr.send();
    });
}

var destroyPost = function (id) {
    sendAjax('/posts/'+id+'/delete','GET').then(function (data) {
        var data = JSON.parse(data);
        if (data.success) $('#post-'+id).animate({
            height : 0,
            opacity : 0
        },1000);
        else alert(data.message);
    }, function (status) {
        alert(status);
    });
    return false;
}
